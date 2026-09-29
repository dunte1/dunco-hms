<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\OtSchedule;
use App\Models\OtRoom;
use App\Models\DoctorDepartment;
use App\Models\AnaesthesiaAssessment;
use App\Models\AnaesthesiaRecord;
use App\Models\AnaesthesiaDrugGiven;
use App\Models\IntraopVital;
use App\Models\AnaesthesiaComplication;
use App\Models\PostAnaesthesiaReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G022AnaesthesiaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private OtSchedule $otSchedule;
    private Medicine $medicine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $category = MedicineCategory::create(['name' => 'Anaesthesia Drugs']);
        $this->medicine = Medicine::create([
            'name' => 'Propofol',
            'strength' => '10mg/ml',
            'category_id' => $category->id,
            'dosage_form' => 'injection',
            'unit_price' => 150.00,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
        ]);

        $department = DoctorDepartment::create(['name' => 'Anaesthesia']);
        $this->doctor->update(['doctor_department_id' => $department->id]);

        $room = OtRoom::create(['name' => 'OT-1', 'status' => 'available']);
        $this->otSchedule = OtSchedule::create([
            'schedule_number' => OtSchedule::generateScheduleNumber(),
            'patient_id' => $this->patient->id,
            'ot_room_id' => $room->id,
            'surgeon_id' => $this->doctor->id,
            'procedure_name' => 'Appendectomy',
            'procedure_type' => 'elective',
            'anesthesia_type' => 'general',
            'scheduled_date' => now()->toDateString(),
            'scheduled_start' => '08:00',
            'status' => 'in_progress',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_pre_anaesthetic_assessment_with_asa_and_airway(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.assessments.store'), [
            'patient_id' => $this->patient->id,
            'ot_schedule_id' => $this->otSchedule->id,
            'assessor_id' => $this->doctor->id,
            'asa_classification' => 2,
            'airway_assessment' => 'easy',
            'mallampati_score' => 1,
            'mouth_opening_cm' => 4.5,
            'neck_mobility' => 'full',
            'previous_anaesthesia_experience' => 'No complications during previous surgery',
            'airway_teeth_prosthesis' => false,
            'airway_plan' => 'Standard endotracheal intubation',
            'anaesthesia_plan' => 'General anaesthesia with propofol induction',
            'risk_assessment' => 'Low risk patient',
            'assessed_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('anaesthesia_assessments', [
            'patient_id' => $this->patient->id,
            'ot_schedule_id' => $this->otSchedule->id,
            'assessor_id' => $this->doctor->id,
            'asa_classification' => 2,
            'airway_assessment' => 'easy',
            'mallampati_score' => 1,
            'neck_mobility' => 'full',
        ]);
    }

    public function test_anaesthesia_record_creation_and_completion(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.store'), [
            'ot_schedule_id' => $this->otSchedule->id,
            'patient_id' => $this->patient->id,
            'anaesthetist_id' => $this->doctor->id,
            'anaesthesia_type' => 'general',
            'start_time' => now()->toDateTimeString(),
            'notes' => 'Induction smooth',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $record = AnaesthesiaRecord::where('ot_schedule_id', $this->otSchedule->id)->first();
        $this->assertNotNull($record);
        $this->assertEquals('in_progress', $record->status);
        $this->assertEquals('general', $record->anaesthesia_type);

        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.complete', $record), [
            'end_time' => now()->addHours(2)->toDateTimeString(),
            'extubation_time' => now()->addHours(2)->addMinutes(5)->toDateTimeString(),
            'total_duration_minutes' => 120,
            'ebl_ml' => 150,
            'urine_output_ml' => 300,
            'fluids_given_ml' => 2000,
            'blood_products' => 'None',
            'complications' => 'None',
            'notes' => 'Uneventful procedure',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('anaesthesia_records', [
            'id' => $record->id,
            'status' => 'completed',
            'ebl_ml' => 150,
            'urine_output_ml' => 300,
        ]);
    }

    public function test_drug_administration_recording(): void
    {
        $record = AnaesthesiaRecord::create([
            'ot_schedule_id' => $this->otSchedule->id,
            'patient_id' => $this->patient->id,
            'anaesthetist_id' => $this->doctor->id,
            'anaesthesia_type' => 'general',
            'start_time' => now(),
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.drugs.store', $record), [
            'medicine_id' => $this->medicine->id,
            'dose' => '20mg',
            'route' => 'iv',
            'time_administered' => now()->toDateTimeString(),
            'notes' => 'Induction dose',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('anaesthesia_drugs_given', [
            'anaesthesia_record_id' => $record->id,
            'medicine_id' => $this->medicine->id,
            'dose' => '20mg',
            'route' => 'iv',
        ]);
    }

    public function test_intra_op_vital_recording(): void
    {
        $record = AnaesthesiaRecord::create([
            'ot_schedule_id' => $this->otSchedule->id,
            'patient_id' => $this->patient->id,
            'anaesthetist_id' => $this->doctor->id,
            'anaesthesia_type' => 'general',
            'start_time' => now(),
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.vitals.store', $record), [
            'patient_id' => $this->patient->id,
            'time_recorded' => now()->toDateTimeString(),
            'heart_rate' => 72,
            'blood_pressure_sys' => 120,
            'blood_pressure_dia' => 80,
            'map' => 93,
            'spo2' => 99.5,
            'etco2' => 35.0,
            'temperature' => 36.5,
            'respiratory_rate' => 14,
            'notes' => 'Stable vitals',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('intraop_vitals', [
            'anaesthesia_record_id' => $record->id,
            'patient_id' => $this->patient->id,
            'heart_rate' => 72,
            'blood_pressure_sys' => 120,
            'spo2' => 99.5,
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_complication_recording(): void
    {
        $record = AnaesthesiaRecord::create([
            'ot_schedule_id' => $this->otSchedule->id,
            'patient_id' => $this->patient->id,
            'anaesthetist_id' => $this->doctor->id,
            'anaesthesia_type' => 'general',
            'start_time' => now(),
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.complications.store', $record), [
            'patient_id' => $this->patient->id,
            'complication_type' => 'hypotension',
            'severity' => 'moderate',
            'description' => 'BP dropped to 80/50 after induction',
            'treatment' => 'IV fluids and ephedrine 6mg',
            'outcome' => 'BP recovered to baseline',
            'occurred_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('anaesthesia_complications', [
            'anaesthesia_record_id' => $record->id,
            'patient_id' => $this->patient->id,
            'complication_type' => 'hypotension',
            'severity' => 'moderate',
            'reported_by' => $this->user->id,
        ]);
    }

    public function test_post_anaesthesia_review_with_aldrete_score(): void
    {
        $record = AnaesthesiaRecord::create([
            'ot_schedule_id' => $this->otSchedule->id,
            'patient_id' => $this->patient->id,
            'anaesthetist_id' => $this->doctor->id,
            'anaesthesia_type' => 'general',
            'start_time' => now()->subHours(2),
            'end_time' => now(),
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.anaesthesia.records.post-review.store', $record), [
            'patient_id' => $this->patient->id,
            'review_time' => now()->toDateTimeString(),
            'consciousness_level' => 15,
            'airway_patent' => true,
            'breathing_spontaneous' => true,
            'heart_rate' => 76,
            'blood_pressure_sys' => 118,
            'blood_pressure_dia' => 76,
            'spo2' => 98.0,
            'temperature' => 36.4,
            'pain_score' => 3,
            'nausea_vomiting' => false,
            'aldrete_score' => 9,
            'fit_for_discharge' => true,
            'reviewer_id' => $this->doctor->id,
            'notes' => 'Patient stable and ready for ward transfer',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('post_anaesthesia_reviews', [
            'anaesthesia_record_id' => $record->id,
            'patient_id' => $this->patient->id,
            'consciousness_level' => 15,
            'airway_patent' => true,
            'breathing_spontaneous' => true,
            'aldrete_score' => 9,
            'fit_for_discharge' => true,
            'reviewer_id' => $this->doctor->id,
        ]);
    }
}
