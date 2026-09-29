<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Charge;
use App\Models\Refund;
use App\Models\Discount;
use App\Models\Waiver;
use App\Models\CashierSession;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G059BillingCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->invoice = Invoice::create([
            'invoice_number' => 'INV-TEST-000001',
            'patient_id' => $this->patient->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'subtotal' => 10000,
            'tax_amount' => 1000,
            'discount_amount' => 0,
            'total_amount' => 11000,
            'paid_amount' => 0,
            'balance_amount' => 11000,
            'status' => 'pending',
        ]);
    }

    public function test_charge_creation_links_to_patient_and_invoice(): void
    {
        $service = Service::create([
            'name' => 'Consultation',
            'code' => 'CONS-001',
            'default_price' => 1500,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.billing.charges.store'), [
            'patient_id' => $this->patient->id,
            'invoice_id' => $this->invoice->id,
            'service_id' => $service->id,
            'description' => 'General Consultation',
            'quantity' => 1,
            'unit_price' => 1500,
            'tax_rate' => 10,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Charge created successfully');

        $this->assertDatabaseHas('charges', [
            'patient_id' => $this->patient->id,
            'invoice_id' => $this->invoice->id,
            'service_id' => $service->id,
            'description' => 'General Consultation',
            'status' => 'charged',
            'charged_by' => $this->user->id,
        ]);

        // Invoice totals should be updated
        $this->invoice->refresh();
        $this->assertEquals(11500, (float) $this->invoice->subtotal);
        $this->assertEquals(1150, (float) $this->invoice->tax_amount);
    }

    public function test_charge_creation_without_invoice(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.billing.charges.store'), [
            'patient_id' => $this->patient->id,
            'description' => 'Walk-in Service',
            'quantity' => 2,
            'unit_price' => 500,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('charges', [
            'patient_id' => $this->patient->id,
            'invoice_id' => null,
            'description' => 'Walk-in Service',
            'total' => 1000,
            'status' => 'charged',
        ]);
    }

    public function test_charge_reversal(): void
    {
        $charge = Charge::create([
            'patient_id' => $this->patient->id,
            'invoice_id' => $this->invoice->id,
            'description' => 'Lab Test',
            'quantity' => 1,
            'unit_price' => 800,
            'total' => 800,
            'status' => 'charged',
            'charged_by' => $this->user->id,
            'charged_at' => now(),
        ]);

        // Update invoice to reflect the charge
        $this->invoice->update([
            'subtotal' => 10800,
            'total_amount' => 11800,
            'balance_amount' => 11800,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.billing.charges.reverse', $charge));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Charge reversed successfully');

        $charge->refresh();
        $this->assertEquals('reversed', $charge->status);

        $this->invoice->refresh();
        $this->assertEquals(10000, (float) $this->invoice->subtotal);
    }

    public function test_refund_request_workflow(): void
    {
        $payment = Payment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'patient_id' => $this->patient->id,
            'amount' => 5000,
            'status' => 'completed',
        ]);

        $this->invoice->update([
            'paid_amount' => 5000,
            'balance_amount' => 6000,
            'status' => 'partial',
        ]);

        // Request refund
        $response = $this->actingAs($this->user)->post(route('hms.billing.payments.request-refund', $payment), [
            'amount' => 2000,
            'reason' => 'Duplicate payment',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Refund request submitted for approval');

        $refund = Refund::where('payment_id', $payment->id)->first();
        $this->assertNotNull($refund);
        $this->assertEquals('pending', $refund->status);
        $this->assertEquals(2000, (float) $refund->amount);
        $this->assertEquals($this->user->id, $refund->requested_by);

        // Approve refund
        $approver = User::factory()->create();
        $response = $this->actingAs($approver)->post(route('hms.billing.refunds.approve', $refund));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Refund approved and processed');

        $refund->refresh();
        $this->assertEquals('completed', $refund->status);
        $this->assertEquals($approver->id, $refund->approved_by);
        $this->assertNotNull($refund->processed_at);

        // Invoice balance should be restored
        $this->invoice->refresh();
        $this->assertEquals(3000, (float) $this->invoice->paid_amount);
        $this->assertEquals(8000, (float) $this->invoice->balance_amount);
    }

    public function test_refund_rejection(): void
    {
        $payment = Payment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'patient_id' => $this->patient->id,
            'amount' => 5000,
        ]);

        $refund = Refund::create([
            'payment_id' => $payment->id,
            'invoice_id' => $this->invoice->id,
            'amount' => 1000,
            'reason' => 'Changed mind',
            'status' => 'pending',
            'requested_by' => $this->user->id,
        ]);

        $approver = User::factory()->create();
        $response = $this->actingAs($approver)->post(route('hms.billing.refunds.reject', $refund));

        $response->assertRedirect();

        $refund->refresh();
        $this->assertEquals('rejected', $refund->status);
    }

    public function test_discount_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.billing.discounts.store'), [
            'invoice_id' => $this->invoice->id,
            'discount_type' => 'percentage',
            'value' => 10,
            'reason' => 'Loyalty discount',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Discount applied successfully');

        $this->assertDatabaseHas('discounts', [
            'invoice_id' => $this->invoice->id,
            'discount_type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);

        $this->invoice->refresh();
        $this->assertEquals(1000, (float) $this->invoice->discount_amount);
        $this->assertEquals(10000, (float) $this->invoice->total_amount);
    }

    public function test_fixed_discount_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.billing.discounts.store'), [
            'invoice_id' => $this->invoice->id,
            'discount_type' => 'fixed',
            'value' => 500,
            'reason' => 'Staff discount',
        ]);

        $response->assertRedirect();

        $this->invoice->refresh();
        $this->assertEquals(500, (float) $this->invoice->discount_amount);
        $this->assertEquals(10500, (float) $this->invoice->total_amount);
    }

    public function test_waiver_approval_workflow(): void
    {
        // Request waiver
        $response = $this->actingAs($this->user)->post(route('hms.billing.waivers.store'), [
            'invoice_id' => $this->invoice->id,
            'amount' => 2000,
            'reason' => 'Financial hardship',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Waiver request submitted for approval');

        $waiver = Waiver::where('invoice_id', $this->invoice->id)->first();
        $this->assertNotNull($waiver);
        $this->assertEquals('pending', $waiver->status);

        // Approve waiver
        $approver = User::factory()->create();
        $response = $this->actingAs($approver)->post(route('hms.billing.waivers.approve', $waiver));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Waiver approved successfully');

        $waiver->refresh();
        $this->assertEquals('approved', $waiver->status);
        $this->assertEquals($approver->id, $waiver->approved_by);

        // Invoice should reflect waiver
        $this->invoice->refresh();
        $this->assertEquals(2000, (float) $this->invoice->discount_amount);
        $this->assertEquals(9000, (float) $this->invoice->total_amount);
        $this->assertEquals(9000, (float) $this->invoice->balance_amount);
    }

    public function test_waiver_rejection(): void
    {
        $waiver = Waiver::create([
            'invoice_id' => $this->invoice->id,
            'amount' => 1000,
            'reason' => 'Test waiver',
            'requested_by' => $this->user->id,
            'status' => 'pending',
        ]);

        $approver = User::factory()->create();
        $response = $this->actingAs($approver)->post(route('hms.billing.waivers.reject', $waiver));

        $response->assertRedirect();

        $waiver->refresh();
        $this->assertEquals('rejected', $waiver->status);
    }

    public function test_cashier_session_open_close_with_variance(): void
    {
        // Open session
        $response = $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.store'), [
            'opening_balance' => 10000,
            'notes' => 'Morning shift',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Cashier session opened successfully');

        $session = CashierSession::where('user_id', $this->user->id)->where('status', 'open')->first();
        $this->assertNotNull($session);
        $this->assertEquals(10000, (float) $session->opening_balance);

        // Close session with correct balance
        $response = $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.close'), [
            'closing_balance' => 10000,
            'notes' => 'No sales today',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Cashier session closed. Balance is correct.');

        $session->refresh();
        $this->assertEquals('closed', $session->status);
        $this->assertNotNull($session->closed_at);
        $this->assertEquals(0, (float) $session->variance);
    }

    public function test_cashier_session_variance_calculation(): void
    {
        // Open session
        $response = $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.store'), [
            'opening_balance' => 5000,
        ]);

        $session = CashierSession::where('user_id', $this->user->id)->where('status', 'open')->first();

        // Close with variance
        $response = $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.close'), [
            'closing_balance' => 5500,
        ]);

        $session->refresh();
        $this->assertEquals('closed', $session->status);
        // Variance = closing_balance - expected_balance (expected = opening + payments)
        // With no payments, expected = 5000, variance = 5500 - 5000 = 500
        $this->assertEquals(500, (float) $session->variance);
    }

    public function test_cashier_session_prevents_duplicate_open(): void
    {
        // Open first session
        $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.store'), [
            'opening_balance' => 10000,
        ]);

        // Try to open second session
        $response = $this->actingAs($this->user)->post(route('hms.billing.cashier-sessions.store'), [
            'opening_balance' => 5000,
        ]);

        $response->assertSessionHasErrors('error');
    }

    public function test_charge_reversal_fails_if_not_charged(): void
    {
        $charge = Charge::create([
            'patient_id' => $this->patient->id,
            'description' => 'Pending charge',
            'quantity' => 1,
            'unit_price' => 500,
            'total' => 500,
            'status' => 'pending',
            'charged_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.billing.charges.reverse', $charge));

        $response->assertSessionHasErrors('error');
        $charge->refresh();
        $this->assertEquals('pending', $charge->status);
    }

    public function test_refund_amount_cannot_exceed_payment(): void
    {
        $payment = Payment::factory()->create([
            'invoice_id' => $this->invoice->id,
            'patient_id' => $this->patient->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.billing.payments.request-refund', $payment), [
            'amount' => 2000,
            'reason' => 'Over refund',
        ]);

        $response->assertSessionHasErrors('amount');
    }
}
