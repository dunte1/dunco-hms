<?php

namespace Tests\Feature;

use App\Models\AccessLog;
use App\Models\BreakGlassEvent;
use App\Models\ComplianceChecklist;
use App\Models\ComplianceItem;
use App\Models\ComplianceResponse;
use App\Models\DataSubjectRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Tests\TestCase;

class G086AuditComplianceTest extends TestCase
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

    // ── Access Log ──────────────────────────────────────────────────────────────

    public function test_access_logs_table_can_be_created_and_queried(): void
    {
        AccessLog::create([
            'user_id' => $this->user->id,
            'action' => 'view',
            'auditable_type' => Patient::class,
            'auditable_id' => $this->patient->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent',
            'url' => '/patients/1',
            'method' => 'GET',
            'timestamp' => now(),
        ]);

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $this->user->id,
            'action' => 'view',
            'auditable_type' => Patient::class,
        ]);

        $log = AccessLog::first();
        $this->assertEquals($this->user->id, $log->user_id);
        $this->assertEquals('view', $log->action);
    }

    public function test_access_log_log_static_method(): void
    {
        $request = RequestFacade::create('/patients/1', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
        ]);

        $this->app['request'] = $request;

        $log = AccessLog::log(
            $this->user->id,
            'create',
            Patient::class,
            $this->patient->id,
            null,
            ['first_name' => 'John']
        );

        $this->assertDatabaseHas('access_logs', [
            'user_id' => $this->user->id,
            'action' => 'create',
            'auditable_type' => Patient::class,
            'auditable_id' => $this->patient->id,
        ]);

        $this->assertEquals('192.168.1.1', $log->ip_address);
        $this->assertEquals(['first_name' => 'John'], $log->new_values);
        $this->assertNull($log->old_values);
    }

    // ── Break Glass Event ──────────────────────────────────────────────────────

    public function test_break_glass_events_table_can_be_created_and_queried(): void
    {
        $event = BreakGlassEvent::create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'reason' => 'Emergency override for critical patient',
            'auditable_type' => Patient::class,
            'auditable_id' => $this->patient->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('break_glass_events', [
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        $this->assertTrue($event->isPending());
        $this->assertFalse($event->isApproved());
    }

    public function test_break_glass_event_workflow_pending_to_approved(): void
    {
        $reviewer = User::factory()->create();

        $event = BreakGlassEvent::create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'reason' => 'Urgent access needed',
            'auditable_type' => Patient::class,
            'auditable_id' => $this->patient->id,
            'status' => 'pending',
        ]);

        $this->assertTrue($event->isPending());

        $result = $event->approve($reviewer->id);

        $this->assertTrue($result);
        $this->assertTrue($event->fresh()->isApproved());
        $this->assertEquals($reviewer->id, $event->fresh()->reviewed_by);
        $this->assertNotNull($event->fresh()->reviewed_at);
    }

    public function test_break_glass_event_reject(): void
    {
        $reviewer = User::factory()->create();

        $event = BreakGlassEvent::create([
            'user_id' => $this->user->id,
            'patient_id' => $this->patient->id,
            'reason' => 'Suspicious access attempt',
            'auditable_type' => Patient::class,
            'auditable_id' => $this->patient->id,
            'status' => 'pending',
        ]);

        $result = $event->reject($reviewer->id);

        $this->assertTrue($result);
        $this->assertEquals('rejected', $event->fresh()->status);
        $this->assertEquals($reviewer->id, $event->fresh()->reviewed_by);
    }

    // ── Compliance Checklist ────────────────────────────────────────────────────

    public function test_compliance_checklists_table_can_be_created_and_queried(): void
    {
        ComplianceChecklist::create([
            'name' => 'Infection Control',
            'description' => 'Monthly infection control compliance',
            'frequency' => 'monthly',
            'category' => 'safety',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('compliance_checklists', [
            'name' => 'Infection Control',
            'frequency' => 'monthly',
        ]);

        $checklist = ComplianceChecklist::first();
        $this->assertTrue($checklist->is_active);
    }

    public function test_compliance_checklist_soft_deletes(): void
    {
        $checklist = ComplianceChecklist::create([
            'name' => 'Fire Safety',
            'frequency' => 'annual',
            'is_active' => true,
        ]);

        $id = $checklist->id;
        $checklist->delete();

        $this->assertSoftDeleted('compliance_checklists', ['id' => $id]);
        $this->assertNull(ComplianceChecklist::find($id));
        $this->assertNotNull(ComplianceChecklist::withTrashed()->find($id));
    }

    public function test_compliance_checklist_with_items(): void
    {
        $checklist = ComplianceChecklist::create([
            'name' => 'Hand Hygiene',
            'description' => 'Daily hand hygiene compliance',
            'frequency' => 'daily',
            'category' => 'infection_control',
            'is_active' => true,
        ]);

        $item1 = $checklist->items()->create([
            'title' => 'Wash hands before patient contact',
            'description' => 'Use soap and water for at least 20 seconds',
            'is_mandatory' => true,
            'sort_order' => 1,
        ]);

        $item2 = $checklist->items()->create([
            'title' => 'Use hand sanitizer between patients',
            'is_mandatory' => false,
            'sort_order' => 2,
        ]);

        $this->assertCount(2, $checklist->items);
        $this->assertEquals('Wash hands before patient contact', $checklist->items->first()->title);
        $this->assertTrue($item1->is_mandatory);
        $this->assertFalse($item2->is_mandatory);
    }

    public function test_compliance_item_responses(): void
    {
        $checklist = ComplianceChecklist::create([
            'name' => 'PPE Compliance',
            'frequency' => 'weekly',
            'is_active' => true,
        ]);

        $item = $checklist->items()->create([
            'title' => 'Masks available in ward',
            'is_mandatory' => true,
            'sort_order' => 1,
        ]);

        $response = ComplianceResponse::create([
            'item_id' => $item->id,
            'completed_by' => $this->user->id,
            'status' => 'compliant',
            'notes' => 'Masks stocked and accessible',
            'completed_at' => now(),
            'next_due_at' => now()->addWeek(),
        ]);

        $this->assertDatabaseHas('compliance_responses', [
            'item_id' => $item->id,
            'status' => 'compliant',
        ]);

        $this->assertTrue($response->isCompliant());
        $this->assertEquals($this->user->id, $response->completed_by);
    }

    // ── Data Subject Request ────────────────────────────────────────────────────

    public function test_data_subject_requests_table_can_be_created_and_queried(): void
    {
        DataSubjectRequest::create([
            'patient_id' => $this->patient->id,
            'request_type' => 'access',
            'status' => 'received',
            'requested_at' => now(),
            'deadline_at' => now()->addDays(30),
        ]);

        $this->assertDatabaseHas('data_subject_requests', [
            'patient_id' => $this->patient->id,
            'request_type' => 'access',
            'status' => 'received',
        ]);
    }

    public function test_data_subject_request_lifecycle(): void
    {
        $processor = User::factory()->create();

        $dsr = DataSubjectRequest::create([
            'patient_id' => $this->patient->id,
            'request_type' => 'erasure',
            'status' => 'received',
            'requested_at' => now(),
            'deadline_at' => now()->addDays(30),
        ]);

        $this->assertEquals('received', $dsr->status);

        $dsr->process($processor->id);
        $this->assertEquals('processing', $dsr->fresh()->status);
        $this->assertEquals($processor->id, $dsr->fresh()->processed_by);

        $dsr->complete($processor->id);
        $this->assertEquals('completed', $dsr->fresh()->status);
        $this->assertNotNull($dsr->fresh()->completed_at);
    }

    public function test_data_subject_request_reject(): void
    {
        $processor = User::factory()->create();

        $dsr = DataSubjectRequest::create([
            'patient_id' => $this->patient->id,
            'request_type' => 'objection',
            'status' => 'received',
            'requested_at' => now(),
        ]);

        $dsr->reject($processor->id, 'Request does not meet GDPR requirements');

        $this->assertEquals('rejected', $dsr->fresh()->status);
        $this->assertEquals('Request does not meet GDPR requirements', $dsr->fresh()->notes);
    }

    public function test_data_subject_request_is_overdue(): void
    {
        $dsr = DataSubjectRequest::create([
            'patient_id' => $this->patient->id,
            'request_type' => 'rectification',
            'status' => 'processing',
            'requested_at' => now()->subDays(40),
            'deadline_at' => now()->subDays(10),
        ]);

        $this->assertTrue($dsr->isOverdue());

        $dsr->complete($this->user->id);
        $this->assertFalse($dsr->fresh()->isOverdue());
    }
}
