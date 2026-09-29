<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Ward;
use App\Models\Doctor;
use App\Models\OutbreakEvent;
use App\Models\HaiSurveillanceRecord;
use App\Models\IsolationOrder;
use App\Models\HandHygieneObservation;
use App\Models\IpcAudit;
use App\Models\OutbreakInvestigation;
use App\Models\AntibioticUsageRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G049IpcTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Ward $ward;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->ward = Ward::create([
            'name' => 'Ward A',
            'code' => 'WA-01',
            'ward_type' => 'general',
            'capacity' => 30,
        ]);
        $this->doctor = Doctor::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_hai_surveillance_record_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipc.hai-surveillance.store'), [
            'patient_id' => $this->patient->id,
            'infection_type' => 'surgical_site',
            'organism' => 'Staphylococcus aureus',
            'ward_id' => $this->ward->id,
            'onset_date' => now()->toDateString(),
            'reported_date' => now()->toDateString(),
            'status' => 'suspected',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hai_surveillance_records', [
            'patient_id' => $this->patient->id,
            'infection_type' => 'surgical_site',
            'organism' => 'Staphylococcus aureus',
            'ward_id' => $this->ward->id,
            'reported_by' => $this->user->id,
            'status' => 'suspected',
        ]);
    }

    public function test_hai_surveillance_index(): void
    {
        HaiSurveillanceRecord::create([
            'patient_id' => $this->patient->id,
            'infection_type' => 'uti',
            'onset_date' => now(),
            'reported_date' => now(),
            'reported_by' => $this->user->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.ipc.hai-surveillance.index'));

        $response->assertOk();
    }

    public function test_isolation_order_workflow(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipc.isolation.store'), [
            'patient_id' => $this->patient->id,
            'ward_id' => $this->ward->id,
            'isolation_type' => 'airborne',
            'reason' => 'Confirmed TB',
            'ordered_by' => $this->doctor->id,
            'start_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('isolation_orders', [
            'patient_id' => $this->patient->id,
            'ward_id' => $this->ward->id,
            'isolation_type' => 'airborne',
            'ordered_by' => $this->doctor->id,
            'status' => 'active',
        ]);

        $isolation = IsolationOrder::latest()->first();

        $response = $this->actingAs($this->user)->post(
            route('hms.ipc.isolation.discharge', $isolation),
            ['end_date' => now()->toDateString()]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('isolation_orders', [
            'id' => $isolation->id,
            'status' => 'completed',
        ]);
        $this->assertNotNull($isolation->fresh()->end_date);
    }

    public function test_hand_hygiene_observation_with_compliance_rate(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipc.hand-hygiene.store'), [
            'ward_id' => $this->ward->id,
            'observation_date' => now()->toDateString(),
            'opportunities_observed' => 20,
            'hand_washes' => 18,
            'technique_score' => 'good',
            'notes' => 'Staff generally compliant',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hand_hygiene_observations', [
            'observer_id' => $this->user->id,
            'ward_id' => $this->ward->id,
            'opportunities_observed' => 20,
            'hand_washes' => 18,
            'compliance_rate' => 90.00,
            'technique_score' => 'good',
        ]);
    }

    public function test_ipc_audit_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipc.audits.store'), [
            'audit_type' => 'hand_hygiene',
            'ward_id' => $this->ward->id,
            'audit_date' => now()->toDateString(),
            'score' => 85.50,
            'findings' => 'Good compliance in most areas',
            'corrective_actions' => 'Increase monitoring in ICU',
            'next_audit_date' => now()->addMonth()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ipc_audits', [
            'audit_type' => 'hand_hygiene',
            'ward_id' => $this->ward->id,
            'score' => 85.50,
            'auditor_id' => $this->user->id,
            'findings' => 'Good compliance in most areas',
        ]);
    }

    public function test_ipc_audit_index(): void
    {
        IpcAudit::create([
            'audit_type' => 'isolation',
            'ward_id' => $this->ward->id,
            'audit_date' => now(),
            'score' => 92.00,
            'findings' => 'Isolation protocols followed',
            'auditor_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.ipc.audits.index'));

        $response->assertOk();
    }

    public function test_outbreak_investigation(): void
    {
        $outbreak = OutbreakEvent::create([
            'disease_name' => 'Cholera',
            'start_date' => now(),
            'status' => 'suspected',
            'declared_by' => $this->user->id,
            'declared_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ipc.outbreak-investigations.store'), [
            'outbreak_event_id' => $outbreak->id,
            'disease_name' => 'Cholera',
            'investigation_start_date' => now()->toDateString(),
            'source_identified' => true,
            'source_description' => 'Contaminated water supply',
            'control_measures' => 'Water treatment and isolation of cases',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_investigations', [
            'outbreak_event_id' => $outbreak->id,
            'disease_name' => 'Cholera',
            'source_identified' => true,
            'investigated_by' => $this->user->id,
            'status' => 'active',
        ]);

        $investigation = OutbreakInvestigation::latest()->first();

        $response = $this->actingAs($this->user)->put(
            route('hms.ipc.outbreak-investigations.update', $investigation),
            [
                'status' => 'contained',
                'investigation_end_date' => now()->toDateString(),
                'control_measures' => 'Water treatment completed, all cases resolved',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('outbreak_investigations', [
            'id' => $investigation->id,
            'status' => 'contained',
        ]);
        $this->assertNotNull($investigation->fresh()->investigation_end_date);
    }

    public function test_antibiotic_usage_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipc.antibiotic-usage.store'), [
            'patient_id' => $this->patient->id,
            'antibiotic_name' => 'Amoxicillin',
            'indication' => 'Community-acquired pneumonia',
            'start_date' => now()->toDateString(),
            'ddd' => 1.5,
            'route' => 'oral',
            'ward_id' => $this->ward->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('antibiotic_usage_records', [
            'patient_id' => $this->patient->id,
            'antibiotic_name' => 'Amoxicillin',
            'indication' => 'Community-acquired pneumonia',
            'ddd' => 1.5,
            'route' => 'oral',
            'ward_id' => $this->ward->id,
            'prescriber_id' => $this->user->id,
        ]);
    }
}
