<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\FiscalPeriod;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G067FinanceJournalsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Account $debitAccount;
    private Account $creditAccount;
    private FiscalPeriod $period;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->debitAccount = Account::create([
            'account_code' => '5001',
            'account_name' => 'Operating Expenses',
            'account_type' => 'expense',
            'balance_type' => 'debit',
            'current_balance' => 0,
            'is_active' => true,
        ]);
        $this->creditAccount = Account::create([
            'account_code' => '1001',
            'account_name' => 'Cash',
            'account_type' => 'asset',
            'balance_type' => 'debit',
            'current_balance' => 0,
            'is_active' => true,
        ]);
        $this->period = FiscalPeriod::create([
            'name' => '2026 - Q1',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'status' => 'open',
        ]);
    }

    public function test_balanced_journal_entry_can_be_created(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.journal.store'), [
            'date' => '2026-09-28',
            'description' => 'Test balanced entry',
            'fiscal_period_id' => $this->period->id,
            'lines' => [
                ['account_id' => $this->debitAccount->id, 'debit' => 500, 'credit' => 0, 'description' => 'Debit line'],
                ['account_id' => $this->creditAccount->id, 'debit' => 0, 'credit' => 500, 'description' => 'Credit line'],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('journal_entries', [
            'description' => 'Test balanced entry',
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('journal_lines', [
            'account_id' => $this->debitAccount->id,
            'debit' => 500,
        ]);
        $this->assertDatabaseHas('journal_lines', [
            'account_id' => $this->creditAccount->id,
            'credit' => 500,
        ]);
    }

    public function test_unbalanced_journal_entry_is_rejected(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.journal.store'), [
            'date' => '2026-09-28',
            'description' => 'Test unbalanced entry',
            'fiscal_period_id' => $this->period->id,
            'lines' => [
                ['account_id' => $this->debitAccount->id, 'debit' => 500, 'credit' => 0, 'description' => 'Debit line'],
                ['account_id' => $this->creditAccount->id, 'debit' => 0, 'credit' => 300, 'description' => 'Credit line'],
            ],
        ]);

        $response->assertSessionHasErrors('lines');
        $this->assertDatabaseMissing('journal_entries', [
            'description' => 'Test unbalanced entry',
        ]);
    }

    public function test_journal_posting_changes_status(): void
    {
        $entry = JournalEntry::create([
            'entry_number' => JournalEntry::generateEntryNumber(),
            'date' => '2026-09-28',
            'description' => 'Test posting',
            'fiscal_period_id' => $this->period->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.journal.post', $entry));

        $response->assertRedirect();
        $entry->refresh();
        $this->assertEquals('posted', $entry->status);
        $this->assertEquals($this->user->id, $entry->posted_by);
        $this->assertNotNull($entry->posted_at);
    }

    public function test_journal_reversal_creates_opposite_entry(): void
    {
        $entry = JournalEntry::create([
            'entry_number' => 'JE-20260928-0001',
            'date' => '2026-09-28',
            'description' => 'Original entry',
            'fiscal_period_id' => $this->period->id,
            'status' => 'posted',
            'posted_by' => $this->user->id,
            'posted_at' => now(),
        ]);

        $entry->lines()->create([
            'account_id' => $this->debitAccount->id,
            'debit' => 500,
            'credit' => 0,
            'description' => 'Debit',
        ]);
        $entry->lines()->create([
            'account_id' => $this->creditAccount->id,
            'debit' => 0,
            'credit' => 500,
            'description' => 'Credit',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.journal.reverse', $entry));

        $response->assertRedirect();
        $entry->refresh();
        $this->assertEquals('reversed', $entry->status);
        $this->assertEquals($this->user->id, $entry->reversed_by);

        $reversedEntry = JournalEntry::where('reference_id', $entry->id)->first();
        $this->assertNotNull($reversedEntry);
        $this->assertEquals('draft', $reversedEntry->status);
        $this->assertStringContainsString('Reversal of', $reversedEntry->description);

        $reversedLines = $reversedEntry->lines;
        $this->assertCount(2, $reversedLines);
        $debitLine = $reversedLines->firstWhere('account_id', $this->debitAccount->id);
        $creditLine = $reversedLines->firstWhere('account_id', $this->creditAccount->id);
        $this->assertEquals(0, $debitLine->debit);
        $this->assertEquals(500, $debitLine->credit);
        $this->assertEquals(500, $creditLine->debit);
        $this->assertEquals(0, $creditLine->credit);
    }

    public function test_fiscal_period_close_prevents_new_entries(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.finance.fiscal-periods.close', $this->period));

        $response->assertRedirect();
        $this->period->refresh();
        $this->assertEquals('closed', $this->period->status);
        $this->assertEquals($this->user->id, $this->period->closed_by);
        $this->assertNotNull($this->period->closed_at);

        $response2 = $this->actingAs($this->user)->post(route('hms.journal.store'), [
            'date' => '2026-09-28',
            'description' => 'Entry to closed period',
            'fiscal_period_id' => $this->period->id,
            'lines' => [
                ['account_id' => $this->debitAccount->id, 'debit' => 100, 'credit' => 0],
                ['account_id' => $this->creditAccount->id, 'debit' => 0, 'credit' => 100],
            ],
        ]);

        $response2->assertSessionHasErrors('fiscal_period_id');
    }

    public function test_bank_account_crud(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.journal.store'), [
            'date' => '2026-09-28',
            'description' => 'Bank account test',
            'fiscal_period_id' => $this->period->id,
            'lines' => [
                ['account_id' => $this->debitAccount->id, 'debit' => 1000, 'credit' => 0],
                ['account_id' => $this->creditAccount->id, 'debit' => 0, 'credit' => 1000],
            ],
        ]);

        $bankAccount = BankAccount::create([
            'name' => 'Main Bank Account',
            'account_number' => '1234567890',
            'bank_name' => 'Test Bank',
            'account_id' => $this->creditAccount->id,
            'opening_balance' => 10000,
            'current_balance' => 11000,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'name' => 'Main Bank Account',
            'bank_name' => 'Test Bank',
        ]);

        $bankAccount->update(['current_balance' => 11500]);
        $this->assertEquals(11500, $bankAccount->fresh()->current_balance);

        $bankAccount->delete();
        $this->assertSoftDeleted('bank_accounts', ['id' => $bankAccount->id]);
    }
}
