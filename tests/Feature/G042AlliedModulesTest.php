<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\DentalRecord;
use App\Models\EyeExaminationRecord;
use App\Models\EntRecord;
use App\Models\RehabSessionRecord;
use App\Models\NutritionRecord;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G042AlliedModulesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'manage patients']);
        $this->user->givePermissionTo('manage patients');

        $this->patient = Patient::factory()->create();
    }

    // ── M22 Dental ──────────────────────────────────────────────

    public function test_dental_record_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.dental.records.store'), [
            'patient_id' => $this->patient->id,
            'tooth_number' => '16',
            'procedure_type' => 'extraction',
            'diagnosis' => 'Impacted third molar',
            'treatment_notes' => 'Simple extraction under local anaesthesia. Tooth extracted atraumatically.',
            'cost' => 1500.00,
            'status' => 'completed',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('dental_records', [
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'procedure_type' => 'extraction',
            'status' => 'completed',
        ]);
    }

    public function test_dental_record_index(): void
    {
        DentalRecord::create([
            'patient_id' => $this->patient->id,
            'dentist_id' => $this->user->id,
            'procedure_type' => 'filling',
            'treatment_notes' => 'Composite filling on tooth 36.',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('hms.dental.records.index'));
        $response->assertOk();
    }

    public function test_dental_record_validation_missing_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.dental.records.store'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['patient_id', 'procedure_type', 'treatment_notes']);
    }

    public function test_dental_record_patient_link(): void
    {
        $otherPatient = Patient::factory()->create();

        $this->actingAs($this->user)->postJson(route('hms.dental.records.store'), [
            'patient_id' => $otherPatient->id,
            'procedure_type' => 'scaling',
            'treatment_notes' => 'Full mouth scaling and polishing.',
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('dental_records', [
            'patient_id' => $otherPatient->id,
            'dentist_id' => $this->user->id,
        ]);
    }

    // ── M23 Ophthalmology ───────────────────────────────────────

    public function test_eye_exam_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.ophthalmology.exams.store'), [
            'patient_id' => $this->patient->id,
            'visual_acuity_right' => 6.0,
            'visual_acuity_left' => 5.5,
            'iop_right' => '15 mmHg',
            'iop_left' => '14 mmHg',
            'refraction_right' => 'Plano',
            'refraction_left' => '-1.00 DS',
            'diagnosis' => 'Mild myopia left eye',
            'treatment' => 'Prescribe corrective lenses',
            'glasses_prescribed' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('eye_examination_records', [
            'patient_id' => $this->patient->id,
            'examiner_id' => $this->user->id,
            'glasses_prescribed' => true,
        ]);
    }

    public function test_eye_exam_index(): void
    {
        EyeExaminationRecord::create([
            'patient_id' => $this->patient->id,
            'examiner_id' => $this->user->id,
            'diagnosis' => 'Normal eye exam',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('hms.ophthalmology.exams.index'));
        $response->assertOk();
    }

    public function test_eye_exam_validation_missing_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.ophthalmology.exams.store'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['patient_id', 'diagnosis']);
    }

    public function test_eye_exam_patient_link(): void
    {
        $otherPatient = Patient::factory()->create();

        $this->actingAs($this->user)->postJson(route('hms.ophthalmology.exams.store'), [
            'patient_id' => $otherPatient->id,
            'diagnosis' => 'Glaucoma suspected',
            'iop_right' => '22 mmHg',
        ]);

        $this->assertDatabaseHas('eye_examination_records', [
            'patient_id' => $otherPatient->id,
            'examiner_id' => $this->user->id,
        ]);
    }

    // ── M24 ENT ─────────────────────────────────────────────────

    public function test_ent_record_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.ent.records.store'), [
            'patient_id' => $this->patient->id,
            'ear_findings' => 'Right ear: wax impaction. Left ear: normal tympanic membrane.',
            'nose_findings' => 'Nasal septum deviated to the left. Turbinates hypertrophied.',
            'throat_findings' => 'Tonsils enlarged grade 2 bilaterally. Oropharynx erythematous.',
            'hearing_test' => 'Right ear mild conductive hearing loss. Left ear normal.',
            'endoscopy_findings' => null,
            'diagnosis' => 'Chronic tonsillitis, deviated nasal septum',
            'treatment' => 'Tonsillectomy recommended. Nasal steroid spray prescribed.',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('ent_records', [
            'patient_id' => $this->patient->id,
            'examiner_id' => $this->user->id,
            'diagnosis' => 'Chronic tonsillitis, deviated nasal septum',
        ]);
    }

    public function test_ent_record_index(): void
    {
        EntRecord::create([
            'patient_id' => $this->patient->id,
            'examiner_id' => $this->user->id,
            'diagnosis' => 'Acute otitis media',
            'ear_findings' => 'Bulging right TM with erythema.',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('hms.ent.records.index'));
        $response->assertOk();
    }

    public function test_ent_record_validation_missing_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.ent.records.store'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['patient_id', 'diagnosis']);
    }

    public function test_ent_record_patient_link(): void
    {
        $otherPatient = Patient::factory()->create();

        $this->actingAs($this->user)->postJson(route('hms.ent.records.store'), [
            'patient_id' => $otherPatient->id,
            'diagnosis' => 'Nasal polyps',
            'nose_findings' => 'Polyps visible in right nasal cavity.',
        ]);

        $this->assertDatabaseHas('ent_records', [
            'patient_id' => $otherPatient->id,
            'examiner_id' => $this->user->id,
        ]);
    }

    // ── M25 Physio / OT ────────────────────────────────────────

    public function test_rehab_session_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.rehab.sessions.store'), [
            'patient_id' => $this->patient->id,
            'session_type' => 'physiotherapy',
            'treatment_area' => 'Left knee post-ACL reconstruction',
            'session_notes' => 'Patient tolerated exercises well. Range of motion improved to 90 degrees.',
            'exercises_performed' => 'Quad sets, straight leg raises, passive ROM, stationary bike',
            'progress_notes' => 'Progressing well. Week 3 of 12.',
            'next_session_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('rehab_session_records', [
            'patient_id' => $this->patient->id,
            'therapist_id' => $this->user->id,
            'session_type' => 'physiotherapy',
        ]);
    }

    public function test_rehab_session_index(): void
    {
        RehabSessionRecord::create([
            'patient_id' => $this->patient->id,
            'therapist_id' => $this->user->id,
            'session_type' => 'occupational_therapy',
            'treatment_area' => 'Right hand fine motor skills',
            'session_notes' => 'Practised grip exercises and fine manipulation tasks.',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('hms.rehab.sessions.index'));
        $response->assertOk();
    }

    public function test_rehab_session_validation_missing_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.rehab.sessions.store'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['patient_id', 'session_type', 'treatment_area', 'session_notes']);
    }

    public function test_rehab_session_patient_link(): void
    {
        $otherPatient = Patient::factory()->create();

        $this->actingAs($this->user)->postJson(route('hms.rehab.sessions.store'), [
            'patient_id' => $otherPatient->id,
            'session_type' => 'occupational_therapy',
            'treatment_area' => 'Shoulder mobility',
            'session_notes' => 'Initial assessment and treatment.',
        ]);

        $this->assertDatabaseHas('rehab_session_records', [
            'patient_id' => $otherPatient->id,
            'therapist_id' => $this->user->id,
        ]);
    }

    // ── M26 Nutrition ───────────────────────────────────────────

    public function test_nutrition_record_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.nutrition.records.store'), [
            'patient_id' => $this->patient->id,
            'assessment_type' => 'assessment',
            'bmi' => 28.5,
            'malnutrition_risk' => 'low',
            'diet_plan' => 'Balanced diet with reduced refined carbohydrates. Increase vegetable intake.',
            'calorie_target' => 2000,
            'protein_target' => 65.0,
            'notes' => 'Patient overweight but no signs of malnutrition.',
            'status' => 'active',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('nutrition_records', [
            'patient_id' => $this->patient->id,
            'nutritionist_id' => $this->user->id,
            'assessment_type' => 'assessment',
            'status' => 'active',
        ]);
    }

    public function test_nutrition_record_index(): void
    {
        NutritionRecord::create([
            'patient_id' => $this->patient->id,
            'nutritionist_id' => $this->user->id,
            'assessment_type' => 'screening',
            'malnutrition_risk' => 'moderate',
            'diet_plan' => 'High protein diet supplementation.',
            'notes' => 'Patient shows signs of nutritional deficit.',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('hms.nutrition.records.index'));
        $response->assertOk();
    }

    public function test_nutrition_record_validation_missing_required(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('hms.nutrition.records.store'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'patient_id', 'assessment_type', 'malnutrition_risk', 'diet_plan', 'notes',
        ]);
    }

    public function test_nutrition_record_patient_link(): void
    {
        $otherPatient = Patient::factory()->create();

        $this->actingAs($this->user)->postJson(route('hms.nutrition.records.store'), [
            'patient_id' => $otherPatient->id,
            'assessment_type' => 'follow_up',
            'malnutrition_risk' => 'high',
            'diet_plan' => 'Enteral feeding plan with fortified supplements.',
            'notes' => 'Post-surgical malnutrition risk.',
        ]);

        $this->assertDatabaseHas('nutrition_records', [
            'patient_id' => $otherPatient->id,
            'nutritionist_id' => $this->user->id,
        ]);
    }
}
