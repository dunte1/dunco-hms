<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\MhAssessment;
use App\Models\MhTreatmentPlan;
use App\Models\CounsellingSession;
use App\Models\SocialAssessment;
use App\Models\WelfareWaiverRequest;
use App\Models\WardDischargePlan;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G042MentalHealthSocialWorkTest extends TestCase
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

    // ── M27 Mental Health ──────────────────────────────────────

    public function test_mh_assessment_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.mental-health.assessments.store'), [
            'patient_id' => $this->patient->id,
            'assessment_date' => now()->toDateString(),
            'presenting_complaint' => 'Persistent low mood and anxiety for 3 months',
            'mental_status_examination' => 'Patient is alert, oriented x3. Mood is depressed. Affect is flat.',
            'risk_assessment' => 'moderate',
            'suicidal_ideation' => false,
            'homicidal_ideation' => false,
            'self_harm_risk' => false,
            'substance_use' => null,
            'functioning_score' => 65.5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mh_assessments', [
            'patient_id' => $this->patient->id,
            'risk_assessment' => 'moderate',
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_mh_assessment_high_risk_with_suicidal_ideation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.mental-health.assessments.store'), [
            'patient_id' => $this->patient->id,
            'assessment_date' => now()->toDateString(),
            'presenting_complaint' => 'Expresses desire to end life',
            'mental_status_examination' => 'Patient is tearful, psychomotor agitation noted. Hopeless outlook.',
            'risk_assessment' => 'high',
            'suicidal_ideation' => true,
            'homicidal_ideation' => false,
            'self_harm_risk' => true,
            'substance_use' => 'Alcohol dependency',
            'functioning_score' => 30.0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mh_assessments', [
            'risk_assessment' => 'high',
            'suicidal_ideation' => true,
            'self_harm_risk' => true,
        ]);
    }

    public function test_mh_treatment_plan_store(): void
    {
        $assessment = MhAssessment::create([
            'patient_id' => $this->patient->id,
            'assessment_date' => now()->toDateString(),
            'presenting_complaint' => 'Depression',
            'mental_status_examination' => 'Depressed mood',
            'risk_assessment' => 'moderate',
            'assessed_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.mental-health.treatment-plans.store'), [
            'patient_id' => $this->patient->id,
            'mh_assessment_id' => $assessment->id,
            'diagnosis' => 'Major Depressive Disorder, Recurrent, Moderate',
            'goals' => 'Reduce PHQ-9 score to below 10 within 3 months',
            'interventions' => 'CBT sessions weekly, medication management',
            'medications' => 'Sertraline 50mg daily',
            'follow_up_frequency' => 'weekly',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('mh_treatment_plans', [
            'patient_id' => $this->patient->id,
            'mh_assessment_id' => $assessment->id,
            'status' => 'active',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_mh_treatment_plan_index(): void
    {
        MhTreatmentPlan::create([
            'patient_id' => $this->patient->id,
            'diagnosis' => 'GAD',
            'goals' => 'Reduce anxiety',
            'interventions' => 'CBT',
            'status' => 'active',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.mental-health.treatment-plans.index'));
        $response->assertOk();
    }

    public function test_counselling_session_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.mental-health.counselling.store'), [
            'patient_id' => $this->patient->id,
            'session_date' => now()->toDateString(),
            'session_type' => 'individual',
            'presenting_issue' => 'Anxiety and sleep disturbances',
            'interventions_used' => 'Relaxation techniques, sleep hygiene education',
            'patient_response' => 'Patient engaged well and expressed willingness to practice techniques',
            'risk_level' => 'low',
            'next_session_date' => now()->addWeek()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('counselling_sessions', [
            'patient_id' => $this->patient->id,
            'session_type' => 'individual',
            'risk_level' => 'low',
            'counsellor_id' => $this->user->id,
        ]);
    }

    public function test_counselling_session_crisis_type(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.mental-health.counselling.store'), [
            'patient_id' => $this->patient->id,
            'session_date' => now()->toDateString(),
            'session_type' => 'crisis',
            'presenting_issue' => 'Acute panic attack, fear of dying',
            'interventions_used' => 'Grounding techniques, safety planning',
            'patient_response' => 'Panic subsided after 20 minutes',
            'risk_level' => 'high',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('counselling_sessions', [
            'session_type' => 'crisis',
            'risk_level' => 'high',
        ]);
    }

    // ── M28 Social Work ───────────────────────────────────────

    public function test_social_assessment_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.social.assessments.store'), [
            'patient_id' => $this->patient->id,
            'assessment_date' => now()->toDateString(),
            'living_situation' => 'family',
            'income_source' => 'Employment at local factory',
            'financial_status' => 'unstable',
            'family_support' => 'limited',
            'transport_needs' => true,
            'housing_needs' => false,
            'legal_needs' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('social_assessments', [
            'patient_id' => $this->patient->id,
            'living_situation' => 'family',
            'financial_status' => 'unstable',
            'assessed_by' => $this->user->id,
        ]);
    }

    public function test_social_assessment_homeless_financial_crisis(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.social.assessments.store'), [
            'patient_id' => $this->patient->id,
            'assessment_date' => now()->toDateString(),
            'living_situation' => 'homeless',
            'income_source' => null,
            'financial_status' => 'crisis',
            'family_support' => 'none',
            'transport_needs' => true,
            'housing_needs' => true,
            'legal_needs' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('social_assessments', [
            'living_situation' => 'homeless',
            'financial_status' => 'crisis',
            'housing_needs' => true,
            'legal_needs' => true,
        ]);
    }

    public function test_waiver_request_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.social.waivers.store'), [
            'patient_id' => $this->patient->id,
            'amount' => 5000.00,
            'reason' => 'Patient unable to pay due to financial hardship',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('waiver_requests', [
            'patient_id' => $this->patient->id,
            'amount' => 5000.00,
            'status' => 'pending',
            'requested_by' => $this->user->id,
        ]);
    }

    public function test_waiver_request_workflow_pending_to_approved(): void
    {
        $waiver = WelfareWaiverRequest::create([
            'patient_id' => $this->patient->id,
            'amount' => 10000.00,
            'reason' => 'Indigent patient',
            'requested_by' => $this->user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.social.waivers.approve', $waiver->id));
        $response->assertRedirect();

        $waiver->refresh();
        $this->assertEquals('approved', $waiver->status);
        $this->assertEquals($this->user->id, $waiver->approved_by);
        $this->assertNotNull($waiver->approved_at);
    }

    public function test_waiver_request_reject(): void
    {
        $waiver = WelfareWaiverRequest::create([
            'patient_id' => $this->patient->id,
            'amount' => 3000.00,
            'reason' => 'Transport costs',
            'requested_by' => $this->user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.social.waivers.reject', $waiver->id));
        $response->assertRedirect();

        $waiver->refresh();
        $this->assertEquals('rejected', $waiver->status);
    }

    public function test_waiver_cannot_approve_non_pending(): void
    {
        $waiver = WelfareWaiverRequest::create([
            'patient_id' => $this->patient->id,
            'amount' => 2000.00,
            'reason' => 'Already approved',
            'requested_by' => $this->user->id,
            'status' => 'approved',
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.social.waivers.approve', $waiver->id));
        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
    }

    public function test_discharge_plan_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.social.discharge-plans.store'), [
            'patient_id' => $this->patient->id,
            'discharge_date' => now()->addDays(3)->toDateString(),
            'home_care_needs' => 'Daily wound dressing, medication reminders',
            'follow_up_appointments' => 'OPD review in 2 weeks, physiotherapy in 1 week',
            'equipment_needs' => 'Wheelchair, hospital bed',
            'community_services' => 'Home nursing visits, community physiotherapy',
            'caregiver_involvement' => 'Spouse to assist with daily activities',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('discharge_plans', [
            'patient_id' => $this->patient->id,
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_discharge_plan_index(): void
    {
        WardDischargePlan::create([
            'patient_id' => $this->patient->id,
            'home_care_needs' => 'Basic care',
            'follow_up_appointments' => 'OPD in 1 week',
            'status' => 'draft',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.social.discharge-plans.index'));
        $response->assertOk();
    }
}
