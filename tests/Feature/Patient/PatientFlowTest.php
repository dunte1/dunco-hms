<?php

namespace Tests\Feature\Patient;

use App\Models\Doctor;
use App\Models\LabRequest;
use App\Models\LabRequestItem;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\OpdVisit;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\QueueManagement;
use App\Models\Triage;
use App\Services\PatientFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientFlowTest extends TestCase
{
    use RefreshDatabase;

    protected PatientFlowService $flow;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->flow = app(PatientFlowService::class);
    }

    public function test_registration_creates_opd_visit_and_queue(): void
    {
        $patient = Patient::factory()->create([
            'first_name' => 'Flow',
            'last_name' => 'Test',
            'patient_no' => 'PATFLOW001',
        ]);

        $result = $this->flow->afterRegistration($patient, [
            'queue_type' => 'walk_in',
            'department' => 'General',
        ]);

        $this->assertNotNull($result['opd_visit_id'], 'OPD visit should be created');
        $this->assertNotNull($result['queue_id'], 'Queue token should be created');

        $opd = OpdVisit::find($result['opd_visit_id']);
        $this->assertSame('registered', $opd->status);

        $queue = QueueManagement::find($result['queue_id']);
        $this->assertSame($patient->id, $queue->patient_id);
        $this->assertSame($opd->id, $queue->opd_visit_id);
        $this->assertSame('waiting', $queue->status);
    }

    public function test_full_journey_registration_to_finalize(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW002']);
        $doctor = Doctor::factory()->create();
        $medicine = $this->makeMedicine(['name' => 'Test Med']);

        // 1. Registration
        $flowResult = $this->flow->afterRegistration($patient);
        $opd = OpdVisit::find($flowResult['opd_visit_id']);
        $this->assertSame('registered', $opd->status);

        // 2. Triage
        $nurseUser = \App\Models\User::factory()->create();
        $triage = Triage::create([
            'triage_number' => 'TRI-E2E-' . uniqid(),
            'patient_id' => $patient->id,
            'opd_visit_id' => $opd->id,
            'priority_level' => 'urgent',
            'chief_complaint' => 'Fever',
            'triaged_by' => $nurseUser->id,
            'triaged_at' => now(),
        ]);
        $this->flow->afterTriage($triage);
        $opd->refresh();
        $this->assertSame('triaged', $opd->status);

        // 3. Start consultation (manual)
        $this->assertTrue($this->flow->startConsultation($opd));
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);

        // 4. Lab request
        $lab = LabRequest::create([
            'request_number' => 'LAB-FLOW-001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
        ]);
        $this->flow->afterLabRequest($lab);
        $opd->refresh();
        $this->assertSame('lab_pending', $opd->status);

        // 5. Lab results complete
$labTest = $this->makeLabTest(['test_name' => 'Full Blood Count']);
        $lab->items()->create([
            'lab_test_id' => $labTest->id,
            'status' => 'pending',
        ]);
        $lab->update(['status' => 'completed']);
        $this->flow->afterLabResultsComplete($lab->fresh());
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);

        // 6. Prescription
        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'prescription_date' => now()->toDateString(),
            'diagnosis' => 'Viral fever',
            'status' => 'active',
        ]);
        $this->flow->afterPrescription($prescription);
        $opd->refresh();
        $this->assertSame('pharmacy_pending', $opd->status);

        // 7. Dispense last Rx
        $prescription->update(['status' => 'dispensed']);
        $this->flow->afterDispense($prescription->fresh());
        $opd->refresh();
        $this->assertSame('billing_pending', $opd->status);

        // 8. Finalize
        $opd->update(['diagnosis' => 'Viral fever']);
        $this->flow->finalizeVisit($opd->fresh());
        $opd->refresh();
        $this->assertSame('completed', $opd->status);

        // Queue should be completed
        $queue = QueueManagement::where('opd_visit_id', $opd->id)->first();
        $this->assertSame('completed', $queue->status);
    }

    public function test_illegal_transitions_are_blocked(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW003']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'in_consultation',
        ]);

        $this->assertFalse($this->flow->advanceOpd($opd, 'registered'));
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);

        $this->assertFalse($this->flow->advanceOpd($opd->refresh(), 'triaged'));
    }

    public function test_completed_visit_cannot_advance(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW004']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'completed',
        ]);

        $this->assertFalse($this->flow->advanceOpd($opd, 'in_consultation'));
        $this->assertFalse($this->flow->advanceOpd($opd->refresh(), 'discharged'));
    }

    public function test_queue_call_starts_consultation_when_pre_consult(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW005']);
        $flowResult = $this->flow->afterRegistration($patient);
        $opd = OpdVisit::find($flowResult['opd_visit_id']);
        $queue = QueueManagement::find($flowResult['queue_id']);

        $this->flow->afterQueueCalled($queue->fresh());
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);
    }

    public function test_dispense_with_open_lab_does_not_billing_pending(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW006']);
        $doctor = Doctor::factory()->create();

        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'pharmacy_pending',
        ]);

        LabRequest::create([
            'request_number' => 'LAB-FLOW-006',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'prescription_date' => now()->toDateString(),
            'status' => 'dispensed',
        ]);

        $this->flow->afterDispense($prescription);
        $opd->refresh();
        $this->assertSame('pharmacy_pending', $opd->status, 'Should stay pharmacy_pending while lab is open');
    }

    public function test_opd_visit_model_can_transition_to(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW007']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'registered',
        ]);

        $this->assertTrue($opd->canTransitionTo('triaged'));
        $this->assertTrue($opd->canTransitionTo('in_consultation'));
        $this->assertTrue($opd->canTransitionTo('lab_pending'));
        $this->assertTrue($opd->canTransitionTo('pharmacy_pending'));
        $this->assertTrue($opd->canTransitionTo('billing_pending'));
        $this->assertTrue($opd->canTransitionTo('completed'));
        $this->assertTrue($opd->canTransitionTo('discharged'));
    }

    public function test_patient_factory_creates_valid_patient(): void
    {
        $patient = Patient::factory()->create();
        $this->assertNotNull($patient->patient_no);
        $this->assertNotEmpty($patient->full_name);
    }

    public function test_lab_request_belongs_to_patient_and_opd(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATFLOW008']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'registered',
        ]);

        $lab = LabRequest::create([
            'request_number' => 'LAB-FLOW-008',
            'patient_id' => $patient->id,
            'opd_visit_id' => $opd->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $this->assertSame($patient->id, $lab->patient_id);
        $this->assertSame($opd->id, $lab->opd_visit_id);
        $this->assertSame($patient->id, $lab->patient->id);
    }
}
