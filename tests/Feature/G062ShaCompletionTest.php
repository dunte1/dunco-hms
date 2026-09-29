<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\InsuranceClaim;
use App\Models\InsuranceProvider;
use App\Models\ClaimBatch;
use App\Models\ClaimRejection;
use App\Models\ClaimRemittance;
use App\Models\Tariff;
use App\Models\BenefitPackage;
use App\Models\PatientInsurance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G062ShaCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private InsuranceProvider $provider;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->provider = InsuranceProvider::create([
            'name' => 'SHA Test Provider',
            'code' => 'SHA-TEST',
            'coverage_percentage' => 80,
            'is_active' => true,
        ]);
        $this->patient = Patient::factory()->create();
    }

    private function createClaim(array $overrides = []): InsuranceClaim
    {
        $pi = PatientInsurance::create([
            'patient_id' => $this->patient->id,
            'insurance_provider_id' => $this->provider->id,
            'policy_number' => 'POL-' . uniqid(),
            'effective_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'is_active' => true,
        ]);

        return InsuranceClaim::create(array_merge([
            'claim_number' => 'CLM-' . str_pad(InsuranceClaim::count() + 1, 6, '0', STR_PAD_LEFT),
            'patient_id' => $this->patient->id,
            'patient_insurance_id' => $pi->id,
            'claim_date' => now()->toDateString(),
            'service_date' => now()->toDateString(),
            'claimed_amount' => 5000,
            'status' => 'pending',
        ], $overrides));
    }

    public function test_claim_batch_creation_with_items(): void
    {
        $claim1 = $this->createClaim(['claimed_amount' => 3000]);
        $claim2 = $this->createClaim(['claimed_amount' => 7000]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claim-batches.store'), [
            'insurance_provider_id' => $this->provider->id,
            'claim_ids' => [$claim1->id, $claim2->id],
            'notes' => 'Test batch',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $batch = ClaimBatch::first();
        $this->assertNotNull($batch);
        $this->assertEquals($this->provider->id, $batch->insurance_provider_id);
        $this->assertEquals('draft', $batch->status);
        $this->assertEquals(2, $batch->claim_count);
        $this->assertEquals(10000, (float) $batch->total_amount);

        $claim1->refresh();
        $claim2->refresh();
        $this->assertEquals($batch->id, $claim1->batch_id);
        $this->assertEquals($batch->id, $claim2->batch_id);
    }

    public function test_batch_submission(): void
    {
        $batch = ClaimBatch::create([
            'batch_number' => 'BATCH-SUBMIT-001',
            'insurance_provider_id' => $this->provider->id,
            'claim_count' => 2,
            'total_amount' => 10000,
            'status' => 'draft',
        ]);

        $this->createClaim(['batch_id' => $batch->id, 'claimed_amount' => 5000]);
        $this->createClaim(['batch_id' => $batch->id, 'claimed_amount' => 5000]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claim-batches.submit', $batch));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $batch->refresh();
        $this->assertEquals('submitted', $batch->status);
        $this->assertEquals($this->user->id, $batch->submitted_by);
        $this->assertNotNull($batch->submitted_at);
    }

    public function test_claim_rejection_handling_resubmit(): void
    {
        $claim = $this->createClaim();

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claims.rejection.store', $claim), [
            'rejection_code' => 'ERR-001',
            'rejection_reason' => 'Missing documentation',
            'amount_rejected' => 5000,
        ]);

        $response->assertRedirect();
        $claim->refresh();
        $this->assertEquals('rejected', $claim->status);

        $rejection = ClaimRejection::where('insurance_claim_id', $claim->id)->first();
        $this->assertNotNull($rejection);
        $this->assertEquals('ERR-001', $rejection->rejection_code);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.rejections.handle', $rejection), [
            'action_taken' => 'resubmit',
        ]);

        $response->assertRedirect();
        $claim->refresh();
        $this->assertEquals('pending', $claim->status);

        $rejection->refresh();
        $this->assertEquals('resubmit', $rejection->action_taken);
        $this->assertEquals($this->user->id, $rejection->action_by);
    }

    public function test_claim_rejection_handling_write_off(): void
    {
        $claim = $this->createClaim(['paid_amount' => 1000]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claims.rejection.store', $claim), [
            'rejection_code' => 'ERR-002',
            'rejection_reason' => 'Service not covered',
            'amount_rejected' => 4000,
        ]);

        $rejection = ClaimRejection::where('insurance_claim_id', $claim->id)->first();

        $response = $this->actingAs($this->user)->post(route('hms.insurance.rejections.handle', $rejection), [
            'action_taken' => 'write_off',
        ]);

        $response->assertRedirect();
        $claim->refresh();
        $this->assertEquals('paid', $claim->status);
    }

    public function test_remittance_recording_and_reconciliation(): void
    {
        $batch = ClaimBatch::create([
            'batch_number' => 'BATCH-REM-001',
            'insurance_provider_id' => $this->provider->id,
            'claim_count' => 1,
            'total_amount' => 8000,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claim-batches.remittance.store', $batch), [
            'remittance_number' => 'REM-2026-001',
            'remittance_date' => now()->toDateString(),
            'remitted_amount' => 7500,
            'notes' => 'Partial payment',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $remittance = ClaimRemittance::first();
        $this->assertNotNull($remittance);
        $this->assertEquals($batch->id, $remittance->batch_id);
        $this->assertEquals(-500, (float) $remittance->variance);
        $this->assertEquals('received', $remittance->status);

        $batch->refresh();
        $this->assertEquals('accepted', $batch->status);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.remittances.reconcile', $remittance));

        $response->assertRedirect();
        $remittance->refresh();
        $this->assertEquals('reconciled', $remittance->status);
        $this->assertEquals($this->user->id, $remittance->reconciled_by);
        $this->assertNotNull($remittance->reconciled_at);
    }

    public function test_tariff_crud_with_effective_dates(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.insurance.tariffs.store'), [
            'code' => 'TAR-CONS-001',
            'name' => 'General Consultation',
            'insurance_provider_id' => $this->provider->id,
            'category' => 'consultation',
            'unit_price' => 2000,
            'effective_from' => now()->toDateString(),
            'effective_to' => now()->addYear()->toDateString(),
            'is_active' => true,
        ]);

        $response->assertRedirect(route('hms.insurance.tariffs.index'));

        $tariff = Tariff::where('code', 'TAR-CONS-001')->first();
        $this->assertNotNull($tariff);
        $this->assertEquals(2000, (float) $tariff->unit_price);
        $this->assertTrue($tariff->isCurrentlyEffective());

        $response = $this->actingAs($this->user)->put(route('hms.insurance.tariffs.update', $tariff), [
            'code' => 'TAR-CONS-001',
            'name' => 'General Consultation Updated',
            'insurance_provider_id' => $this->provider->id,
            'category' => 'consultation',
            'unit_price' => 2500,
            'effective_from' => now()->toDateString(),
            'effective_to' => now()->addYear()->toDateString(),
            'is_active' => true,
        ]);

        $response->assertRedirect(route('hms.insurance.tariffs.index'));
        $tariff->refresh();
        $this->assertEquals(2500, (float) $tariff->unit_price);
        $this->assertEquals('General Consultation Updated', $tariff->name);

        $response = $this->actingAs($this->user)->delete(route('hms.insurance.tariffs.destroy', $tariff));

        $response->assertRedirect(route('hms.insurance.tariffs.index'));
        $this->assertSoftDeleted('tariffs', ['id' => $tariff->id]);
    }

    public function test_tariff_unique_code_constraint(): void
    {
        Tariff::create([
            'code' => 'TAR-DUP-001',
            'name' => 'Dup Test',
            'unit_price' => 1000,
            'effective_from' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.tariffs.store'), [
            'code' => 'TAR-DUP-001',
            'name' => 'Dup Test 2',
            'unit_price' => 1500,
            'effective_from' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_benefit_package_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.insurance.tariffs.store'), [
            'code' => 'TAR-BP-001',
            'name' => 'Basic Package',
            'unit_price' => 5000,
            'effective_from' => now()->toDateString(),
            'is_active' => true,
        ]);

        $benefit = BenefitPackage::create([
            'insurance_provider_id' => $this->provider->id,
            'name' => 'SHA Basic Cover',
            'description' => 'Standard SHA coverage package',
            'coverage_percentage' => 80,
            'max_amount' => 100000,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('benefit_packages', [
            'insurance_provider_id' => $this->provider->id,
            'name' => 'SHA Basic Cover',
            'coverage_percentage' => 80,
            'is_active' => true,
        ]);

        $this->assertEquals(80, (float) $benefit->coverage_percentage);
        $this->assertEquals(100000, (float) $benefit->max_amount);
    }

    public function test_batch_submit_rejects_non_draft(): void
    {
        $batch = ClaimBatch::create([
            'batch_number' => 'BATCH-NODRAFT-001',
            'insurance_provider_id' => $this->provider->id,
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.insurance.claim-batches.submit', $batch));

        $response->assertSessionHas('error');
        $batch->refresh();
        $this->assertEquals('submitted', $batch->status);
    }
}
