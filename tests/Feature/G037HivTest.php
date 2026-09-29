<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\HtsEncounter;
use App\Models\HivCareEnrollment;
use App\Models\ArtRegimen;
use App\Models\ViralLoadResult;
use App\Models\PepPrepRecord;
use App\Models\PartnerNotification;
use App\Models\HeiRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G037HivTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Patient $motherPatient;
    private Patient $newbornPatient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create(['first_name' => 'James', 'last_name' => 'Mwangi']);
        $this->motherPatient = Patient::factory()->create(['first_name' => 'Jane', 'last_name' => 'Wanjiku']);
        $this->newbornPatient = Patient::factory()->create(['first_name' => 'Baby', 'last_name' => 'Wanjiku']);
    }

    public function test_hts_encounter_with_consent_and_result(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.hiv.hts.store'), [
            'patient_id' => $this->patient->id,
            'encounter_date' => now()->toDateString(),
            'consent_given' => true,
            'risk_assessment_done' => true,
            'test_type' => 'hts',
            'test_result' => 'negative',
            'test_date' => now()->toDateString(),
            'counselled_before' => true,
            'counselled_after' => true,
            'referral_offered' => false,
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hts_encounters', [
            'patient_id' => $this->patient->id,
            'test_result' => 'negative',
            'consent_given' => true,
            'tested_by' => $this->user->id,
            'status' => 'completed',
        ]);
    }

    public function test_hiv_care_enrollment_with_art_number(): void
    {
        $htsEncounter = HtsEncounter::create([
            'patient_id' => $this->patient->id,
            'encounter_date' => now()->toDateString(),
            'hts_number' => 'HTS000001',
            'consent_given' => true,
            'test_type' => 'hts',
            'test_result' => 'positive',
            'tested_by' => $this->user->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.hiv.care.enroll'), [
            'patient_id' => $this->patient->id,
            'hts_encounter_id' => $htsEncounter->id,
            'enrollment_date' => now()->toDateString(),
            'art_number' => 'ART-2026-0001',
            'who_stage' => 2,
            'baseline_cd4' => 350,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hiv_care_enrollments', [
            'patient_id' => $this->patient->id,
            'art_number' => 'ART-2026-0001',
            'hts_encounter_id' => $htsEncounter->id,
            'who_stage' => 2,
            'enrolled_by' => $this->user->id,
            'status' => 'active',
        ]);
    }

    public function test_art_regimen_start_and_switch(): void
    {
        $enrollment = HivCareEnrollment::create([
            'patient_id' => $this->patient->id,
            'enrollment_date' => now()->toDateString(),
            'art_number' => 'ART-2026-0002',
            'enrolled_by' => $this->user->id,
            'status' => 'active',
        ]);

        // Start first-line regimen
        $response = $this->actingAs($this->user)->post(route('hms.hiv.care.regimen.start', $enrollment), [
            'regimen_code' => 'TLE',
            'start_date' => now()->toDateString(),
            'regimen_line' => 'first',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('art_regimens', [
            'care_enrollment_id' => $enrollment->id,
            'regimen_code' => 'TLE',
            'regimen_line' => 'first',
            'prescribed_by' => $this->user->id,
        ]);

        // Switch to second-line regimen
        $response = $this->actingAs($this->user)->post(route('hms.hiv.care.regimen.change', $enrollment), [
            'regimen_code' => 'AZT-3TC-NVP',
            'start_date' => now()->addMonths(3)->toDateString(),
            'regimen_line' => 'second',
            'reason_for_change' => 'toxicity',
        ]);

        $response->assertRedirect();

        // First regimen should have an end_date
        $firstRegimen = ArtRegimen::where('care_enrollment_id', $enrollment->id)
            ->where('regimen_code', 'TLE')
            ->first();
        $this->assertNotNull($firstRegimen->end_date);
        $this->assertEquals('toxicity', $firstRegimen->reason_for_change);

        // New regimen should exist
        $this->assertDatabaseHas('art_regimens', [
            'care_enrollment_id' => $enrollment->id,
            'regimen_code' => 'AZT-3TC-NVP',
            'regimen_line' => 'second',
        ]);
    }

    public function test_viral_load_result_recording(): void
    {
        $enrollment = HivCareEnrollment::create([
            'patient_id' => $this->patient->id,
            'enrollment_date' => now()->toDateString(),
            'art_number' => 'ART-2026-0003',
            'enrolled_by' => $this->user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.hiv.care.viral-load.store', $enrollment), [
            'patient_id' => $this->patient->id,
            'result_date' => now()->toDateString(),
            'viral_load_copies' => 150,
            'detection_limit' => '<20',
            'suppression_status' => 'suppressed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('viral_load_results', [
            'care_enrollment_id' => $enrollment->id,
            'patient_id' => $this->patient->id,
            'viral_load_copies' => 150,
            'detection_limit' => '<20',
            'suppression_status' => 'suppressed',
            'ordered_by' => $this->user->id,
        ]);
    }

    public function test_pep_prep_record_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.hiv.pep-prep.store'), [
            'patient_id' => $this->patient->id,
            'record_type' => 'PEP',
            'start_date' => now()->toDateString(),
            'indication' => 'Occupational exposure - needle stick injury',
            'regimen' => 'TDF/3TC/DTG',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pep_prep_records', [
            'patient_id' => $this->patient->id,
            'record_type' => 'PEP',
            'indication' => 'Occupational exposure - needle stick injury',
            'regimen' => 'TDF/3TC/DTG',
            'prescribed_by' => $this->user->id,
            'status' => 'active',
        ]);
    }

    public function test_partner_notification_workflow(): void
    {
        $htsEncounter = HtsEncounter::create([
            'patient_id' => $this->patient->id,
            'encounter_date' => now()->toDateString(),
            'hts_number' => 'HTS000002',
            'consent_given' => true,
            'test_type' => 'hts',
            'test_result' => 'positive',
            'tested_by' => $this->user->id,
            'status' => 'completed',
        ]);

        // Create partner notification
        $response = $this->actingAs($this->user)->post(route('hms.hiv.partner-notifications.store'), [
            'patient_id' => $this->patient->id,
            'partner_name' => 'Mary Mwangi',
            'partner_phone' => '0712345678',
            'notification_method' => 'self',
            'hts_encounter_id' => $htsEncounter->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partner_notifications', [
            'patient_id' => $this->patient->id,
            'partner_name' => 'Mary Mwangi',
            'notification_method' => 'self',
            'status' => 'pending',
        ]);

        $notification = PartnerNotification::where('patient_id', $this->patient->id)->first();

        // Update to notified
        $response = $this->actingAs($this->user)->put(route('hms.hiv.partner-notifications.update', $notification), [
            'status' => 'notified',
            'notified_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partner_notifications', [
            'id' => $notification->id,
            'status' => 'notified',
        ]);

        // Update to tested
        $response = $this->actingAs($this->user)->put(route('hms.hiv.partner-notifications.update', $notification), [
            'status' => 'tested',
            'tested_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partner_notifications', [
            'id' => $notification->id,
            'status' => 'tested',
        ]);
    }

    public function test_hei_record_with_pcr_results(): void
    {
        // Create HEI record
        $response = $this->actingAs($this->user)->post(route('hms.hiv.hei.store'), [
            'newborn_id' => $this->newbornPatient->id,
            'mother_patient_id' => $this->motherPatient->id,
            'mother_art_number' => 'ART-2026-0004',
            'birth_date' => now()->subDays(3)->toDateString(),
            'prophylaxis_given' => true,
            'cotrimoxazole_start' => now()->subDays(2)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hei_records', [
            'newborn_id' => $this->newbornPatient->id,
            'mother_patient_id' => $this->motherPatient->id,
            'mother_art_number' => 'ART-2026-0004',
            'prophylaxis_given' => true,
            'final_status' => 'pending',
            'status' => 'active',
        ]);

        $hei = HeiRecord::where('newborn_id', $this->newbornPatient->id)->first();

        // Record PCR at 6 weeks (round 1)
        $response = $this->actingAs($this->user)->post(route('hms.hiv.hei.pcr', $hei), [
            'pcr_round' => '1',
            'pcr_result' => 'negative',
            'pcr_date' => now()->addWeeks(6)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hei_records', [
            'id' => $hei->id,
            'pcr_1_result' => 'negative',
        ]);

        // Record PCR at 3 months (round 2)
        $response = $this->actingAs($this->user)->post(route('hms.hiv.hei.pcr', $hei), [
            'pcr_round' => '2',
            'pcr_result' => 'negative',
            'pcr_date' => now()->addMonths(3)->toDateString(),
        ]);

        $response->assertRedirect();

        // Record PCR at 6 months (round 6)
        $response = $this->actingAs($this->user)->post(route('hms.hiv.hei.pcr', $hei), [
            'pcr_round' => '6',
            'pcr_result' => 'negative',
            'pcr_date' => now()->addMonths(6)->toDateString(),
        ]);

        $response->assertRedirect();

        // All negatives → final_status should be exposed_uninfected
        $hei->refresh();
        $this->assertEquals('exposed_uninfected', $hei->final_status);
    }

    public function test_hei_positive_pcr_sets_final_status(): void
    {
        $hei = HeiRecord::create([
            'newborn_id' => $this->newbornPatient->id,
            'mother_patient_id' => $this->motherPatient->id,
            'birth_date' => now()->subDays(3)->toDateString(),
            'final_status' => 'pending',
            'status' => 'active',
        ]);

        // Positive PCR at 6 weeks
        $this->actingAs($this->user)->post(route('hms.hiv.hei.pcr', $hei), [
            'pcr_round' => '1',
            'pcr_result' => 'positive',
            'pcr_date' => now()->addWeeks(6)->toDateString(),
        ]);

        $hei->refresh();
        $this->assertEquals('exposed_infected', $hei->final_status);
    }
}
