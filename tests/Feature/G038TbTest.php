<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\TbScreening;
use App\Models\TbCase;
use App\Models\TbTreatment;
use App\Models\TbAdherenceLog;
use App\Models\TbContact;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G038TbTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);
    }

    public function test_tb_screening_with_symptom_checklist(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.tb.screenings.store'), [
            'patient_id' => $this->patient->id,
            'screening_date' => '2026-09-28',
            'symptoms_cough' => true,
            'symptoms_fever' => true,
            'symptoms_night_sweats' => false,
            'symptoms_weight_loss' => true,
            'symptoms_other' => null,
            'contact_history' => true,
            'hiv_status' => 'negative',
            'chest_xray_result' => 'infiltrates right upper lobe',
            'screen_result' => 'suspected',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tb_screenings', [
            'patient_id' => $this->patient->id,
            'symptoms_cough' => true,
            'symptoms_fever' => true,
            'symptoms_night_sweats' => false,
            'symptoms_weight_loss' => true,
            'contact_history' => true,
            'hiv_status' => 'negative',
            'screen_result' => 'suspected',
            'screened_by' => $this->user->id,
        ]);
    }

    public function test_case_registration_with_gene_xpert_result(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.tb.cases.store'), [
            'patient_id' => $this->patient->id,
            'diagnosis_date' => '2026-09-28',
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'pulmonary' => true,
            'drug_susceptible' => true,
            'mdr_tb' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tb_cases', [
            'patient_id' => $this->patient->id,
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'pulmonary' => true,
            'drug_susceptible' => true,
            'mdr_tb' => false,
            'status' => 'active',
            'registered_by' => $this->user->id,
        ]);

        $case = TbCase::where('patient_id', $this->patient->id)->first();
        $this->assertStringStartsWith('TB', $case->case_number);
    }

    public function test_treatment_start_with_regimen(): void
    {
        $case = TbCase::create([
            'patient_id' => $this->patient->id,
            'case_number' => 'TB000001',
            'diagnosis_date' => '2026-09-28',
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'registered_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.tb.cases.treatment.store', $case), [
            'regimen' => '2RHZE',
            'phase' => 'intensive',
            'start_date' => '2026-09-28',
            'weight_kg' => 65.5,
            'drugs_given' => ['Rifampicin', 'Isoniazid', 'Pyrazinamide', 'Ethambutol'],
            'prescribed_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tb_treatments', [
            'tb_case_id' => $case->id,
            'regimen' => '2RHZE',
            'phase' => 'intensive',
            'weight_kg' => 65.5,
            'status' => 'active',
            'prescribed_by' => $this->doctor->id,
        ]);

        $case->refresh();
        $this->assertEquals('2026-09-28', $case->treatment_start_date->format('Y-m-d'));
    }

    public function test_adherence_logging_with_percentage_calculation(): void
    {
        $case = TbCase::create([
            'patient_id' => $this->patient->id,
            'case_number' => 'TB000002',
            'diagnosis_date' => '2026-09-28',
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'registered_by' => $this->user->id,
        ]);

        $treatment = TbTreatment::create([
            'tb_case_id' => $case->id,
            'regimen' => '2RHZE',
            'phase' => 'intensive',
            'start_date' => '2026-09-28',
            'prescribed_by' => $this->doctor->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.tb.treatment.adherence.store', $treatment), [
            'patient_id' => $this->patient->id,
            'log_date' => '2026-09-28',
            'doses_expected' => 4,
            'doses_taken' => 3,
            'missed_reason' => 'Forgot morning dose',
            'counselling_done' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tb_adherence_logs', [
            'tb_treatment_id' => $treatment->id,
            'patient_id' => $this->patient->id,
            'doses_expected' => 4,
            'doses_taken' => 3,
            'adherence_percentage' => 75.00,
            'missed_reason' => 'Forgot morning dose',
            'counselling_done' => true,
            'logged_by' => $this->user->id,
        ]);
    }

    public function test_contact_tracing_and_screening(): void
    {
        $case = TbCase::create([
            'patient_id' => $this->patient->id,
            'case_number' => 'TB000003',
            'diagnosis_date' => '2026-09-28',
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'registered_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.tb.cases.contacts.store', $case), [
            'contact_name' => 'John Smith',
            'contact_phone' => '+254700000000',
            'contact_relationship' => 'spouse',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tb_contacts', [
            'tb_case_id' => $case->id,
            'contact_name' => 'John Smith',
            'contact_phone' => '+254700000000',
            'contact_relationship' => 'spouse',
            'screened' => false,
            'screening_result' => 'pending',
        ]);

        $contact = TbContact::where('tb_case_id', $case->id)->first();

        $response = $this->actingAs($this->user)->post(route('hms.tb.contacts.screen', $contact), [
            'screening_result' => 'negative',
        ]);

        $response->assertRedirect();
        $contact->refresh();
        $this->assertTrue($contact->screened);
        $this->assertEquals('negative', $contact->screening_result);
        $this->assertEquals(now()->toDateString(), $contact->screened_date->format('Y-m-d'));
    }

    public function test_case_outcome_tracking(): void
    {
        $case = TbCase::create([
            'patient_id' => $this->patient->id,
            'case_number' => 'TB000004',
            'diagnosis_date' => '2026-09-28',
            'specimen_type' => 'sputum',
            'test_method' => 'gene_xpert',
            'test_result' => 'positive',
            'status' => 'active',
            'registered_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('hms.tb.cases.update', $case), [
            'status' => 'treatment_completed',
            'outcome_date' => '2026-12-28',
            'outcome' => 'cured',
        ]);

        $response->assertRedirect();
        $case->refresh();
        $this->assertEquals('treatment_completed', $case->status);
        $this->assertEquals('2026-12-28', $case->outcome_date->format('Y-m-d'));
        $this->assertEquals('cured', $case->outcome);
    }
}
