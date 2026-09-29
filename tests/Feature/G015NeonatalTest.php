<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\BirthReport;
use App\Models\Doctor;
use App\Models\Newborn;
use App\Models\NeonatalAssessment;
use App\Models\NicuAdmission;
use App\Models\IncubatorAssignment;
use App\Models\PhototherapySession;
use App\Models\NeonatalFeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G015NeonatalTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $babyPatient;
    private Patient $motherPatient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->babyPatient = Patient::factory()->create(['first_name' => 'Baby', 'last_name' => 'Test']);
        $this->motherPatient = Patient::factory()->create(['first_name' => 'Mother', 'last_name' => 'Test']);
    }

    public function test_newborn_registration_linked_to_delivery(): void
    {
        $birthReport = BirthReport::create([
            'report_number' => 'BR-2026-000001',
            'baby_name' => 'Baby Test',
            'mother_name' => 'Mother Test',
            'father_name' => 'Father Test',
            'birth_date' => now()->toDateString(),
            'birth_time' => now()->format('H:i:s'),
            'gender' => 'male',
            'birth_weight' => 3.20,
            'birth_length' => 49.5,
            'delivery_type' => 'normal',
            'attending_doctor_id' => Doctor::factory()->create()->id,
            'mother_patient_id' => $this->motherPatient->id,
            'baby_patient_id' => $this->babyPatient->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.neonatal.newborns.store'), [
            'patient_id' => $this->babyPatient->id,
            'birth_report_id' => $birthReport->id,
            'mother_patient_id' => $this->motherPatient->id,
            'baby_name' => 'Baby Test',
            'sex' => 'male',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 3200,
            'gestational_age_weeks' => 39.5,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('newborns', [
            'patient_id' => $this->babyPatient->id,
            'birth_report_id' => $birthReport->id,
            'mother_patient_id' => $this->motherPatient->id,
            'sex' => 'male',
            'birth_weight_grams' => 3200,
        ]);
    }

    public function test_apgar_scores_recorded(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.newborns.store'), [
            'patient_id' => $this->babyPatient->id,
            'sex' => 'female',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 2800,
            'gestational_age_weeks' => 37.0,
            'apgar_1_min' => 7,
            'apgar_5_min' => 9,
            'apgar_10_min' => 10,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('newborns', [
            'patient_id' => $this->babyPatient->id,
            'apgar_1_min' => 7,
            'apgar_5_min' => 9,
            'apgar_10_min' => 10,
        ]);
    }

    public function test_neonatal_assessment_with_weight_feeding(): void
    {
        $newborn = Newborn::create([
            'patient_id' => $this->babyPatient->id,
            'sex' => 'male',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 3200,
            'gestational_age_weeks' => 39.5,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
            'status' => 'well_baby',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.neonatal.assessments.store', $newborn), [
            'assessment_date' => now()->toDateString(),
            'weight_grams' => 3150,
            'length_cm' => 50.0,
            'head_circumference_cm' => 34.5,
            'temperature' => 36.7,
            'heart_rate' => 130,
            'respiratory_rate' => 40,
            'feeding_type' => 'breast',
            'stool_passed' => true,
            'jaundice' => 'mild',
            'reflexes' => 'present',
            'cried_at_birth' => true,
            'notes' => 'Baby feeding well.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('neonatal_assessments', [
            'newborn_id' => $newborn->id,
            'weight_grams' => 3150,
            'feeding_type' => 'breast',
            'jaundice' => 'mild',
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_nicu_admission_and_discharge(): void
    {
        $newborn = Newborn::create([
            'patient_id' => $this->babyPatient->id,
            'sex' => 'male',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 1800,
            'gestational_age_weeks' => 32.0,
            'apgar_1_min' => 4,
            'apgar_5_min' => 6,
            'status' => 'well_baby',
        ]);

        // NICU Admission
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.nicu.store', $newborn), [
            'patient_id' => $this->babyPatient->id,
            'admission_date' => now()->toDateTimeString(),
            'reason' => 'Prematurity and low birth weight',
            'admission_weight_grams' => 1800,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('nicu_admissions', [
            'newborn_id' => $newborn->id,
            'patient_id' => $this->babyPatient->id,
            'status' => 'active',
            'admitted_by' => $this->user->id,
        ]);
        $this->assertDatabaseHas('newborns', [
            'id' => $newborn->id,
            'status' => 'nicu',
        ]);

        $admission = NicuAdmission::where('newborn_id', $newborn->id)->first();

        // NICU Discharge
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.nicu.discharge', $admission), [
            'discharge_date' => now()->toDateTimeString(),
            'discharge_weight_grams' => 2200,
            'discharge_destination' => 'Normal ward',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('nicu_admissions', [
            'id' => $admission->id,
            'status' => 'discharged',
            'discharge_weight_grams' => 2200,
            'discharge_destination' => 'Normal ward',
        ]);
        $this->assertDatabaseHas('newborns', [
            'id' => $newborn->id,
            'status' => 'discharged',
        ]);
    }

    public function test_phototherapy_session(): void
    {
        $newborn = Newborn::create([
            'patient_id' => $this->babyPatient->id,
            'sex' => 'female',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 3000,
            'gestational_age_weeks' => 38.0,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
            'status' => 'well_baby',
        ]);

        // Start phototherapy
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.phototherapy.store', $newborn), [
            'start_time' => now()->toDateTimeString(),
            'light_type' => 'LED',
            'bilirubin_before' => 15.2,
            'eye_protection' => true,
            'notes' => 'Mild jaundice observed.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('phototherapy_sessions', [
            'newborn_id' => $newborn->id,
            'light_type' => 'LED',
            'bilirubin_before' => 15.2,
            'eye_protection' => true,
        ]);

        $session = PhototherapySession::where('newborn_id', $newborn->id)->first();

        // Stop phototherapy
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.phototherapy.stop', $session), [
            'end_time' => now()->addHours(12)->toDateTimeString(),
            'bilirubin_after' => 10.5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('phototherapy_sessions', [
            'id' => $session->id,
            'bilirubin_after' => 10.5,
            'duration_hours' => 12.0,
        ]);
    }

    public function test_feeding_record(): void
    {
        $newborn = Newborn::create([
            'patient_id' => $this->babyPatient->id,
            'sex' => 'male',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 3500,
            'gestational_age_weeks' => 40.0,
            'apgar_1_min' => 9,
            'apgar_5_min' => 10,
            'status' => 'well_baby',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.neonatal.feeds.store', $newborn), [
            'feed_time' => now()->toDateTimeString(),
            'feed_type' => 'breast',
            'volume_ml' => null,
            'duration_minutes' => 20,
            'method' => 'suckling',
            'notes' => 'Baby fed well on both breasts.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('neonatal_feeds', [
            'newborn_id' => $newborn->id,
            'feed_type' => 'breast',
            'method' => 'suckling',
            'duration_minutes' => 20,
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_maternal_newborn_linkage(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.neonatal.newborns.store'), [
            'patient_id' => $this->babyPatient->id,
            'mother_patient_id' => $this->motherPatient->id,
            'sex' => 'male',
            'date_of_birth' => now()->toDateString(),
            'time_of_birth' => now()->format('H:i:s'),
            'birth_weight_grams' => 3100,
            'gestational_age_weeks' => 39.0,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
        ]);

        $response->assertRedirect();
        $newborn = Newborn::where('patient_id', $this->babyPatient->id)->first();
        $this->assertNotNull($newborn);
        $this->assertEquals($this->motherPatient->id, $newborn->mother_patient_id);

        // Verify relationship
        $this->assertNotNull($newborn->mother);
        $this->assertEquals($this->motherPatient->id, $newborn->mother->id);
    }
}
