<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\OpdVisit;
use App\Models\ClinicalNote;
use App\Models\ProcedureOrder;
use App\Models\SickNote;
use App\Models\MedicalCertificate;
use App\Models\NumberSequence;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G008ConsultationCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private OpdVisit $visit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'admit patients']);
        Permission::create(['name' => 'manage admissions']);
        Permission::create(['name' => 'view patients']);
        $this->user->givePermissionTo(['admit patients', 'manage admissions', 'view patients']);

        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->visit = OpdVisit::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'visit_date' => now(),
            'visit_type' => 'consultation',
            'status' => 'in_consultation',
        ]);
    }

    public function test_clinical_note_creation_with_note_types(): void
    {
        $types = ['history', 'exam', 'assessment', 'plan', 'procedure', 'progress'];

        foreach ($types as $type) {
            $response = $this->actingAs($this->user)->post(
                route('hms.consultations.clinical-notes.store', $this->visit),
                [
                    'doctor_id' => $this->doctor->id,
                    'note_type' => $type,
                    'content' => "Clinical note of type {$type}: Patient presents with fever and cough.",
                ]
            );

            $response->assertRedirect();
            $this->assertDatabaseHas('clinical_notes', [
                'patient_id' => $this->patient->id,
                'opd_visit_id' => $this->visit->id,
                'doctor_id' => $this->doctor->id,
                'note_type' => $type,
            ]);
        }
    }

    public function test_clinical_notes_index_linked_to_opd_visit(): void
    {
        ClinicalNote::create([
            'patient_id' => $this->patient->id,
            'opd_visit_id' => $this->visit->id,
            'doctor_id' => $this->doctor->id,
            'note_type' => 'assessment',
            'content' => 'Acute bronchitis suspected.',
        ]);

        $response = $this->actingAs($this->user)->get(
            route('hms.consultations.clinical-notes.index', $this->visit)
        );

        $response->assertOk();
        $response->assertViewHas('notes', function ($notes) {
            return $notes->contains('note_type', 'assessment');
        });
    }

    public function test_procedure_order_creation_and_completion_workflow(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('hms.consultations.procedure-orders.store', $this->visit),
            [
                'doctor_id' => $this->doctor->id,
                'procedure_name' => 'Chest X-Ray',
                'description' => 'PA view chest radiograph',
                'body_site' => 'Chest',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('procedure_orders', [
            'patient_id' => $this->patient->id,
            'opd_visit_id' => $this->visit->id,
            'procedure_name' => 'Chest X-Ray',
            'status' => 'ordered',
        ]);

        $order = ProcedureOrder::where('procedure_name', 'Chest X-Ray')->first();

        $response = $this->actingAs($this->user)->post(
            route('hms.consultations.procedure-orders.complete', $order),
            [
                'performed_by' => $this->user->id,
                'performed_at' => now()->toDateTimeString(),
                'outcome' => 'No abnormalities detected.',
                'complications' => null,
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('procedure_orders', [
            'id' => $order->id,
            'status' => 'completed',
            'performed_by' => $this->user->id,
            'outcome' => 'No abnormalities detected.',
        ]);
    }

    public function test_sick_note_issuance_with_date_range(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.sick-notes.store'), [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'opd_visit_id' => $this->visit->id,
            'start_date' => '2026-09-28',
            'end_date' => '2026-09-30',
            'diagnosis' => 'Acute gastroenteritis',
            'restrictions' => 'No heavy lifting for 3 days.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sick_notes', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'opd_visit_id' => $this->visit->id,
            'days_off' => 3,
            'diagnosis' => 'Acute gastroenteritis',
        ]);
        $this->assertNotNull(SickNote::where('patient_id', $this->patient->id)->first()->sick_note_number);
    }

    public function test_medical_certificate_issuance_with_auto_numbering(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.medical-certificates.store'), [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'certificate_type' => 'fitness',
            'findings' => 'Patient is medically fit.',
            'recommendations' => 'No restrictions.',
            'valid_until' => now()->addYear()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('medical_certificates', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'certificate_type' => 'fitness',
        ]);
        $cert = MedicalCertificate::where('patient_id', $this->patient->id)->first();
        $this->assertNotNull($cert->certificate_number);
        $this->assertStringStartsWith('MED', $cert->certificate_number);
    }

    public function test_clinical_notes_linked_to_opd_visit(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('hms.consultations.clinical-notes.store', $this->visit),
            [
                'doctor_id' => $this->doctor->id,
                'note_type' => 'progress',
                'content' => 'Patient responding well to treatment.',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('clinical_notes', [
            'opd_visit_id' => $this->visit->id,
            'patient_id' => $this->patient->id,
        ]);

        $note = ClinicalNote::where('opd_visit_id', $this->visit->id)->first();
        $this->assertEquals($this->visit->id, $note->opd_visit_id);
    }
}
