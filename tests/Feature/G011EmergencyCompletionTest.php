<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\EmergencyAdmission;
use App\Models\ResuscitationRecord;
use App\Models\TraumaAssessment;
use App\Models\ObservationStay;
use App\Models\EmergencyDisposition;
use App\Models\EmergencyProcedure;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\Ward;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G011EmergencyCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private EmergencyAdmission $admission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'admit patients']);
        $this->user->givePermissionTo('admit patients');

        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->admission = EmergencyAdmission::create([
            'admission_number' => 'EMR-2026-000001',
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->first_name . ' ' . $this->patient->last_name,
            'admission_time' => now(),
            'triage_level' => 'critical',
            'chief_complaint' => 'Cardiac arrest',
            'status' => 'active',
        ]);
    }

    public function test_resuscitation_record_creation_with_outcome(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.emergency.resuscitation.store', $this->admission), [
            'presenting_complaint' => 'Cardiac arrest on arrival',
            'initial_assessment' => 'Patient found unresponsive, no pulse detected.',
            'airway_status' => 'obstructed',
            'breathing_status' => 'absent',
            'circulation_status' => 'absent',
            'disability_neurological' => '3',
            'exposure' => 'Full exposure reveals no external injuries.',
            'interventions' => 'CPR initiated, intubation performed, epinephrine administered.',
            'outcome' => 'survived',
            'time_of_arrest' => now()->subMinutes(15)->toDateTimeString(),
            'resuscitation_duration_minutes' => 20,
            'performed_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resuscitation_records', [
            'emergency_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'outcome' => 'survived',
            'resuscitation_duration_minutes' => 20,
            'performed_by' => $this->doctor->id,
        ]);
    }

    public function test_trauma_assessment_with_gcs_and_injury_scoring(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.emergency.trauma.store', $this->admission), [
            'mechanism_of_injury' => 'Motor vehicle accident',
            'injury_type' => 'blunt',
            'head_face_neck' => 'Laceration on forehead, no neck tenderness.',
            'chest' => 'Chest wall tenderness, crepitus on palpation.',
            'abdomen' => 'Soft, non-tender.',
            'pelvis' => 'Stable on compression.',
            'extremities' => 'Right femur deformity noted.',
            'spinal' => 'No spinal tenderness.',
            'gcs_total' => 12,
            'pupils_left' => '3mm reactive',
            'pupils_right' => '3mm reactive',
            'vital_signs_snapshot' => [
                'systolic_bp' => 100,
                'diastolic_bp' => 60,
                'heart_rate' => 110,
                'respiratory_rate' => 22,
                'temperature' => 36.8,
                'spo2' => 94,
            ],
            'trauma_score' => 16,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('trauma_assessments', [
            'emergency_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'injury_type' => 'blunt',
            'gcs_total' => 12,
            'trauma_score' => 16,
        ]);
    }

    public function test_observation_stay_creation_and_discharge(): void
    {
        $ward = Ward::create(['name' => 'Observation Ward', 'code' => 'OW-01', 'ward_type' => 'observation', 'capacity' => 10, 'is_active' => true]);
        $bedType = BedType::create(['name' => 'Observation', 'charge_per_day' => 2000]);
        $bed = Bed::create(['bed_number' => 'OW-001', 'ward_name' => 'Observation Ward', 'bed_type_id' => $bedType->id, 'ward_id' => $ward->id, 'is_available' => true]);

        $response = $this->actingAs($this->user)->post(route('hms.emergency.observations.store', $this->admission), [
            'bed_id' => $bed->id,
            'observation_duration_hours' => 24,
            'observation_purpose' => 'Monitor for delayed internal bleeding',
            'initial_assessment' => 'Patient hemodynamically stable after initial treatment.',
            'started_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $observation = ObservationStay::where('emergency_admission_id', $this->admission->id)->first();
        $this->assertNotNull($observation);
        $this->assertEquals('active', $observation->status);

        $response = $this->actingAs($this->user)->post(route('hms.emergency.observations.discharge', $observation), [
            'status' => 'discharged',
            'disposition' => 'Discharged home with follow-up instructions',
            'ended_at' => now()->addHours(24)->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $observation->refresh();
        $this->assertEquals('discharged', $observation->status);
        $this->assertNotNull($observation->ended_at);
    }

    public function test_disposition_with_billing_deferred_flag(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.emergency.disposition.store', $this->admission), [
            'disposition_type' => 'admitted',
            'destination_ward' => 'Surgical Ward',
            'discharge_notes' => 'Patient requires surgical intervention.',
            'discharge_instructions' => 'NPO from midnight.',
            'discharged_by' => $this->doctor->id,
            'discharged_at' => now()->toDateTimeString(),
            'billing_deferred' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('emergency_dispositions', [
            'emergency_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'disposition_type' => 'admitted',
            'destination_ward' => 'Surgical Ward',
            'billing_deferred' => true,
            'discharged_by' => $this->doctor->id,
        ]);
    }

    public function test_emergency_procedure_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.emergency.resuscitation.store', $this->admission), [
            'presenting_complaint' => 'Respiratory failure',
            'initial_assessment' => 'Severe respiratory distress, SpO2 78%.',
            'airway_status' => 'compromised',
            'breathing_status' => 'severely_compromised',
            'circulation_status' => 'stable',
            'disability_neurological' => '14',
            'exposure' => 'No external injuries.',
            'interventions' => 'Emergency intubation and mechanical ventilation initiated.',
            'outcome' => 'survived',
            'performed_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resuscitation_records', [
            'emergency_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'outcome' => 'survived',
            'performed_by' => $this->doctor->id,
        ]);
    }
}
