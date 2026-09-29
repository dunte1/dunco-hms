<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Vaccine;
use App\Models\GrowthMeasurement;
use App\Models\DevelopmentalAssessment;
use App\Models\ImmunizationSchedule;
use App\Models\ChildProtectionCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G016PaediatricsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
    }

    public function test_growth_measurement_recording_with_zscores(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.growth.store'), [
            'patient_id' => $this->patient->id,
            'recorded_date' => '2026-09-28',
            'weight_grams' => 8500,
            'height_cm' => 72.5,
            'head_circumference_cm' => 45.0,
            'weight_for_age_zscore' => -1.5,
            'height_for_age_zscore' => -0.8,
            'weight_for_height_zscore' => -1.2,
            'malnutrition_status' => 'mild',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('growth_measurements', [
            'patient_id' => $this->patient->id,
            'weight_grams' => 8500,
            'height_cm' => 72.5,
            'head_circumference_cm' => 45.0,
            'weight_for_age_zscore' => -1.5,
            'height_for_age_zscore' => -0.8,
            'weight_for_height_zscore' => -1.2,
            'malnutrition_status' => 'mild',
            'recorded_by' => $this->user->id,
        ]);

        $measurement = GrowthMeasurement::where('patient_id', $this->patient->id)->first();
        $this->assertNotNull($measurement->bmi);
    }

    public function test_growth_measurement_auto_malnutrition_from_zscore(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.growth.store'), [
            'patient_id' => $this->patient->id,
            'recorded_date' => '2026-09-28',
            'weight_grams' => 5000,
            'height_cm' => 65.0,
            'head_circumference_cm' => 42.0,
            'weight_for_height_zscore' => -3.5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('growth_measurements', [
            'patient_id' => $this->patient->id,
            'malnutrition_status' => 'severe',
        ]);
    }

    public function test_developmental_assessment_with_red_flags(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.assessments.store'), [
            'patient_id' => $this->patient->id,
            'assessment_date' => '2026-09-28',
            'age_months' => 12,
            'motor_skills' => 'Can stand with support, crawling well',
            'language_skills' => 'Babbling, says mama/dada',
            'social_skills' => 'Stranger anxiety present, waves bye-bye',
            'cognitive_skills' => 'Object permanence emerging',
            'red_flags' => 'Not walking independently by 12 months',
            'overall_status' => 'delayed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('developmental_assessments', [
            'patient_id' => $this->patient->id,
            'age_months' => 12,
            'overall_status' => 'delayed',
            'red_flags' => 'Not walking independently by 12 months',
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_immunization_schedule_creation_and_completion(): void
    {
        $vaccine = Vaccine::create([
            'name' => 'BCG',
            'dose_count' => 1,
            'stock_quantity' => 100,
        ]);

        $schedule = ImmunizationSchedule::create([
            'vaccine_id' => $vaccine->id,
            'patient_id' => $this->patient->id,
            'dose_number' => 1,
            'due_date' => '2026-10-01',
            'status' => 'due',
            'notes' => 'First dose BCG',
        ]);

        $this->assertDatabaseHas('immunization_schedules', [
            'id' => $schedule->id,
            'status' => 'due',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.immunizations.complete', $schedule), [
            'batch_number' => 'BCG-2026-001',
            'site' => 'Left upper arm',
            'notes' => 'No immediate reaction',
        ]);

        $response->assertRedirect();
        $schedule->refresh();
        $this->assertEquals('completed', $schedule->status);
        $this->assertNotNull($schedule->completed_date);
        $this->assertEquals($this->user->id, $schedule->administered_by);
        $this->assertEquals('BCG-2026-001', $schedule->batch_number);
    }

    public function test_immunization_index_lists_due_schedules(): void
    {
        $vaccine = Vaccine::create(['name' => 'Polio', 'dose_count' => 4, 'stock_quantity' => 50]);

        ImmunizationSchedule::create([
            'vaccine_id' => $vaccine->id,
            'patient_id' => $this->patient->id,
            'dose_number' => 1,
            'due_date' => now()->toDateString(),
            'status' => 'due',
        ]);

        ImmunizationSchedule::create([
            'vaccine_id' => $vaccine->id,
            'patient_id' => $this->patient->id,
            'dose_number' => 1,
            'due_date' => now()->subDays(5)->toDateString(),
            'status' => 'completed',
            'completed_date' => now()->subDays(5)->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.paediatrics.immunizations.index'));

        $response->assertOk();
    }

    public function test_child_protection_case_workflow_open_to_closed(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.child-protection.store'), [
            'patient_id' => $this->patient->id,
            'concern_type' => 'neglect',
            'description' => 'Child appears malnourished and neglected. Multiple missed appointments.',
            'risk_level' => 'high',
            'reported_date' => '2026-09-28',
        ]);

        $response->assertRedirect();
        $case = ChildProtectionCase::where('patient_id', $this->patient->id)->first();
        $this->assertNotNull($case);
        $this->assertEquals('open', $case->status);
        $this->assertStringStartsWith('CPC-', $case->case_number);

        $caseNumber = $case->case_number;

        $response = $this->actingAs($this->user)->put(route('hms.paediatrics.child-protection.update', $case), [
            'status' => 'investigation',
            'assigned_to' => $this->user->id,
        ]);

        $response->assertRedirect();
        $case->refresh();
        $this->assertEquals('investigation', $case->status);
        $this->assertEquals($this->user->id, $case->assigned_to);

        $response = $this->actingAs($this->user)->put(route('hms.paediatrics.child-protection.update', $case), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $case->refresh();
        $this->assertEquals('confirmed', $case->status);

        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.child-protection.close', $case), [
            'outcome' => 'Child placed with relative. Follow-up scheduled for 30 days.',
        ]);

        $response->assertRedirect();
        $case->refresh();
        $this->assertEquals('closed', $case->status);
        $this->assertEquals('Child placed with relative. Follow-up scheduled for 30 days.', $case->outcome);
    }

    public function test_growth_chart_data_retrieval(): void
    {
        GrowthMeasurement::create([
            'patient_id' => $this->patient->id,
            'recorded_date' => '2026-01-15',
            'weight_grams' => 4000,
            'height_cm' => 53.0,
            'head_circumference_cm' => 37.0,
            'bmi' => 14.3,
            'malnutrition_status' => 'none',
            'recorded_by' => $this->user->id,
        ]);

        GrowthMeasurement::create([
            'patient_id' => $this->patient->id,
            'recorded_date' => '2026-09-28',
            'weight_grams' => 8500,
            'height_cm' => 72.5,
            'head_circumference_cm' => 45.0,
            'bmi' => 16.16,
            'malnutrition_status' => 'none',
            'recorded_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('hms.paediatrics.growth.chart', $this->patient)
        );

        $response->assertOk();
    }

    public function test_child_protection_validation_requires_description(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.child-protection.store'), [
            'patient_id' => $this->patient->id,
            'concern_type' => 'physical',
            'risk_level' => 'moderate',
            'reported_date' => '2026-09-28',
        ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_growth_measurement_validation_requires_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.paediatrics.growth.store'), [
            'patient_id' => $this->patient->id,
        ]);

        $response->assertSessionHasErrors(['recorded_date', 'weight_grams', 'height_cm', 'head_circumference_cm']);
    }
}
