<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Vaccine;
use App\Models\ImmunizationSchedule;
use App\Models\FpVisit;
use App\Models\SurveillanceCase;
use App\Models\NotifiableDiseaseReport;
use App\Models\OutbreakEvent;
use App\Models\HospitalBranch;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G040PublicHealthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Vaccine $vaccine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'view patients']);
        Permission::create(['name' => 'manage public health']);
        $this->user->givePermissionTo(['view patients', 'manage public health']);

        $this->patient = Patient::factory()->create();
        $this->vaccine = Vaccine::create([
            'name' => 'BCG',
            'dose_count' => 2,
            'stock_quantity' => 100,
        ]);
    }

    public function test_immunization_schedule_creation_and_completion(): void
    {
        $schedule = ImmunizationSchedule::create([
            'patient_id' => $this->patient->id,
            'vaccine_id' => $this->vaccine->id,
            'dose_number' => 1,
            'due_date' => now()->toDateString(),
            'status' => 'due',
        ]);

        $this->assertDatabaseHas('immunization_schedules', [
            'patient_id' => $this->patient->id,
            'vaccine_id' => $this->vaccine->id,
            'status' => 'due',
        ]);

        $response = $this->actingAs($this->user)->post(
            route('public-health.immunizations.complete', $schedule),
            [
                'batch_number' => 'BCG-2026-001',
                'site' => 'left_arm',
                'notes' => 'No adverse reaction',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('immunization_schedules', [
            'id' => $schedule->id,
            'status' => 'completed',
            'administered_by' => $this->user->id,
            'batch_number' => 'BCG-2026-001',
            'site' => 'left_arm',
        ]);
        $this->assertNotNull($schedule->fresh()->completed_date);
    }

    public function test_immunization_schedule_index(): void
    {
        ImmunizationSchedule::create([
            'patient_id' => $this->patient->id,
            'vaccine_id' => $this->vaccine->id,
            'dose_number' => 1,
            'due_date' => now()->toDateString(),
            'status' => 'due',
        ]);

        $response = $this->actingAs($this->user)->get(
            route('public-health.immunizations.schedule')
        );

        $response->assertOk();
    }

    public function test_family_planning_visit_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('public-health.fp.store'), [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->toDateString(),
            'method' => 'pills',
            'previous_method' => 'condom',
            'side_effects' => 'Mild nausea',
            'satisfaction_score' => 4,
            'next_visit_date' => now()->addMonths(3)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('fp_visits', [
            'patient_id' => $this->patient->id,
            'method' => 'pills',
            'previous_method' => 'condom',
            'counseled_by' => $this->user->id,
            'satisfaction_score' => 4,
        ]);
    }

    public function test_family_planning_visit_index(): void
    {
        FpVisit::create([
            'patient_id' => $this->patient->id,
            'visit_date' => now(),
            'method' => 'injectable',
            'counseled_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('public-health.fp.index')
        );

        $response->assertOk();
    }

    public function test_surveillance_case_reporting(): void
    {
        $facility = HospitalBranch::create([
            'name' => 'Main Hospital',
            'branch_code' => 'MH-001',
            'address' => '123 Health St',
            'phone' => '+254700000000',
            'email' => 'main@hospital.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('public-health.surveillance.store'), [
            'patient_id' => $this->patient->id,
            'disease_name' => 'Cholera',
            'icd_code' => 'A00',
            'notification_type' => 'case',
            'case_date' => now()->toDateString(),
            'facility_id' => $facility->id,
            'county' => 'Nairobi',
            'sub_county' => 'Westlands',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('surveillance_cases', [
            'patient_id' => $this->patient->id,
            'disease_name' => 'Cholera',
            'icd_code' => 'A00',
            'notification_type' => 'case',
            'status' => 'pending',
            'reported_by' => $this->user->id,
        ]);
    }

    public function test_surveillance_case_index(): void
    {
        SurveillanceCase::create([
            'disease_name' => 'Measles',
            'notification_type' => 'case',
            'case_date' => now(),
            'reported_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(
            route('public-health.surveillance.index')
        );

        $response->assertOk();
    }

    public function test_notifiable_disease_report(): void
    {
        $case = SurveillanceCase::create([
            'disease_name' => 'Cholera',
            'notification_type' => 'case',
            'case_date' => now(),
            'reported_by' => $this->user->id,
        ]);

        $report = NotifiableDiseaseReport::create([
            'surveillance_case_id' => $case->id,
            'report_week' => '39',
            'report_year' => '2026',
            'facility_code' => 'MH-001',
            'disease_name' => 'Cholera',
            'cases_count' => 5,
            'deaths_count' => 1,
            'reported_to_moh' => false,
        ]);

        $this->assertDatabaseHas('notifiable_disease_reports', [
            'surveillance_case_id' => $case->id,
            'disease_name' => 'Cholera',
            'cases_count' => 5,
            'deaths_count' => 1,
            'reported_to_moh' => false,
        ]);

        $report->update([
            'reported_to_moh' => true,
            'reported_at' => now(),
        ]);

        $this->assertTrue($report->fresh()->reported_to_moh);
        $this->assertNotNull($report->fresh()->reported_at);
    }

    public function test_outbreak_declaration_and_containment(): void
    {
        $facility = HospitalBranch::create([
            'name' => 'Outbreak Center',
            'branch_code' => 'OC-001',
            'address' => '456 Outbreak St',
            'phone' => '+254711111111',
            'email' => 'outbreak@hospital.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('public-health.outbreaks.store'), [
            'disease_name' => 'Cholera',
            'start_date' => now()->toDateString(),
            'facility_ids' => [$facility->id],
            'investigation_notes' => 'Multiple cases reported',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_events', [
            'disease_name' => 'Cholera',
            'status' => 'suspected',
            'declared_by' => $this->user->id,
            'total_cases' => 0,
            'total_deaths' => 0,
        ]);

        $outbreak = OutbreakEvent::latest()->first();

        // Confirm
        $response = $this->actingAs($this->user)->put(
            route('public-health.outbreaks.update', $outbreak),
            ['status' => 'confirmed', 'total_cases' => 10]
        );
        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_events', ['id' => $outbreak->id, 'status' => 'confirmed']);

        // Contain
        $response = $this->actingAs($this->user)->put(
            route('public-health.outbreaks.update', $outbreak),
            ['status' => 'contained', 'total_cases' => 15, 'total_deaths' => 2]
        );
        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_events', ['id' => $outbreak->id, 'status' => 'contained']);
        $this->assertNotNull($outbreak->fresh()->contained_at);

        // End
        $response = $this->actingAs($this->user)->put(
            route('public-health.outbreaks.update', $outbreak),
            ['status' => 'ended']
        );
        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_events', ['id' => $outbreak->id, 'status' => 'ended']);
        $this->assertNotNull($outbreak->fresh()->ended_at);
    }

    public function test_outbreak_status_workflow_suspected_to_ended(): void
    {
        $outbreak = OutbreakEvent::create([
            'disease_name' => 'Measles',
            'start_date' => now(),
            'status' => 'suspected',
            'declared_by' => $this->user->id,
            'declared_at' => now(),
        ]);

        $workflow = ['confirmed', 'contained', 'ended'];

        foreach ($workflow as $status) {
            $response = $this->actingAs($this->user)->put(
                route('public-health.outbreaks.update', $outbreak),
                ['status' => $status, 'total_cases' => 20]
            );
            $response->assertRedirect();
            $this->assertDatabaseHas('outbreak_events', [
                'id' => $outbreak->id,
                'status' => $status,
            ]);
        }

        $this->assertNotNull($outbreak->fresh()->contained_at);
        $this->assertNotNull($outbreak->fresh()->ended_at);
    }

    public function test_outbreak_index(): void
    {
        OutbreakEvent::create([
            'disease_name' => 'Dengue',
            'start_date' => now(),
            'status' => 'suspected',
            'declared_by' => $this->user->id,
            'declared_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(
            route('public-health.outbreaks.index')
        );

        $response->assertOk();
    }
}
