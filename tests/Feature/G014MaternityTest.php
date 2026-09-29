<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Ward;
use App\Models\Pregnancy;
use App\Models\LabourRecord;
use App\Models\Delivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G014MaternityTest extends TestCase
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

    public function test_pregnancy_registration_with_gravida_para(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.maternity.pregnancies.store'), [
            'patient_id' => $this->patient->id,
            'gravida' => 3,
            'parity' => 2,
            'last_menstrual_date' => now()->subMonths(5)->toDateString(),
            'estimated_due_date' => now()->addMonths(4)->toDateString(),
            'current_gestational_weeks' => 20,
            'blood_group' => 'O',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'previous_complications' => 'Previous preterm labour at 34 weeks',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pregnancies', [
            'patient_id' => $this->patient->id,
            'gravida' => 3,
            'parity' => 2,
            'blood_group' => 'O',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'status' => 'active',
        ]);
    }

    public function test_anc_visit_creation_with_vitals(): void
    {
        $pregnancy = Pregnancy::create([
            'patient_id' => $this->patient->id,
            'gravida' => 1,
            'parity' => 0,
            'last_menstrual_date' => now()->subMonths(6)->toDateString(),
            'estimated_due_date' => now()->addMonths(3)->toDateString(),
            'current_gestational_weeks' => 24,
            'blood_group' => 'A',
            'rh_factor' => 'negative',
            'hiv_status' => 'unknown',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.maternity.pregnancies.anc.store', $pregnancy), [
            'visit_number' => 1,
            'visit_date' => now()->toDateString(),
            'gestational_age_weeks' => 24,
            'weight_kg' => 68.5,
            'blood_pressure_sys' => 120,
            'blood_pressure_dia' => 80,
            'hemoglobin' => 11.2,
            'urine_protein' => 'negative',
            'urine_glucose' => 'negative',
            'fundal_height' => 24.0,
            'fetal_heart_rate' => 140,
            'presentation' => 'cephalic',
            'notes' => 'Normal ANC visit. Patient doing well.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('anc_visits', [
            'pregnancy_id' => $pregnancy->id,
            'visit_number' => 1,
            'weight_kg' => 68.5,
            'blood_pressure_sys' => 120,
            'blood_pressure_dia' => 80,
            'fetal_heart_rate' => 140,
            'visited_by' => $this->user->id,
        ]);
    }

    public function test_labour_admission(): void
    {
        $pregnancy = Pregnancy::create([
            'patient_id' => $this->patient->id,
            'gravida' => 1,
            'parity' => 0,
            'last_menstrual_date' => now()->subMonths(9)->toDateString(),
            'estimated_due_date' => now()->toDateString(),
            'current_gestational_weeks' => 40,
            'blood_group' => 'B',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'status' => 'active',
        ]);

        $ward = Ward::create(['name' => 'Maternity Ward', 'code' => 'MAT-01', 'ward_type' => 'maternity', 'capacity' => 20, 'is_active' => true]);

        $response = $this->actingAs($this->user)->post(route('hms.maternity.labour.store'), [
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'admission_time' => now()->toDateTimeString(),
            'labour_start_time' => now()->subHours(3)->toDateTimeString(),
            'membrane_status' => 'intact',
            'cervical_dilation' => 4,
            'liquor' => 'clear',
            'presenting_part' => 'cephalic',
            'ward_id' => $ward->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('labour_records', [
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'membrane_status' => 'intact',
            'cervical_dilation' => 4,
            'status' => 'active',
        ]);
    }

    public function test_partograph_entry_recording(): void
    {
        $pregnancy = Pregnancy::create([
            'patient_id' => $this->patient->id,
            'gravida' => 2,
            'parity' => 1,
            'last_menstrual_date' => now()->subMonths(9)->toDateString(),
            'estimated_due_date' => now()->toDateString(),
            'current_gestational_weeks' => 40,
            'blood_group' => 'AB',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'status' => 'active',
        ]);

        $labour = LabourRecord::create([
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'admission_time' => now()->subHours(6)->toDateTimeString(),
            'labour_start_time' => now()->subHours(4)->toDateTimeString(),
            'membrane_status' => 'ruptured',
            'rupture_time' => now()->subHours(3)->toDateTimeString(),
            'cervical_dilation' => 5,
            'liquor' => 'clear',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.maternity.labour.partograph.store', $labour), [
            'time_recorded' => now()->toDateTimeString(),
            'cervical_dilation' => 6,
            'descent' => 2,
            'contractions_per_10' => 4,
            'fetal_heart_rate' => 135,
            'maternal_pulse' => 88,
            'maternal_bp' => '125/85',
            'urine_output' => 150,
            'notes' => 'Labour progressing well.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partograph_entries', [
            'labour_record_id' => $labour->id,
            'cervical_dilation' => 6,
            'fetal_heart_rate' => 135,
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_delivery_recording_with_apgar_scores(): void
    {
        $pregnancy = Pregnancy::create([
            'patient_id' => $this->patient->id,
            'gravida' => 1,
            'parity' => 0,
            'last_menstrual_date' => now()->subMonths(9)->toDateString(),
            'estimated_due_date' => now()->toDateString(),
            'current_gestational_weeks' => 40,
            'blood_group' => 'O',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.maternity.deliveries.store'), [
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'delivery_date' => now()->toDateString(),
            'delivery_time' => '14:30',
            'mode' => 'normal_assisted',
            'baby_sex' => 'male',
            'birth_weight_grams' => 3200,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
            'alive' => true,
            'placenta_delivered_time' => now()->addMinutes(15)->toDateTimeString(),
            'placenta_complete' => true,
            'blood_loss_ml' => 300,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('deliveries', [
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'mode' => 'normal_assisted',
            'baby_sex' => 'male',
            'birth_weight_grams' => 3200,
            'apgar_1_min' => 8,
            'apgar_5_min' => 9,
            'alive' => true,
            'delivered_by' => $this->user->id,
        ]);

        // Pregnancy should be marked as completed
        $this->assertDatabaseHas('pregnancies', [
            'id' => $pregnancy->id,
            'status' => 'completed',
        ]);
    }

    public function test_postnatal_visit(): void
    {
        $pregnancy = Pregnancy::create([
            'patient_id' => $this->patient->id,
            'gravida' => 1,
            'parity' => 0,
            'last_menstrual_date' => now()->subMonths(9)->toDateString(),
            'estimated_due_date' => now()->subDays(6)->toDateString(),
            'current_gestational_weeks' => 40,
            'blood_group' => 'A',
            'rh_factor' => 'positive',
            'hiv_status' => 'known_negative',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.maternity.pregnancies.postnatal.store', $pregnancy), [
            'patient_id' => $this->patient->id,
            'visit_date' => now()->toDateString(),
            'visit_day_postpartum' => 6,
            'blood_pressure_sys' => 118,
            'blood_pressure_dia' => 76,
            'uterine_involution' => 'good',
            'lochia' => 'normal',
            'breast_feeding' => 'yes',
            'family_planning_counselled' => true,
            'family_planning_method' => 'pills',
            'wound_check' => 'Caesarean wound healing well',
            'mental_health_screening' => 'No signs of PND',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('postnatal_visits', [
            'pregnancy_id' => $pregnancy->id,
            'patient_id' => $this->patient->id,
            'visit_day_postpartum' => 6,
            'uterine_involution' => 'good',
            'breast_feeding' => 'yes',
            'family_planning_counselled' => true,
            'visited_by' => $this->user->id,
        ]);
    }

    public function test_family_planning_visit(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.maternity.family-planning.store'), [
            'patient_id' => $this->patient->id,
            'method' => 'injectable',
            'previous_method' => 'pills',
            'side_effects' => 'Mild weight gain noticed',
            'visit_date' => now()->toDateString(),
            'next_visit_date' => now()->addMonths(3)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('family_planning_visits', [
            'patient_id' => $this->patient->id,
            'method' => 'injectable',
            'previous_method' => 'pills',
            'counselled_by' => $this->user->id,
        ]);
    }

    public function test_high_risk_flagging(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.maternity.pregnancies.store'), [
            'patient_id' => $this->patient->id,
            'gravida' => 4,
            'parity' => 3,
            'last_menstrual_date' => now()->subMonths(7)->toDateString(),
            'estimated_due_date' => now()->addMonths(2)->toDateString(),
            'current_gestational_weeks' => 28,
            'blood_group' => 'O',
            'rh_factor' => 'negative',
            'hiv_status' => 'known_negative',
            'previous_complications' => 'Pre-eclampsia in previous pregnancy',
            'is_high_risk' => true,
            'high_risk_reason' => 'History of pre-eclampsia, age > 35, grand multiparity',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pregnancies', [
            'patient_id' => $this->patient->id,
            'is_high_risk' => true,
            'high_risk_reason' => 'History of pre-eclampsia, age > 35, grand multiparity',
            'status' => 'active',
        ]);
    }
}
