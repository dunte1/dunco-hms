<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\IpdAdmission;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\IcuAdmission;
use App\Models\CriticalCareChart;
use App\Models\VentilatorSetting;
use App\Models\AbgResult;
use App\Models\SedationScore;
use App\Models\InfusionRecord;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G019IcuTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private IpdAdmission $ipdAdmission;
    private Ward $icuWard;
    private Bed $icuBed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'admit patients']);
        $this->user->givePermissionTo('admit patients');

        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $bedType = BedType::create(['name' => 'ICU', 'charge_per_day' => 5000]);
        $this->icuWard = Ward::create(['name' => 'ICU Ward', 'code' => 'ICU-01', 'ward_type' => 'icu', 'capacity' => 10, 'is_active' => true]);
        $this->icuBed = Bed::create(['bed_number' => 'ICU-001', 'ward_name' => 'ICU Ward', 'bed_type_id' => $bedType->id, 'ward_id' => $this->icuWard->id, 'is_available' => true]);
        $this->ipdAdmission = IpdAdmission::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'bed_id' => $this->icuBed->id,
            'ward_id' => $this->icuWard->id,
            'admission_date' => now(),
            'status' => 'admitted',
        ]);
    }

    public function test_icu_admission_from_ward(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.icu.store'), [
            'patient_id' => $this->patient->id,
            'ipd_admission_id' => $this->ipdAdmission->id,
            'ward_id' => $this->icuWard->id,
            'bed_id' => $this->icuBed->id,
            'unit_type' => 'ICU',
            'admission_from' => 'ward',
            'admission_datetime' => now()->toDateTimeString(),
            'admission_diagnosis' => 'Sepsis with respiratory failure',
            'admitting_doctor_id' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('icu_admissions', [
            'patient_id' => $this->patient->id,
            'ipd_admission_id' => $this->ipdAdmission->id,
            'ward_id' => $this->icuWard->id,
            'unit_type' => 'ICU',
            'admission_from' => 'ward',
            'status' => 'active',
            'admitting_doctor_id' => $this->doctor->id,
        ]);
    }

    public function test_hourly_chart_entry_with_gcs(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'bed_id' => $this->icuBed->id,
            'unit_type' => 'ICU',
            'admission_from' => 'emergency',
            'admission_datetime' => now(),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.icu.charts.store', $admission), [
            'chart_date' => now()->toDateString(),
            'hour' => 8,
            'heart_rate' => 90,
            'blood_pressure_sys' => 120,
            'blood_pressure_dia' => 80,
            'map' => 93,
            'respiratory_rate' => 18,
            'spo2' => 97.5,
            'temperature' => 37.2,
            'gcs_eye' => 4,
            'gcs_verbal' => 5,
            'gcs_motor' => 6,
            'pupil_left' => '3mm reactive',
            'pupil_right' => '3mm reactive',
            'urine_output_ml' => 50.0,
            'notes' => 'Patient alert and oriented.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('critical_care_charts', [
            'icu_admission_id' => $admission->id,
            'patient_id' => $this->patient->id,
            'chart_date' => now()->startOfDay()->toDateTimeString(),
            'hour' => 8,
            'heart_rate' => 90,
            'gcs_eye' => 4,
            'gcs_verbal' => 5,
            'gcs_motor' => 6,
            'gcs_total' => 15,
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_ventilator_settings_start_stop(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'unit_type' => 'ICU',
            'admission_from' => 'emergency',
            'admission_datetime' => now(),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        // Start ventilator
        $response = $this->actingAs($this->user)->post(route('hms.icu.ventilators.store', $admission), [
            'mode' => 'volume',
            'set_rate' => 14,
            'tidal_volume' => 500,
            'peep' => 5,
            'fio2' => 40,
            'start_time' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ventilator_settings', [
            'icu_admission_id' => $admission->id,
            'mode' => 'volume',
            'set_rate' => 14,
            'tidal_volume' => 500,
            'status' => 'active',
            'recorded_by' => $this->user->id,
        ]);

        $ventilator = VentilatorSetting::where('icu_admission_id', $admission->id)->first();

        // Stop ventilator
        $response = $this->actingAs($this->user)->post(route('hms.icu.ventilators.stop', $ventilator), [
            'end_time' => now()->addHours(4)->toDateTimeString(),
            'reason_for_change' => 'Patient improved, weaned off ventilator.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ventilator_settings', [
            'id' => $ventilator->id,
            'status' => 'stopped',
            'reason_for_change' => 'Patient improved, weaned off ventilator.',
        ]);
    }

    public function test_abg_result_recording(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'unit_type' => 'ICU',
            'admission_from' => 'ward',
            'admission_datetime' => now(),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.icu.abg.store', $admission), [
            'ph' => 7.35,
            'pco2' => 40.0,
            'po2' => 95.0,
            'hco3' => 24.0,
            'be' => -1.0,
            'sao2' => 98.0,
            'lactate' => 1.2,
            'interpretation' => 'Normal arterial blood gas.',
            'collected_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('abg_results', [
            'icu_admission_id' => $admission->id,
            'patient_id' => $this->patient->id,
            'ph' => 7.35,
            'pco2' => 40.0,
            'po2' => 95.0,
            'hco3' => 24.0,
            'interpretation' => 'Normal arterial blood gas.',
        ]);
    }

    public function test_sedation_scoring(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'unit_type' => 'ICU',
            'admission_from' => 'ward',
            'admission_datetime' => now(),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.icu.sedation.store', $admission), [
            'score_type' => 'rass',
            'score_value' => -2,
            'assessment_time' => now()->toDateTimeString(),
            'notes' => 'Patient calm, responds to voice.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sedation_scores', [
            'icu_admission_id' => $admission->id,
            'patient_id' => $this->patient->id,
            'score_type' => 'rass',
            'score_value' => -2,
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_infusion_recording_and_stopping(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'unit_type' => 'ICU',
            'admission_from' => 'ward',
            'admission_datetime' => now(),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        // Start infusion
        $response = $this->actingAs($this->user)->post(route('hms.icu.infusions.store', $admission), [
            'drug_name' => 'Noradrenaline',
            'concentration' => '4mg in 50ml NS',
            'rate_ml_hr' => 10.50,
            'start_time' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('infusion_records', [
            'icu_admission_id' => $admission->id,
            'patient_id' => $this->patient->id,
            'drug_name' => 'Noradrenaline',
            'rate_ml_hr' => 10.50,
            'status' => 'running',
        ]);

        $infusion = InfusionRecord::where('icu_admission_id', $admission->id)->first();

        // Stop infusion
        $response = $this->actingAs($this->user)->post(route('hms.icu.infusions.stop', $infusion), [
            'end_time' => now()->addHours(2)->toDateTimeString(),
            'volume_infused_ml' => 21.0,
            'reason_for_stop' => 'Blood pressure stabilized.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('infusion_records', [
            'id' => $infusion->id,
            'status' => 'stopped',
            'volume_infused_ml' => 21.0,
            'reason_for_stop' => 'Blood pressure stabilized.',
            'stopped_by' => $this->user->id,
        ]);
    }

    public function test_icu_discharge(): void
    {
        $admission = IcuAdmission::create([
            'patient_id' => $this->patient->id,
            'ward_id' => $this->icuWard->id,
            'bed_id' => $this->icuBed->id,
            'unit_type' => 'ICU',
            'admission_from' => 'emergency',
            'admission_datetime' => now()->subDays(3),
            'admitting_doctor_id' => $this->doctor->id,
            'status' => 'active',
        ]);

        Bed::where('id', $this->icuBed->id)->update(['is_available' => false]);

        $response = $this->actingAs($this->user)->post(route('hms.icu.discharge', $admission), [
            'discharge_datetime' => now()->toDateTimeString(),
            'discharge_destination' => 'General Ward',
            'discharge_condition' => 'Stable',
            'status' => 'discharged',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('icu_admissions', [
            'id' => $admission->id,
            'status' => 'discharged',
            'discharge_destination' => 'General Ward',
            'discharge_condition' => 'Stable',
        ]);
        $this->assertTrue((bool) $this->icuBed->fresh()->is_available);
    }
}
