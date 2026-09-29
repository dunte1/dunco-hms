<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MortuaryRecord;
use App\Models\MortuarySlotAssignment;
use App\Models\BodyIdentification;
use App\Models\Postmortem;
use App\Models\DeathCertificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G043MortuaryCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private MortuaryRecord $record;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->record = MortuaryRecord::create([
            'body_id' => 'BDY-TEST-001',
            'received_at' => now(),
            'received_by' => $this->user->id,
            'status' => 'stored',
        ]);
    }

    // ── Slot Assignment & Release ──────────────────────────────

    public function test_slot_assignment(): void
    {
        $response = $this->actingAs($this->user)->post(route('mortuary.slots.assign', $this->record), [
            'slot_number' => 12,
            'notes' => 'Body placed in cold storage slot 12',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mortuary_slot_assignments', [
            'mortuary_record_id' => $this->record->id,
            'slot_number' => 12,
        ]);
        $this->assertDatabaseHas('mortuary_records', [
            'id' => $this->record->id,
            'slot_number' => 12,
        ]);
    }

    public function test_slot_release(): void
    {
        $slot = MortuarySlotAssignment::create([
            'mortuary_record_id' => $this->record->id,
            'slot_number' => 12,
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->post(route('mortuary.slots.release', $slot));

        $response->assertRedirect();
        $this->assertDatabaseHas('mortuary_slot_assignments', [
            'id' => $slot->id,
        ]);
        $this->assertNotNull($slot->fresh()->removed_at);
        $this->assertDatabaseHas('mortuary_records', [
            'id' => $this->record->id,
            'slot_number' => null,
        ]);
    }

    // ── Body Identification ────────────────────────────────────

    public function test_body_identification(): void
    {
        $response = $this->actingAs($this->user)->post(route('mortuary.identification.store', $this->record), [
            'identifier_name' => 'Jane Doe',
            'identifier_relationship' => 'Wife',
            'identification_method' => 'visual',
            'notes' => 'Identified by tattoo on left arm',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('body_identifications', [
            'mortuary_record_id' => $this->record->id,
            'identifier_name' => 'Jane Doe',
            'identification_method' => 'visual',
            'identified_by' => $this->user->id,
        ]);
    }

    public function test_body_identification_with_dna(): void
    {
        $response = $this->actingAs($this->user)->post(route('mortuary.identification.store', $this->record), [
            'identifier_name' => 'John Doe',
            'identifier_relationship' => 'Son',
            'identification_method' => 'dna',
            'notes' => 'DNA sample matched',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('body_identifications', [
            'identification_method' => 'dna',
        ]);
    }

    // ── Postmortem ─────────────────────────────────────────────

    public function test_postmortem_request(): void
    {
        $response = $this->actingAs($this->user)->post(route('mortuary.postmortem.store', $this->record), [
            'patient_id' => $this->patient->id,
            'cause_of_death' => 'Cardiac arrest',
            'manner_of_death' => 'natural',
            'performed_by' => $this->doctor->id,
            'requested_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('postmortems', [
            'mortuary_record_id' => $this->record->id,
            'patient_id' => $this->patient->id,
            'cause_of_death' => 'Cardiac arrest',
            'manner_of_death' => 'natural',
            'performed_by' => $this->doctor->id,
            'status' => 'requested',
        ]);
    }

    public function test_postmortem_completion(): void
    {
        $postmortem = Postmortem::create([
            'mortuary_record_id' => $this->record->id,
            'patient_id' => $this->patient->id,
            'cause_of_death' => 'Head trauma',
            'manner_of_death' => 'accident',
            'performed_by' => $this->doctor->id,
            'requested_date' => now()->toDateString(),
            'status' => 'requested',
        ]);

        $response = $this->actingAs($this->user)->post(route('mortuary.postmortem.complete', $postmortem), [
            'findings' => 'Severe cranial fracture. Cause of death confirmed as traumatic brain injury.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('postmortems', [
            'id' => $postmortem->id,
            'status' => 'completed',
            'findings' => 'Severe cranial fracture. Cause of death confirmed as traumatic brain injury.',
        ]);
        $this->assertNotNull($postmortem->fresh()->completed_date);
    }

    // ── Death Certificate ──────────────────────────────────────

    public function test_death_certificate_issuance(): void
    {
        $response = $this->actingAs($this->user)->post(route('mortuary.death-certificate.store', $this->record), [
            'patient_id' => $this->patient->id,
            'cause_of_death_primary' => 'Cardiac arrest',
            'cause_of_death_secondary' => 'History of hypertension',
            'contributing_conditions' => 'Chronic kidney disease, Diabetes mellitus',
            'issued_by' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('death_certificates', [
            'mortuary_record_id' => $this->record->id,
            'patient_id' => $this->patient->id,
            'cause_of_death_primary' => 'Cardiac arrest',
            'cause_of_death_secondary' => 'History of hypertension',
            'issued_by' => $this->doctor->id,
        ]);
    }

    public function test_death_certificate_unique_number(): void
    {
        $this->actingAs($this->user)->post(route('mortuary.death-certificate.store', $this->record), [
            'patient_id' => $this->patient->id,
            'cause_of_death_primary' => 'Stroke',
            'issued_by' => $this->doctor->id,
        ]);

        $this->actingAs($this->user)->post(route('mortuary.death-certificate.store', $this->record), [
            'patient_id' => $this->patient->id,
            'cause_of_death_primary' => 'Respiratory failure',
            'issued_by' => $this->doctor->id,
        ]);

        $certificates = DeathCertificate::all();
        $this->assertCount(2, $certificates);
        $this->assertNotEquals(
            $certificates[0]->certificate_number,
            $certificates[1]->certificate_number
        );
    }
}
