<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\LabCategory;
use App\Models\LabRequest;
use App\Models\LabRequestItem;
use App\Models\LabSpecimen;
use App\Models\LabWorklist;
use App\Models\LabWorklistItem;
use App\Models\LabResultVerification;
use App\Models\LabCriticalAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G025LabCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private LabRequest $labRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();

        $category = LabCategory::create(['name' => 'Biochemistry']);
        $test = LabTest::create([
            'test_name' => 'Blood Sugar',
            'category_id' => $category->id,
            'price' => 500,
            'is_active' => true,
        ]);

        $this->labRequest = LabRequest::create([
            'request_number' => 'LAB-2026-000001',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        LabRequestItem::create([
            'lab_request_id' => $this->labRequest->id,
            'lab_test_id' => $test->id,
            'status' => 'pending',
        ]);
    }

    public function test_specimen_creation_from_lab_request(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.lab.specimens.store'), [
            'lab_request_id' => $this->labRequest->id,
            'specimen_type' => 'blood',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('lab_specimens', [
            'lab_request_id' => $this->labRequest->id,
            'patient_id' => $this->patient->id,
            'specimen_type' => 'blood',
            'status' => 'collected',
            'collected_by' => $this->user->id,
        ]);

        $specimen = LabSpecimen::where('lab_request_id', $this->labRequest->id)->first();
        $this->assertNotNull($specimen->specimen_number);
        $this->assertStringStartsWith('SPE-', $specimen->specimen_number);
        $this->assertNotNull($specimen->collected_at);
    }

    public function test_specimen_receive_workflow(): void
    {
        $specimen = LabSpecimen::create([
            'specimen_number' => LabSpecimen::generateSpecimenNumber(),
            'lab_request_id' => $this->labRequest->id,
            'patient_id' => $this->patient->id,
            'specimen_type' => 'urine',
            'status' => 'collected',
            'collected_by' => $this->user->id,
            'collected_at' => now(),
        ]);

        $receiver = User::factory()->create();
        $response = $this->actingAs($receiver)->post(route('hms.lab.specimens.receive', $specimen));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $specimen->refresh();
        $this->assertEquals('received', $specimen->status);
        $this->assertEquals($receiver->id, $specimen->received_by);
        $this->assertNotNull($specimen->received_at);
    }

    public function test_specimen_rejection_with_reason(): void
    {
        $specimen = LabSpecimen::create([
            'specimen_number' => LabSpecimen::generateSpecimenNumber(),
            'lab_request_id' => $this->labRequest->id,
            'patient_id' => $this->patient->id,
            'specimen_type' => 'stool',
            'status' => 'collected',
            'collected_by' => $this->user->id,
            'collected_at' => now(),
        ]);

        $receiver = User::factory()->create();
        $response = $this->actingAs($receiver)->post(route('hms.lab.specimens.reject', $specimen), [
            'rejection_reason' => 'Insufficient sample volume',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $specimen->refresh();
        $this->assertEquals('rejected', $specimen->status);
        $this->assertEquals('Insufficient sample volume', $specimen->rejection_reason);
        $this->assertEquals($receiver->id, $specimen->received_by);
    }

    public function test_worklist_creation_with_items(): void
    {
        $item = $this->labRequest->items()->first();

        $response = $this->actingAs($this->user)->post(route('hms.lab.worklists.store'), [
            'name' => 'Morning Biochemistry',
            'date' => now()->toDateString(),
            'department' => 'biochemistry',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $worklist = LabWorklist::where('name', 'Morning Biochemistry')->first();
        $this->assertNotNull($worklist);
        $this->assertEquals('active', $worklist->status);
        $this->assertEquals($this->user->id, $worklist->created_by);

        // Add item to worklist
        $response = $this->actingAs($this->user)->post(route('hms.lab.worklists.items', $worklist), [
            'lab_request_item_ids' => [$item->id],
            'priorities' => ['urgent'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('lab_worklist_items', [
            'worklist_id' => $worklist->id,
            'lab_request_item_id' => $item->id,
            'priority' => 'urgent',
            'status' => 'pending',
        ]);
    }

    public function test_worklist_completion(): void
    {
        $worklist = LabWorklist::create([
            'name' => 'Evening Haematology',
            'date' => now()->toDateString(),
            'department' => 'haematology',
            'status' => 'active',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.lab.worklists.complete', $worklist));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $worklist->refresh();
        $this->assertEquals('completed', $worklist->status);
    }

    public function test_result_verification_workflow(): void
    {
        $item = $this->labRequest->items()->first();
        $item->update(['result_value' => '120', 'unit' => 'mg/dL', 'status' => 'completed']);

        // Technician verifies
        $technician = User::factory()->create();
        $response = $this->actingAs($technician)->post(route('hms.lab.verifications.verify', $item));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $verification = LabResultVerification::where('lab_request_item_id', $item->id)->first();
        $this->assertNotNull($verification);
        $this->assertEquals('verified', $verification->status);
        $this->assertEquals($technician->id, $verification->verified_by);
        $this->assertNotNull($verification->verified_at);

        // Pathologist approves
        $pathologist = User::factory()->create();
        $response = $this->actingAs($pathologist)->post(route('hms.lab.verifications.approve', $item));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $verification->refresh();
        $this->assertEquals('approved', $verification->status);
        $this->assertEquals($pathologist->id, $verification->approved_by);
        $this->assertNotNull($verification->approved_at);
    }

    public function test_critical_alert_creation_and_acknowledgement(): void
    {
        $item = $this->labRequest->items()->first();

        $alert = LabCriticalAlert::create([
            'lab_request_item_id' => $item->id,
            'patient_id' => $this->patient->id,
            'alert_type' => 'critical_high',
            'result_value' => '450',
            'reference_range' => '70-140',
            'message' => 'Blood sugar critically high at 450 mg/dL',
            'is_acknowledged' => false,
        ]);

        $this->assertDatabaseHas('lab_critical_alerts', [
            'id' => $alert->id,
            'is_acknowledged' => false,
        ]);

        $notifier = User::factory()->create();
        $alert->acknowledge($notifier);

        $alert->refresh();
        $this->assertTrue($alert->is_acknowledged);
        $this->assertEquals($notifier->id, $alert->acknowledged_by);
        $this->assertNotNull($alert->acknowledged_at);
    }

    public function test_specimen_rejection_requires_reason(): void
    {
        $specimen = LabSpecimen::create([
            'specimen_number' => LabSpecimen::generateSpecimenNumber(),
            'lab_request_id' => $this->labRequest->id,
            'patient_id' => $this->patient->id,
            'specimen_type' => 'swab',
            'status' => 'collected',
            'collected_by' => $this->user->id,
            'collected_at' => now(),
        ]);

        $receiver = User::factory()->create();
        $response = $this->actingAs($receiver)->post(route('hms.lab.specimens.reject', $specimen), [
            'rejection_reason' => '',
        ]);

        $response->assertSessionHasErrors('rejection_reason');
    }

    public function test_verify_requires_result_value(): void
    {
        $item = $this->labRequest->items()->first();

        $technician = User::factory()->create();
        $response = $this->actingAs($technician)->post(route('hms.lab.verifications.verify', $item));

        $response->assertSessionHasErrors('item');
    }
}
