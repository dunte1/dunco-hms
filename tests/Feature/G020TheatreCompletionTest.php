<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\OtSchedule;
use App\Models\OtRoom;
use App\Models\SurgicalWaitingList;
use App\Models\PreopAssessment;
use App\Models\WhoSafetyChecklist;
use App\Models\TheatreTeam;
use App\Models\TheatreConsumable;
use App\Models\Specimen;
use App\Models\RecoveryRecord;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G020TheatreCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private OtRoom $room;
    private OtSchedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->room = OtRoom::create([
            'name' => 'OT-1',
            'type' => 'general',
            'status' => 'available',
        ]);

        $this->schedule = OtSchedule::create([
            'schedule_number' => OtSchedule::generateScheduleNumber(),
            'patient_id' => $this->patient->id,
            'ot_room_id' => $this->room->id,
            'surgeon_id' => $this->doctor->id,
            'procedure_name' => 'Appendectomy',
            'procedure_type' => 'elective',
            'anesthesia_type' => 'general',
            'scheduled_date' => now()->toDateString(),
            'scheduled_start' => '09:00',
            'status' => 'scheduled',
            'risk_level' => 'low',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_waiting_list_add(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ot.waiting-list.store'), [
            'patient_id' => $this->patient->id,
            'procedure_name' => 'Cholecystectomy',
            'urgency' => 'elective',
            'priority' => 1,
            'target_date' => now()->addWeek()->toDateString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('surgical_waiting_list', [
            'patient_id' => $this->patient->id,
            'procedure_name' => 'Cholecystectomy',
            'status' => 'waiting',
        ]);
    }

    public function test_waiting_list_schedule(): void
    {
        $item = SurgicalWaitingList::create([
            'patient_id' => $this->patient->id,
            'procedure_name' => 'Hernia Repair',
            'urgency' => 'urgent',
            'priority' => 2,
            'added_by' => $this->doctor->id,
            'added_date' => now()->toDateString(),
            'status' => 'waiting',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ot.waiting-list.schedule', $item));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $item->refresh();
        $this->assertEquals('scheduled', $item->status);
    }

    public function test_preop_assessment_with_asa(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ot.schedules.preop.store', $this->schedule), [
            'asa_classification' => 2,
            'airway_assessment' => 'easy',
            'comorbidities' => 'Hypertension',
            'allergies' => 'Penicillin',
            'medications' => 'Amlodipine 5mg',
            'npo_status' => true,
            'fasting_hours' => 8,
            'airway_teeth_prosthesis' => false,
            'weight_kg' => 72.5,
            'allergies_confirmed' => true,
            'risks_identified' => 'Mild hypertension',
            'plan' => 'Standard general anaesthesia',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('preop_assessments', [
            'patient_id' => $this->patient->id,
            'ot_schedule_id' => $this->schedule->id,
            'asa_classification' => 2,
            'airway_assessment' => 'easy',
            'npo_status' => true,
            'allergies_confirmed' => true,
        ]);
    }

    public function test_who_checklist_sign_in_completion(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('hms.ot.schedules.checklist.complete', [$this->schedule, 'sign_in']),
            [
                'site_marked' => true,
                'consent_confirmed' => true,
                'anaesthesia_safety_confirmed' => true,
                'instruments_counted' => false,
                'equipment_checked' => true,
                'key_concerns_communicated' => true,
                'prophylactic_antibiotics_given' => false,
                'essential_imaging_displayed' => false,
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('who_safety_checklists', [
            'ot_schedule_id' => $this->schedule->id,
            'checklist_type' => 'sign_in',
            'completed' => true,
            'site_marked' => true,
            'consent_confirmed' => true,
            'anaesthesia_safety_confirmed' => true,
            'completed_by' => $this->user->id,
        ]);
    }

    public function test_theatre_team_assignment(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ot.schedules.team.store', $this->schedule), [
            'role' => 'anaesthetist',
            'user_id' => $this->user->id,
            'notes' => 'Primary anaesthetist',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('theatre_teams', [
            'ot_schedule_id' => $this->schedule->id,
            'role' => 'anaesthetist',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_consumable_tracking(): void
    {
        $category = MedicineCategory::create(['name' => 'IV Fluids']);
        $medicine = Medicine::create([
            'name' => 'Normal Saline 500ml',
            'generic_name' => 'Sodium Chloride',
            'category_id' => $category->id,
            'dosage_form' => 'solution',
            'unit_price' => 150.00,
            'stock_quantity' => 100,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ot.schedules.consumables.store', $this->schedule), [
            'medicine_id' => $medicine->id,
            'item_name' => 'Normal Saline 500ml',
            'quantity' => 2,
            'unit_cost' => 150.00,
            'batch_number' => 'BATCH-001',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('theatre_consumables', [
            'ot_schedule_id' => $this->schedule->id,
            'item_name' => 'Normal Saline 500ml',
            'quantity' => 2,
            'batch_number' => 'BATCH-001',
            'added_by' => $this->user->id,
        ]);
    }

    public function test_specimen_collection(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ot.schedules.specimens.store', $this->schedule), [
            'specimen_type' => 'tissue',
            'description' => 'Appendix specimen',
            'collection_site' => 'Right iliac fossa',
            'container_type' => 'Formalin jar',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('specimens', [
            'ot_schedule_id' => $this->schedule->id,
            'patient_id' => $this->patient->id,
            'specimen_type' => 'tissue',
            'description' => 'Appendix specimen',
            'collected_by' => $this->user->id,
        ]);
    }

    public function test_recovery_admission_and_discharge(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ot.recovery.store'), [
            'ot_schedule_id' => $this->schedule->id,
            'patient_id' => $this->patient->id,
            'gcs' => 15,
            'vital_signs_snapshot' => ['bp' => '120/80', 'hr' => 72, 'spo2' => 99],
            'pain_score' => 3,
            'nausea_vomiting' => false,
            'temperature' => 36.8,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $recovery = RecoveryRecord::where('ot_schedule_id', $this->schedule->id)->first();
        $this->assertNotNull($recovery);
        $this->assertEquals('monitoring', $recovery->status);

        $response = $this->actingAs($this->user)->post(route('hms.ot.recovery.discharge', $recovery), [
            'discharge_criteria_met' => true,
            'discharge_notes' => 'Patient stable, vitals normal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $recovery->refresh();
        $this->assertEquals('recovered', $recovery->status);
        $this->assertTrue($recovery->discharge_criteria_met);
        $this->assertNotNull($recovery->discharge_time);
    }
}
