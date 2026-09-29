<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\RadiologyTest;
use App\Models\RadiologyCategory;
use App\Models\RadiologyRequest;
use App\Models\ImagingSchedule;
use App\Models\ModalityWorklistItem;
use App\Models\ImagingReportVersion;
use App\Models\ContrastRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G028RadiologyCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private RadiologyRequest $radiologyRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();

        $category = RadiologyCategory::create(['name' => 'Diagnostic Imaging']);
        $test = RadiologyTest::create([
            'test_name' => 'Chest X-Ray',
            'category_id' => $category->id,
            'price' => 2000,
            'is_active' => true,
        ]);

        $this->radiologyRequest = RadiologyRequest::create([
            'request_number' => 'RAD-2026-000001',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'radiology_test_id' => $test->id,
            'request_date' => now()->toDateString(),
            'clinical_notes' => 'Persistent cough for 2 weeks',
            'urgency' => 'routine',
            'status' => 'pending',
        ]);
    }

    public function test_imaging_schedule_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.radiology.schedules.store'), [
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'radiology_test_id' => $this->radiologyRequest->radiology_test_id,
            'modality' => 'xray',
            'scheduled_date' => now()->addDay()->toDateString(),
            'scheduled_time' => '09:30',
            'notes' => 'Patient fasting not required',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('imaging_schedules', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'modality' => 'xray',
            'status' => 'scheduled',
            'scheduled_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('radiology_requests', [
            'id' => $this->radiologyRequest->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_imaging_schedule_cancellation(): void
    {
        $schedule = ImagingSchedule::create([
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'radiology_test_id' => $this->radiologyRequest->radiology_test_id,
            'modality' => 'ct',
            'scheduled_date' => now()->addDay()->toDateString(),
            'scheduled_time' => '14:00',
            'status' => 'scheduled',
            'scheduled_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.radiology.schedules.cancel', $schedule));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $schedule->refresh();
        $this->assertEquals('cancelled', $schedule->status);
    }

    public function test_modality_worklist_creation(): void
    {
        $item = ModalityWorklistItem::create([
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'modality' => 'xray',
            'priority' => 'urgent',
            'status' => 'queued',
            'body_part' => 'Chest PA',
            'clinical_history' => 'Persistent cough for 2 weeks',
            'scheduled_time' => '09:30',
        ]);

        $this->assertDatabaseHas('modality_worklist_items', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'modality' => 'xray',
            'priority' => 'urgent',
            'status' => 'queued',
        ]);
    }

    public function test_worklist_claiming(): void
    {
        $item = ModalityWorklistItem::create([
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'modality' => 'xray',
            'priority' => 'routine',
            'status' => 'queued',
            'body_part' => 'Chest PA',
            'scheduled_time' => '10:00',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.radiology.worklist.claim', $item));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals('in_progress', $item->status);
        $this->assertNotNull($item->started_at);
    }

    public function test_worklist_completion(): void
    {
        $item = ModalityWorklistItem::create([
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'modality' => 'ultrasound',
            'priority' => 'routine',
            'status' => 'in_progress',
            'body_part' => 'Abdomen',
            'started_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.radiology.worklist.complete', $item));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals('completed', $item->status);
        $this->assertNotNull($item->completed_at);
    }

    public function test_report_versioning_draft_to_final(): void
    {
        // Create draft report v1
        $response = $this->actingAs($this->user)->post(route('hms.radiology.report.store', $this->radiologyRequest), [
            'findings' => 'Normal chest radiograph. No consolidation.',
            'impression' => 'Normal chest X-ray.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('imaging_report_versions', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'version_number' => 1,
            'status' => 'draft',
        ]);

        // Approve v1 → final
        $response = $this->actingAs($this->user)->post(route('hms.radiology.report.approve', $this->radiologyRequest), [
            'version_number' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('imaging_report_versions', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'version_number' => 1,
            'status' => 'final',
        ]);

        $this->assertDatabaseHas('radiology_requests', [
            'id' => $this->radiologyRequest->id,
            'findings' => 'Normal chest radiograph. No consolidation.',
            'status' => 'completed',
        ]);
    }

    public function test_report_amendment_creates_new_version(): void
    {
        // Create and finalize v1
        ImagingReportVersion::create([
            'radiology_request_id' => $this->radiologyRequest->id,
            'version_number' => 1,
            'findings' => 'Initial findings.',
            'impression' => 'Initial impression.',
            'created_by' => $this->user->id,
            'status' => 'final',
            'created_at' => now(),
        ]);

        // Amend → creates v2 as draft, v1 becomes amended
        $response = $this->actingAs($this->user)->post(route('hms.radiology.report.amend', $this->radiologyRequest), [
            'findings' => 'Amended findings with additional detail.',
            'impression' => 'Updated impression after review.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('imaging_report_versions', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'version_number' => 1,
            'status' => 'amended',
        ]);

        $this->assertDatabaseHas('imaging_report_versions', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'version_number' => 2,
            'status' => 'draft',
            'findings' => 'Amended findings with additional detail.',
        ]);
    }

    public function test_contrast_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.radiology.contrast.store', $this->radiologyRequest), [
            'patient_id' => $this->patient->id,
            'contrast_type' => 'iodine',
            'contrast_agent' => 'Omnipaque 350',
            'volume_ml' => 100.00,
            'route' => 'iv',
            'reaction_notes' => null,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contrast_records', [
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'contrast_type' => 'iodine',
            'contrast_agent' => 'Omnipaque 350',
            'volume_ml' => 100.00,
            'route' => 'iv',
            'administered_by' => $this->user->id,
        ]);
    }

    public function test_schedule_validation_requires_valid_modality(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.radiology.schedules.store'), [
            'radiology_request_id' => $this->radiologyRequest->id,
            'patient_id' => $this->patient->id,
            'radiology_test_id' => $this->radiologyRequest->radiology_test_id,
            'modality' => 'invalid_modality',
            'scheduled_date' => now()->addDay()->toDateString(),
            'scheduled_time' => '09:30',
        ]);

        $response->assertSessionHasErrors('modality');
    }
}
