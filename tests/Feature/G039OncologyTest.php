<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CancerRegistration;
use App\Models\OncologyTreatmentPlan;
use App\Models\ChemoProtocol;
use App\Models\ChemoCycle;
use App\Models\ChemoInfusion;
use App\Models\AdverseEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G039OncologyTest extends TestCase
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
        $this->doctor = Doctor::factory()->create();
    }

    public function test_cancer_registration_with_staging(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.oncology.registrations.store'), [
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Breast',
            'histology_type' => 'Invasive Ductal Carcinoma',
            'laterality' => 'left',
            'grade' => 'Grade II',
            'diagnosis_date' => '2026-09-01',
            'tnm_staging_t' => 'T2',
            'tnm_staging_n' => 'N1',
            'tnm_staging_m' => 'M0',
            'overall_stage' => 'II',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cancer_registrations', [
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Breast',
            'histology_type' => 'Invasive Ductal Carcinoma',
            'tnm_staging_t' => 'T2',
            'tnm_staging_n' => 'N1',
            'tnm_staging_m' => 'M0',
            'overall_stage' => 'II',
            'status' => 'active',
            'registered_by' => $this->user->id,
        ]);

        $reg = CancerRegistration::where('patient_id', $this->patient->id)->first();
        $this->assertNotNull($reg->registration_number);
        $this->assertStringStartsWith('CAN-', $reg->registration_number);
    }

    public function test_treatment_plan_creation(): void
    {
        $registration = CancerRegistration::create([
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Lung',
            'histology_type' => 'Adenocarcinoma',
            'diagnosis_date' => '2026-08-01',
            'overall_stage' => 'III',
            'registered_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.oncology.plans.store'), [
            'cancer_registration_id' => $registration->id,
            'patient_id' => $this->patient->id,
            'plan_name' => 'AC-T Protocol',
            'treatment_intent' => 'curative',
            'modalities' => ['chemotherapy', 'radiation'],
            'start_date' => '2026-10-01',
            'expected_end_date' => '2027-03-01',
            'created_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('oncology_treatment_plans', [
            'cancer_registration_id' => $registration->id,
            'patient_id' => $this->patient->id,
            'plan_name' => 'AC-T Protocol',
            'treatment_intent' => 'curative',
            'status' => 'proposed',
            'created_by' => $this->doctor->id,
        ]);
    }

    public function test_chemo_protocol_definition(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.oncology.protocols.store'), [
            'name' => 'AC-T (Doxorubicin/Cyclophosphamide followed by Paclitaxel)',
            'code' => 'AC-T-001',
            'regimen' => 'Doxorubicin 60mg/m2 + Cyclophosphamide 600mg/m2 q3w x4, then Paclitaxel 175mg/m2 q3w x4',
            'cycle_count' => 8,
            'cycle_days' => 21,
            'drugs' => [
                ['name' => 'Doxorubicin', 'dose' => '60mg/m2', 'route' => 'IV'],
                ['name' => 'Cyclophosphamide', 'dose' => '600mg/m2', 'route' => 'IV'],
                ['name' => 'Paclitaxel', 'dose' => '175mg/m2', 'route' => 'IV'],
            ],
            'indication' => 'Breast Cancer Stage II-III',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chemo_protocols', [
            'code' => 'AC-T-001',
            'name' => 'AC-T (Doxorubicin/Cyclophosphamide followed by Paclitaxel)',
            'cycle_count' => 8,
            'cycle_days' => 21,
            'is_active' => true,
        ]);
    }

    public function test_cycle_scheduling_and_completion(): void
    {
        $registration = CancerRegistration::create([
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Colon',
            'histology_type' => 'Adenocarcinoma',
            'diagnosis_date' => '2026-07-01',
            'overall_stage' => 'III',
            'registered_by' => $this->user->id,
        ]);

        $plan = OncologyTreatmentPlan::create([
            'cancer_registration_id' => $registration->id,
            'patient_id' => $this->patient->id,
            'plan_name' => 'FOLFOX',
            'treatment_intent' => 'curative',
            'modalities' => ['chemotherapy'],
            'start_date' => '2026-09-01',
            'created_by' => $this->doctor->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.oncology.plans.cycles.store', $plan), [
            'patient_id' => $this->patient->id,
            'cycle_number' => 1,
            'scheduled_date' => '2026-10-01',
            'prescribed_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chemo_cycles', [
            'treatment_plan_id' => $plan->id,
            'patient_id' => $this->patient->id,
            'cycle_number' => 1,
            'status' => 'scheduled',
        ]);

        $cycle = ChemoCycle::where('treatment_plan_id', $plan->id)->first();

        $response = $this->actingAs($this->user)->post(route('hms.oncology.cycles.complete', $cycle), [
            'actual_date' => '2026-10-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chemo_cycles', [
            'id' => $cycle->id,
            'status' => 'completed',
        ]);
        $this->assertEquals('2026-10-01', $cycle->fresh()->actual_date->format('Y-m-d'));
    }

    public function test_infusion_recording(): void
    {
        $registration = CancerRegistration::create([
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Breast',
            'histology_type' => 'Ductal Carcinoma',
            'diagnosis_date' => '2026-06-01',
            'overall_stage' => 'II',
            'registered_by' => $this->user->id,
        ]);

        $plan = OncologyTreatmentPlan::create([
            'cancer_registration_id' => $registration->id,
            'patient_id' => $this->patient->id,
            'plan_name' => 'AC',
            'treatment_intent' => 'curative',
            'modalities' => ['chemotherapy'],
            'start_date' => '2026-09-01',
            'created_by' => $this->doctor->id,
            'status' => 'active',
        ]);

        $cycle = ChemoCycle::create([
            'treatment_plan_id' => $plan->id,
            'patient_id' => $this->patient->id,
            'cycle_number' => 1,
            'scheduled_date' => '2026-10-01',
            'prescribed_by' => $this->doctor->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.oncology.cycles.infusions.store', $cycle), [
            'patient_id' => $this->patient->id,
            'start_time' => '2026-10-01 09:00:00',
            'drug_name' => 'Doxorubicin',
            'dose' => '60mg/m2',
            'volume_ml' => 250.00,
            'rate' => 125.00,
            'site' => 'arm',
            'nurse_id' => $this->user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chemo_infusions', [
            'chemo_cycle_id' => $cycle->id,
            'patient_id' => $this->patient->id,
            'drug_name' => 'Doxorubicin',
            'dose' => '60mg/m2',
            'site' => 'arm',
            'status' => 'running',
        ]);

        $infusion = ChemoInfusion::where('chemo_cycle_id', $cycle->id)->first();

        $response = $this->actingAs($this->user)->post(route('hms.oncology.infusions.complete', $infusion), [
            'end_time' => '2026-10-01 11:30:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chemo_infusions', [
            'id' => $infusion->id,
            'status' => 'completed',
        ]);
    }

    public function test_adverse_event_reporting_with_grade(): void
    {
        $registration = CancerRegistration::create([
            'patient_id' => $this->patient->id,
            'cancer_site' => 'Lung',
            'histology_type' => 'Squamous Cell',
            'diagnosis_date' => '2026-05-01',
            'overall_stage' => 'IV',
            'registered_by' => $this->user->id,
        ]);

        $plan = OncologyTreatmentPlan::create([
            'cancer_registration_id' => $registration->id,
            'patient_id' => $this->patient->id,
            'plan_name' => 'Carbo/Taxol',
            'treatment_intent' => 'palliative',
            'modalities' => ['chemotherapy'],
            'start_date' => '2026-09-01',
            'created_by' => $this->doctor->id,
            'status' => 'active',
        ]);

        $cycle = ChemoCycle::create([
            'treatment_plan_id' => $plan->id,
            'patient_id' => $this->patient->id,
            'cycle_number' => 1,
            'scheduled_date' => '2026-10-01',
            'prescribed_by' => $this->doctor->id,
            'status' => 'completed',
            'actual_date' => '2026-10-01',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.oncology.adverse-events.store'), [
            'patient_id' => $this->patient->id,
            'chemo_cycle_id' => $cycle->id,
            'treatment_plan_id' => $plan->id,
            'grade' => 3,
            'event_type' => 'neutropenia',
            'description' => 'Grade 3 neutropenia requiring G-CSF support',
            'onset_date' => '2026-10-08',
            'management' => 'Started Filgrastim 5mcg/kg daily',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('adverse_events', [
            'patient_id' => $this->patient->id,
            'chemo_cycle_id' => $cycle->id,
            'treatment_plan_id' => $plan->id,
            'grade' => 3,
            'event_type' => 'neutropenia',
            'reported_by' => $this->user->id,
        ]);
    }
}
