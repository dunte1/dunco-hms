<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\CorrectiveAction;
use App\Models\DeathReport;
use App\Models\Incident;
use App\Models\IndicatorValue;
use App\Models\MortalityReview;
use App\Models\Patient;
use App\Models\QualityIndicator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G085QualityAssuranceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    // ── Complaints ────────────────────────────────────────────────────────────

    public function test_complaint_creation(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->post('/hms/quality/complaints', [
            'patient_id' => $patient->id,
            'complaint_type' => 'service_quality',
            'description' => 'Long wait time in outpatient',
            'priority' => 'medium',
            'department' => 'Outpatient',
            'complainant_name' => 'Jane Doe',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'patient_id' => $patient->id,
            'complaint_type' => 'service_quality',
            'status' => 'received',
            'received_by' => $this->user->id,
        ]);
    }

    public function test_complaint_workflow_received_to_resolved(): void
    {
        $complaint = Complaint::create([
            'complaint_number' => 'CMP999001',
            'patient_id' => Patient::factory()->create()->id,
            'complaint_type' => 'billing',
            'description' => 'Overcharged for lab test',
            'status' => 'received',
            'priority' => 'high',
            'received_by' => $this->user->id,
            'received_at' => now(),
        ]);

        $this->assertEquals('received', $complaint->status);

        $complaint->acknowledge();
        $this->assertEquals('acknowledged', $complaint->fresh()->status);
        $this->assertNotNull($complaint->fresh()->acknowledged_at);

        $complaint->resolve('Refund processed', 4);
        $this->assertEquals('resolved', $complaint->fresh()->status);
        $this->assertEquals('Refund processed', $complaint->fresh()->resolution);
        $this->assertEquals(4, $complaint->fresh()->satisfaction_score);
        $this->assertNotNull($complaint->fresh()->resolved_at);
    }

    public function test_complaint_resolve_via_route(): void
    {
        $complaint = Complaint::create([
            'complaint_number' => 'CMP999002',
            'patient_id' => Patient::factory()->create()->id,
            'complaint_type' => 'other',
            'description' => 'Noise complaint',
            'status' => 'investigating',
            'priority' => 'low',
            'received_by' => $this->user->id,
            'received_at' => now(),
        ]);

        $response = $this->post("/hms/quality/complaints/{$complaint->id}/resolve", [
            'resolution' => 'Staff warned',
            'satisfaction_score' => 3,
        ]);

        $response->assertRedirect();
        $this->assertEquals('resolved', $complaint->fresh()->status);
        $this->assertEquals('Staff warned', $complaint->fresh()->resolution);
    }

    // ── Incidents ─────────────────────────────────────────────────────────────

    public function test_incident_reporting(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->post('/hms/quality/incidents', [
            'incident_type' => 'patient_fall',
            'severity' => 'high',
            'description' => 'Patient fell from bed in Ward 3',
            'location' => 'Ward 3, Bed 12',
            'department' => 'Nursing',
            'patient_id' => $patient->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('incidents', [
            'incident_type' => 'patient_fall',
            'severity' => 'high',
            'status' => 'reported',
            'reported_by' => $this->user->id,
        ]);
    }

    public function test_incident_investigation(): void
    {
        $incident = Incident::create([
            'incident_number' => 'INC999001',
            'incident_type' => 'medication_error',
            'severity' => 'critical',
            'description' => 'Wrong medication administered',
            'location' => 'ICU',
            'reported_by' => $this->user->id,
            'reported_at' => now(),
            'status' => 'reported',
        ]);

        $incident->investigate();
        $this->assertEquals('investigating', $incident->fresh()->status);

        $resolver = User::factory()->create();
        $incident->resolve($resolver->id, 'Nurse fatigue', 'Implement double-check protocol');
        $this->assertEquals('resolved', $incident->fresh()->status);
        $this->assertEquals('Nurse fatigue', $incident->fresh()->root_cause);
        $this->assertNotNull($incident->fresh()->resolved_at);
    }

    // ── Mortality Reviews ────────────────────────────────────────────────────

    public function test_mortality_review_creation(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->post('/hms/quality/mortality-reviews', [
            'patient_id' => $patient->id,
            'review_date' => now()->toDateString(),
            'review_type' => 'peer_review',
            'diagnosis' => 'Sepsis',
            'contributing_factors' => 'Delayed antibiotics',
            'preventability' => 'potentially_preventable',
            'recommendations' => 'Improve sepsis screening protocol',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mortality_reviews', [
            'patient_id' => $patient->id,
            'review_type' => 'peer_review',
            'preventability' => 'potentially_preventable',
            'status' => 'pending',
            'reviewed_by' => $this->user->id,
        ]);
    }

    // ── Quality Indicators ───────────────────────────────────────────────────

    public function test_quality_indicator_creation(): void
    {
        $response = $this->post('/hms/quality/indicators', [
            'name' => 'Hand Hygiene Compliance',
            'code' => 'QI-HH-001',
            'description' => 'Percentage of staff compliant with hand hygiene',
            'formula' => '(compliant observations / total observations) * 100',
            'target_value' => 95.00,
            'unit' => '%',
            'category' => 'clinical',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quality_indicators', [
            'code' => 'QI-HH-001',
            'category' => 'clinical',
            'is_active' => true,
        ]);
    }

    public function test_indicator_value_recording_with_auto_status(): void
    {
        $indicator = QualityIndicator::create([
            'name' => 'Bed Occupancy Rate',
            'code' => 'QI-OPS-001',
            'description' => 'Bed occupancy rate',
            'target_value' => 80.00,
            'unit' => '%',
            'category' => 'operational',
        ]);

        $response = $this->post("/hms/quality/indicators/{$indicator->id}/values", [
            'period_month' => 9,
            'period_year' => 2026,
            'numerator' => 85,
            'denominator' => 100,
            'notes' => 'September data',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('indicator_values', [
            'indicator_id' => $indicator->id,
            'period_month' => 9,
            'period_year' => 2026,
            'status' => 'met',
            'recorded_by' => $this->user->id,
        ]);

        $value = IndicatorValue::where('indicator_id', $indicator->id)->first();
        $this->assertEquals(85.0, $value->actual_value);
    }

    // ── Corrective Actions ──────────────────────────────────────────────────

    public function test_corrective_action_lifecycle(): void
    {
        $response = $this->post('/hms/quality/corrective-actions', [
            'source_type' => 'incident',
            'source_id' => 1,
            'action_description' => 'Implement fall prevention protocol',
            'responsible_person' => $this->user->id,
            'due_date' => now()->addDays(30)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('corrective_actions', [
            'source_type' => 'incident',
            'status' => 'pending',
            'responsible_person' => $this->user->id,
        ]);

        $action = CorrectiveAction::first();
        $action->complete('evidence/protocol.pdf');
        $this->assertEquals('completed', $action->fresh()->status);
        $this->assertNotNull($action->fresh()->completion_date);
        $this->assertEquals('evidence/protocol.pdf', $action->fresh()->evidence_path);
    }

    public function test_corrective_action_complete_via_route(): void
    {
        $action = CorrectiveAction::create([
            'source_type' => 'complaint',
            'source_id' => 1,
            'action_description' => 'Retrain staff on billing',
            'responsible_person' => $this->user->id,
            'due_date' => now()->addDays(14)->toDateString(),
            'status' => 'in_progress',
        ]);

        $response = $this->post("/hms/quality/corrective-actions/{$action->id}/complete", [
            'evidence_path' => 'evidence/training_cert.pdf',
        ]);

        $response->assertRedirect();
        $this->assertEquals('completed', $action->fresh()->status);
        $this->assertNotNull($action->fresh()->completion_date);
    }
}
