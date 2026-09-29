<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Triage;
use App\Models\TriageCategory;
use App\Models\TriageEscalation;
use App\Models\OpdVisit;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G005TriageCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'manage patient vitals']);
        Permission::create(['name' => 'view patients']);
        $this->user->givePermissionTo(['manage patient vitals', 'view patients']);

        $this->patient = Patient::factory()->create();
    }

    public function test_triage_category_crud(): void
    {
        // Create
        $category = TriageCategory::create([
            'name' => 'Respiratory Distress',
            'code' => 'RESP-01',
            'color' => '#ff0000',
            'description' => 'Patients with breathing difficulties',
            'priority_level' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('triage_categories', [
            'code' => 'RESP-01',
            'name' => 'Respiratory Distress',
        ]);

        // Update
        $category->update(['name' => 'Severe Respiratory Distress', 'color' => '#cc0000']);
        $this->assertEquals('Severe Respiratory Distress', $category->fresh()->name);

        // Soft delete
        $category->delete();
        $this->assertSoftDeleted('triage_categories', ['id' => $category->id]);
    }

    public function test_triage_category_unique_code(): void
    {
        TriageCategory::create(['name' => 'Cat A', 'code' => 'CAT-A', 'priority_level' => 1]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        TriageCategory::create(['name' => 'Cat B', 'code' => 'CAT-A', 'priority_level' => 2]);
    }

    public function test_triage_creation_with_pain_score_gcs_pregnancy(): void
    {
        $category = TriageCategory::create([
            'name' => 'Emergency',
            'code' => 'EMG-01',
            'priority_level' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.triage.store'), [
            'patient_id' => $this->patient->id,
            'priority_level' => 'emergency',
            'category_id' => $category->id,
            'pain_score' => 8,
            'gcs_score' => 12,
            'pregnancy_status' => 'yes',
            'temperature' => 38.5,
            'pulse_rate' => 110,
            'systolic_bp' => 90,
            'diastolic_bp' => 60,
            'respiratory_rate' => 24,
            'oxygen_saturation' => 92.0,
            'blood_glucose' => 45,
            'chief_complaint' => 'Severe chest pain and shortness of breath',
            'triage_notes' => 'Patient appears distressed, diaphoretic',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('triages', [
            'patient_id' => $this->patient->id,
            'priority_level' => 'emergency',
            'category_id' => $category->id,
            'pain_score' => 8,
            'gcs_score' => 12,
            'pregnancy_status' => 'yes',
            'triaged_by' => $this->user->id,
        ]);

        $triage = Triage::where('patient_id', $this->patient->id)->first();
        $this->assertNotNull($triage->triage_number);
        $this->assertStringStartsWith('TRI-' . date('Y'), $triage->triage_number);
    }

    public function test_triage_pain_score_validation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.triage.store'), [
            'patient_id' => $this->patient->id,
            'priority_level' => 'urgent',
            'pain_score' => 11,
        ]);

        $response->assertSessionHasErrors('pain_score');
    }

    public function test_triage_gcs_score_validation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.triage.store'), [
            'patient_id' => $this->patient->id,
            'priority_level' => 'urgent',
            'gcs_score' => 2,
        ]);

        $response->assertSessionHasErrors('gcs_score');

        $response = $this->actingAs($this->user)->post(route('hms.triage.store'), [
            'patient_id' => $this->patient->id,
            'priority_level' => 'urgent',
            'gcs_score' => 16,
        ]);

        $response->assertSessionHasErrors('gcs_score');
    }

    public function test_escalation_workflow_create_acknowledge_resolve(): void
    {
        $triage = Triage::create([
            'patient_id' => $this->patient->id,
            'triage_number' => 'TRI-2026-000001',
            'priority_level' => 'emergency',
            'pain_score' => 9,
            'gcs_score' => 10,
            'pregnancy_status' => 'no',
            'triaged_by' => $this->user->id,
            'triaged_at' => now(),
        ]);

        $targetUser = User::factory()->create();
        $targetUser->givePermissionTo(['manage patient vitals', 'view patients']);

        // Create escalation
        $response = $this->actingAs($this->user)->post(route('hms.triage.escalations.store', $triage), [
            'reason' => 'Patient condition deteriorating rapidly',
            'severity' => 'critical',
            'escalated_to' => $targetUser->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('triage_escalations', [
            'triage_id' => $triage->id,
            'patient_id' => $this->patient->id,
            'escalated_by' => $this->user->id,
            'escalated_to' => $targetUser->id,
            'reason' => 'Patient condition deteriorating rapidly',
            'severity' => 'critical',
            'status' => 'pending',
        ]);

        $escalation = TriageEscalation::where('triage_id', $triage->id)->first();

        // Acknowledge
        $response = $this->actingAs($targetUser)->post(route('hms.triage.escalations.acknowledge', $escalation));
        $response->assertRedirect();

        $escalation->refresh();
        $this->assertEquals('acknowledged', $escalation->status);
        $this->assertNotNull($escalation->acknowledged_at);

        // Resolve
        $response = $this->actingAs($targetUser)->post(route('hms.triage.escalations.resolve', $escalation), [
            'resolution_notes' => 'Patient stabilized with IV fluids and medication',
        ]);

        $response->assertRedirect();
        $escalation->refresh();
        $this->assertEquals('resolved', $escalation->status);
        $this->assertNotNull($escalation->resolved_at);
        $this->assertEquals('Patient stabilized with IV fluids and medication', $escalation->resolution_notes);
    }

    public function test_escalation_status_filtering(): void
    {
        $triage = Triage::create([
            'patient_id' => $this->patient->id,
            'triage_number' => 'TRI-2026-000002',
            'priority_level' => 'urgent',
            'triaged_by' => $this->user->id,
            'triaged_at' => now(),
        ]);

        TriageEscalation::create([
            'triage_id' => $triage->id,
            'patient_id' => $this->patient->id,
            'escalated_by' => $this->user->id,
            'reason' => 'Issue 1',
            'severity' => 'moderate',
            'status' => 'pending',
        ]);

        TriageEscalation::create([
            'triage_id' => $triage->id,
            'patient_id' => $this->patient->id,
            'escalated_by' => $this->user->id,
            'reason' => 'Issue 2',
            'severity' => 'severe',
            'status' => 'resolved',
        ]);

        // Filter by pending
        $response = $this->actingAs($this->user)->get(route('hms.triage.escalations.index', ['status' => 'pending']));
        $response->assertOk();
        $esc = $response->viewData('escalations');
        $this->assertEquals(1, $esc->total());

        // Filter by resolved
        $response = $this->actingAs($this->user)->get(route('hms.triage.escalations.index', ['status' => 'resolved']));
        $response->assertOk();
        $esc = $response->viewData('escalations');
        $this->assertEquals(1, $esc->total());

        // No filter = all
        $response = $this->actingAs($this->user)->get(route('hms.triage.escalations.index'));
        $response->assertOk();
        $esc = $response->viewData('escalations');
        $this->assertEquals(2, $esc->total());
    }

    public function test_pregnancy_status_default_is_unknown(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.triage.store'), [
            'patient_id' => $this->patient->id,
            'priority_level' => 'non_urgent',
        ]);

        $response->assertRedirect();

        $triage = Triage::where('patient_id', $this->patient->id)->first();
        $this->assertEquals('unknown', $triage->pregnancy_status);
    }
}
