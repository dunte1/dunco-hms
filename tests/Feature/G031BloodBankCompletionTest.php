<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\BloodGroup;
use App\Models\BloodDonor;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\BloodDonation;
use App\Models\BloodScreeningResult;
use App\Models\BloodUnit;
use App\Models\CrossmatchRequest;
use App\Models\BloodIssue;
use App\Models\Transfusion;
use App\Models\TransfusionReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G031BloodBankCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private BloodGroup $bloodGroup;
    private BloodDonor $donor;
    private BloodInventory $inventory;
    private BloodRequest $bloodRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->bloodGroup = BloodGroup::create(['name' => 'O+']);
        $this->donor = BloodDonor::create([
            'donor_id' => 'DON-2026-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0712345678',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group_id' => $this->bloodGroup->id,
            'address' => 'Nairobi',
        ]);
        $this->inventory = BloodInventory::create([
            'blood_group_id' => $this->bloodGroup->id,
            'donor_id' => $this->donor->id,
            'bag_number' => 'BAG-001',
            'collection_date' => now()->subDays(5),
            'expiry_date' => now()->addDays(30),
            'status' => 'available',
        ]);
        $this->bloodRequest = BloodRequest::create([
            'request_number' => 'BR-2026-000001',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'blood_group_id' => $this->bloodGroup->id,
            'units_required' => 1,
            'reason' => 'Surgery',
            'status' => 'pending',
        ]);
    }

    public function test_donation_recording_with_screening(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.donations.store'), [
            'donor_id' => $this->donor->id,
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'donation_date' => now()->toDateString(),
            'donation_type' => 'whole_blood',
            'hemoglobin_g_dl' => 14.5,
            'blood_pressure_sys' => 120,
            'blood_pressure_dia' => 80,
            'pulse_rate' => 72,
            'weight_kg' => 70.0,
            'hiv_test' => 'negative',
            'hepatitis_b_test' => 'negative',
            'hepatitis_c_test' => 'negative',
            'syphilis_test' => 'negative',
            'malaria_test' => 'negative',
            'blood_group_confirmation' => 'O+',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('blood_donations', [
            'donor_id' => $this->donor->id,
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'status' => 'completed',
            'collected_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('blood_screening_results', [
            'hiv_test' => 'negative',
            'hepatitis_b_test' => 'negative',
            'is_eligible' => true,
            'screened_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('blood_donors', [
            'id' => $this->donor->id,
            'last_donation_date' => now()->toDateString(),
        ]);
    }

    public function test_donation_with_positive_screening_marks_ineligible(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.donations.store'), [
            'donor_id' => $this->donor->id,
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'donation_date' => now()->toDateString(),
            'donation_type' => 'whole_blood',
            'hiv_test' => 'positive',
            'hepatitis_b_test' => 'negative',
            'hepatitis_c_test' => 'negative',
            'syphilis_test' => 'negative',
            'malaria_test' => 'negative',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('blood_donations', [
            'donor_id' => $this->donor->id,
            'status' => 'ineligible',
        ]);

        $this->assertDatabaseHas('blood_screening_results', [
            'hiv_test' => 'positive',
            'is_eligible' => false,
        ]);
    }

    public function test_blood_unit_creation_and_status_transitions(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.units.store'), [
            'blood_inventory_id' => $this->inventory->id,
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30)->toDateString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $unit = BloodUnit::where('blood_inventory_id', $this->inventory->id)->first();
        $this->assertNotNull($unit);
        $this->assertEquals('available', $unit->status);
        $this->assertStringStartsWith('BU-', $unit->unit_number);

        // Reserve
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.units.reserve', $unit), [
            'patient_id' => $this->patient->id,
        ]);

        $response->assertRedirect();
        $unit->refresh();
        $this->assertEquals('reserved', $unit->status);
        $this->assertEquals($this->patient->id, $unit->reserved_for_patient_id);
        $this->assertNotNull($unit->reserved_at);

        // Issue
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.units.issue', $unit), [
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
        ]);

        $response->assertRedirect();
        $unit->refresh();
        $this->assertEquals('issued', $unit->status);

        $this->assertDatabaseHas('blood_issues', [
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'issued_by' => $this->user->id,
        ]);
    }

    public function test_crossmatch_request_and_result(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.crossmatch.store'), [
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'blood_group_id' => $this->bloodGroup->id,
            'sample_date' => now()->toDateString(),
            'requested_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $crossmatch = CrossmatchRequest::where('blood_request_id', $this->bloodRequest->id)->first();
        $this->assertNotNull($crossmatch);
        $this->assertEquals('pending', $crossmatch->result);

        // Record result
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.crossmatch.result', $crossmatch), [
            'result' => 'compatible',
        ]);

        $response->assertRedirect();
        $crossmatch->refresh();
        $this->assertEquals('compatible', $crossmatch->result);
        $this->assertEquals($this->user->id, $crossmatch->tested_by);
        $this->assertNotNull($crossmatch->tested_at);
    }

    public function test_issue_workflow(): void
    {
        $unit = BloodUnit::create([
            'blood_inventory_id' => $this->inventory->id,
            'unit_number' => BloodUnit::generateUnitNumber(),
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30),
            'status' => 'available',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.units.issue', $unit), [
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('blood_issues', [
            'blood_unit_id' => $unit->id,
            'issued_by' => $this->user->id,
        ]);

        $unit->refresh();
        $this->assertEquals('issued', $unit->status);
    }

    public function test_transfusion_start_complete(): void
    {
        $unit = BloodUnit::create([
            'blood_inventory_id' => $this->inventory->id,
            'unit_number' => BloodUnit::generateUnitNumber(),
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30),
            'status' => 'issued',
        ]);

        $issue = BloodIssue::create([
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'issued_by' => $this->user->id,
            'issued_at' => now(),
        ]);

        // Start transfusion
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.transfusions.store'), [
            'blood_issue_id' => $issue->id,
            'patient_id' => $this->patient->id,
            'blood_unit_id' => $unit->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $transfusion = Transfusion::where('blood_issue_id', $issue->id)->first();
        $this->assertNotNull($transfusion);
        $this->assertEquals('in_progress', $transfusion->status);
        $this->assertEquals($this->user->id, $transfusion->performed_by);
        $this->assertNotNull($transfusion->started_at);

        // Complete transfusion
        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.transfusions.complete', $transfusion), [
            'volume_transfused_ml' => 450,
        ]);

        $response->assertRedirect();
        $transfusion->refresh();
        $this->assertEquals('completed', $transfusion->status);
        $this->assertEquals(450, $transfusion->volume_transfused_ml);
        $this->assertNotNull($transfusion->ended_at);
        $this->assertNotNull($transfusion->duration_minutes);
    }

    public function test_transfusion_reaction_reporting(): void
    {
        $unit = BloodUnit::create([
            'blood_inventory_id' => $this->inventory->id,
            'unit_number' => BloodUnit::generateUnitNumber(),
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30),
            'status' => 'issued',
        ]);

        $issue = BloodIssue::create([
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'issued_by' => $this->user->id,
            'issued_at' => now(),
        ]);

        $transfusion = Transfusion::create([
            'blood_issue_id' => $issue->id,
            'patient_id' => $this->patient->id,
            'blood_unit_id' => $unit->id,
            'started_at' => now()->subMinutes(30),
            'performed_by' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.transfusions.reaction', $transfusion), [
            'reaction_type' => 'febrile',
            'severity' => 'moderate',
            'symptoms' => 'Fever, chills, rigors',
            'onset_time' => now()->subMinutes(15)->toDateTimeString(),
            'treatment_given' => 'Paracetamol administered',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('transfusion_reactions', [
            'transfusion_id' => $transfusion->id,
            'patient_id' => $this->patient->id,
            'reaction_type' => 'febrile',
            'severity' => 'moderate',
            'reported_by' => $this->user->id,
        ]);
    }

    public function test_severe_reaction_stops_transfusion(): void
    {
        $unit = BloodUnit::create([
            'blood_inventory_id' => $this->inventory->id,
            'unit_number' => BloodUnit::generateUnitNumber(),
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30),
            'status' => 'issued',
        ]);

        $issue = BloodIssue::create([
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'issued_by' => $this->user->id,
            'issued_at' => now(),
        ]);

        $transfusion = Transfusion::create([
            'blood_issue_id' => $issue->id,
            'patient_id' => $this->patient->id,
            'blood_unit_id' => $unit->id,
            'started_at' => now()->subMinutes(10),
            'performed_by' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.bloodbank.transfusions.reaction', $transfusion), [
            'reaction_type' => 'hemolytic',
            'severity' => 'life_threatening',
            'symptoms' => 'Severe hypotension, hemoglobinuria',
            'onset_time' => now()->subMinutes(5)->toDateTimeString(),
        ]);

        $response->assertRedirect();

        $transfusion->refresh();
        $this->assertEquals('stopped', $transfusion->status);
        $this->assertStringContainsString('hemolytic', $transfusion->stop_reason);
    }

    public function test_traceability_donor_to_transfusion(): void
    {
        // 1. Record donation
        $donation = BloodDonation::create([
            'donor_id' => $this->donor->id,
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'donation_date' => now()->toDateString(),
            'donation_type' => 'whole_blood',
            'status' => 'completed',
            'collected_by' => $this->user->id,
        ]);

        // 2. Create blood unit linked to donation
        $unit = BloodUnit::create([
            'blood_inventory_id' => $this->inventory->id,
            'donation_id' => $donation->id,
            'unit_number' => BloodUnit::generateUnitNumber(),
            'blood_group_id' => $this->bloodGroup->id,
            'volume_ml' => 450,
            'expiry_date' => now()->addDays(30),
            'status' => 'available',
        ]);

        // 3. Issue the unit
        $issue = BloodIssue::create([
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $this->bloodRequest->id,
            'patient_id' => $this->patient->id,
            'issued_by' => $this->user->id,
            'issued_at' => now(),
        ]);
        $unit->update(['status' => 'issued']);

        // 4. Start transfusion
        $transfusion = Transfusion::create([
            'blood_issue_id' => $issue->id,
            'patient_id' => $this->patient->id,
            'blood_unit_id' => $unit->id,
            'started_at' => now(),
            'performed_by' => $this->user->id,
            'status' => 'in_progress',
        ]);

        // Verify traceability chain
        $this->assertDatabaseHas('blood_donations', ['id' => $donation->id, 'donor_id' => $this->donor->id]);
        $this->assertDatabaseHas('blood_units', ['id' => $unit->id, 'donation_id' => $donation->id]);
        $this->assertDatabaseHas('blood_issues', ['id' => $issue->id, 'blood_unit_id' => $unit->id]);
        $this->assertDatabaseHas('transfusions', ['id' => $transfusion->id, 'blood_issue_id' => $issue->id]);

        // Verify the full chain through model relationships
        $loadedDonation = BloodDonation::with(['donor', 'bloodUnits.bloodIssue.transfusion'])->find($donation->id);
        $this->assertEquals($this->donor->id, $loadedDonation->donor->id);
        $this->assertNotNull($loadedDonation->bloodUnits->first()->bloodIssue);
        $this->assertNotNull($loadedDonation->bloodUnits->first()->bloodIssue->transfusion);
    }
}
