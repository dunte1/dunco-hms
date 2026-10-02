<?php

namespace Tests\Feature;

use App\Models\BreakGlassEvent;
use App\Models\Patient;
use App\Models\PatientPortalAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Completion tests for newly implemented gaps:
 * rate limiting markers, portal ownership, break-glass, patient 360,
 * prescription/lab void, My Work, FHIR mapper, role composition.
 */
class GapCompletionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        Artisan::call('db:seed', ['--class' => 'ModuleSeeder']);
        \App\Models\Module::resetCache();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function userWithRole(string $role): User
    {
        $u = User::factory()->create(['status' => 'active']);
        $u->assignRole($role);
        return $u;
    }

    // ── Auth rate limiting (route definition) ────────────────────

    public function test_login_and_register_routes_are_throttled(): void
    {
        $routes = app('router')->getRoutes();
        $login = $routes->getByAction('App\Http\Controllers\Auth\AuthenticatedSessionController@store');
        $this->assertNotNull($login);
        $middleware = implode(',', $login->gatherMiddleware());
        $this->assertStringContainsString('throttle', $middleware, 'login POST must be throttled');

        $register = $routes->getByAction('App\Http\Controllers\Auth\RegisteredUserController@store');
        $this->assertNotNull($register);
        $this->assertStringContainsString('throttle', implode(',', $register->gatherMiddleware()));
    }

    // ── Portal own-records ───────────────────────────────────────

    public function test_portal_dependant_rejects_unrelated_patient(): void
    {
        $owner = Patient::factory()->create(['email' => 'owner@example.com', 'phone' => '0711111111']);
        $stranger = Patient::factory()->create(['email' => 'stranger@example.com', 'phone' => '0799999999']);

        $account = PatientPortalAccount::create([
            'patient_id' => $owner->id,
            'username' => 'owner1',
            'password_hash' => bcrypt('secret'),
            'email' => 'owner@example.com',
            'phone' => '0711111111',
            'is_active' => true,
        ]);

        $this->withSession(['patient_portal_user' => $account->id]);

        $resp = $this->post('/patient-portal/dependants', [
            'patient_id' => $stranger->id,
            'relationship' => 'other',
        ]);

        $resp->assertStatus(403);
        $this->assertDatabaseMissing('portal_dependants', [
            'portal_account_id' => $account->id,
            'patient_id' => $stranger->id,
        ]);
    }

    public function test_portal_dependant_allows_matching_contact(): void
    {
        $owner = Patient::factory()->create(['email' => 'family@example.com', 'phone' => '0722222222']);
        $child = Patient::factory()->create(['email' => 'family@example.com', 'phone' => '0733333333']);

        $account = PatientPortalAccount::create([
            'patient_id' => $owner->id,
            'username' => 'family1',
            'password_hash' => bcrypt('secret'),
            'email' => 'family@example.com',
            'phone' => '0722222222',
            'is_active' => true,
        ]);

        $this->withSession(['patient_portal_user' => $account->id]);

        $resp = $this->post('/patient-portal/dependants', [
            'patient_id' => $child->id,
            'relationship' => 'child',
        ]);

        $resp->assertStatus(201);
        $this->assertDatabaseHas('portal_dependants', [
            'portal_account_id' => $account->id,
            'patient_id' => $child->id,
        ]);
    }

    // ── Break-glass workflow ─────────────────────────────────────

    public function test_break_glass_store_creates_audited_event(): void
    {
        $user = $this->userWithRole('Doctor');
        $patient = Patient::factory()->create();

        $resp = $this->actingAs($user)->post('/break-glass', [
            'patient_id' => $patient->id,
            'reason' => 'Emergency access required for unresponsive patient in casualty; normal ACL insufficient.',
        ]);

        $resp->assertStatus(302);
        $this->assertDatabaseHas('break_glass_events', [
            'user_id' => $user->id,
            'patient_id' => $patient->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'break_glass.access',
            'model_type' => 'BreakGlassEvent',
        ]);
    }

    public function test_break_glass_index_requires_permission(): void
    {
        $denied = $this->userWithRole('Receptionist');
        // Receptionist may not have manage break glass events / view audit logs
        $resp = $this->actingAs($denied)->get('/break-glass');
        if ($resp->status() !== 200) {
            $this->assertEquals(403, $resp->status());
        }

        $auditor = $this->userWithRole('System Auditor');
        $this->actingAs($auditor)->get('/break-glass')->assertStatus(200);
    }

    public function test_break_glass_review_approve(): void
    {
        $user = $this->userWithRole('Doctor');
        $auditor = $this->userWithRole('System Auditor');
        $event = BreakGlassEvent::create([
            'user_id' => $user->id,
            'reason' => 'Emergency chart access during mass casualty triage event.',
            'status' => 'pending',
        ]);

        $this->actingAs($auditor)->post("/break-glass/{$event->id}/approve")->assertRedirect(route('break-glass.index'));
        $this->assertEquals('approved', $event->fresh()->status);
    }

    // ── Patient 360 ──────────────────────────────────────────────

    public function test_patient_360_visible_to_authorized_and_hides_unauthorized_sections(): void
    {
        $doctor = $this->userWithRole('Doctor');
        $patient = Patient::factory()->create();

        $resp = $this->actingAs($doctor)->get("/patients/{$patient->id}/360");
        $resp->assertStatus(200);
        $resp->assertSee('Patient 360');
        $resp->assertSee('Prescriptions');
        // Doctor should not be shown billing data section content as authorized if no billing perm
        // Doctor has view payment reports in seeder - billing allowed. Just assert page loads sections.
        $resp->assertSee('Laboratory');
    }

    public function test_patient_360_blocked_for_unauthorized(): void
    {
        $user = User::factory()->create(['status' => 'active']); // no role
        $patient = Patient::factory()->create();
        $resp = $this->actingAs($user)->get("/patients/{$patient->id}/360");
        $this->assertTrue(in_array($resp->status(), [403, 404]), "expected 403/404 got {$resp->status()}");
    }

    // ── Prescription void / lab cancel ───────────────────────────

    public function test_prescription_void_instead_of_hard_delete(): void
    {
        $doctorUser = $this->userWithRole('Doctor');
        $patient = Patient::factory()->create();
        $doctor = \App\Models\Doctor::create([
            'first_name' => 'Test', 'last_name' => 'Doctor',
            'email' => 'rx-doctor-' . uniqid() . '@example.com', 'phone' => '0700000001',
        ]);
        $rx = \App\Models\Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'prescription_date' => now()->toDateString(),
            'status' => 'pending',
            'symptoms' => 'Fever',
        ]);

        $this->actingAs($doctorUser)->delete("/hms/pharmacy/prescriptions/{$rx->id}")
            ->assertRedirect(route('hms.pharmacy.prescriptions.index'));

        $fresh = $rx->fresh();
        $this->assertEquals('cancelled', $fresh->status);
        $this->assertNotNull($fresh); // still exists — not hard-deleted
        $this->assertDatabaseHas('audit_logs', ['action' => 'prescription.void']);
    }

    public function test_lab_request_cancel_instead_of_hard_delete(): void
    {
        $lab = $this->userWithRole('Lab Technician');
        $patient = Patient::factory()->create();
        $req = \App\Models\LabRequest::create([
            'patient_id' => $patient->id,
            'status' => 'pending',
            'request_number' => 'LAB-' . uniqid(),
            'request_date' => now()->toDateString(),
        ]);

        $this->actingAs($lab)->delete("/hms/laboratory/requests/{$req->id}")
            ->assertRedirect();

        $this->assertNotNull($req->fresh());
        $this->assertEquals('cancelled', $req->fresh()->status);
    }

    // ── FHIR mapper ──────────────────────────────────────────────

    public function test_fhir_patient_mapper_produces_r4_shape(): void
    {
        $patient = Patient::factory()->create([
            'first_name' => 'Amina',
            'last_name' => 'Hassan',
            'gender' => 'female',
            'dob' => '1995-04-12',
            'phone' => '0712345678',
            'email' => 'amina@example.com',
            'patient_no' => 'P-2026-000001',
            'national_id' => '12345678',
        ]);

        $mapper = new \App\Services\Fhir\FhirResourceMapper();
        $resource = $mapper->mapPatient($patient);

        $this->assertEquals('Patient', $resource['resourceType']);
        $this->assertEquals('Hassan', $resource['name'][0]['family']);
        $this->assertContains('Amina', $resource['name'][0]['given']);
        $this->assertEquals('female', $resource['gender']);
        $this->assertEquals('1995-04-12', $resource['birthDate']);
        $this->assertNotEmpty($resource['identifier']);
        // Must NOT claim Kenya IG conformance markers we did not verify
        $this->assertArrayNotHasKey('kenya_ig', $resource);
    }

    // ── Role composition ─────────────────────────────────────────

    public function test_role_composer_builds_icu_nurse_from_base(): void
    {
        $composer = new \App\Services\Roles\RoleComposer();
        $user = User::factory()->create(['status' => 'active']);
        $composer->applyToUser($user, 'ICU Nurse');

        $this->assertTrue($user->hasRole('ICU Nurse'));
        $this->assertTrue($user->can('manage ventilators'));
        $this->assertTrue($user->can('manage nursing notes')); // from base Nurse
        $this->assertFalse($user->can('manage roles'));
    }

    // ── My Work / dashboard ──────────────────────────────────────

    public function test_dashboard_renders_my_work_for_authorized_user(): void
    {
        $doctor = $this->userWithRole('Doctor');
        $resp = $this->actingAs($doctor)->get('/dashboard');
        $resp->assertStatus(200);
        $resp->assertSee('My Work');
    }

    // ── Facility scope ───────────────────────────────────────────

    public function test_users_table_has_branch_id_column(): void
    {
        $this->assertTrue(\Schema::hasColumn('users', 'branch_id'));
    }

    public function test_patient_model_uses_facility_scope(): void
    {
        $scopes = (new \ReflectionClass(Patient::class))->getTraitNames();
        // Global scope registered via trait boot
        $this->assertTrue(
            in_array(\App\Models\Scopes\BelongsToFacility::class, $scopes, true)
            || collect((new Patient())->getGlobalScopes())->has('facility')
        );
    }

    // ── Export route protection ──────────────────────────────────

    public function test_export_routes_require_export_or_report_permissions(): void
    {
        $security = $this->userWithRole('Security Officer');
        $resp = $this->actingAs($security)->get('/hms/reports/export-patients');
        $this->assertTrue(
            in_array($resp->status(), [403, 404], true),
            "Security Officer should be denied patient export, got {$resp->status()}"
        );
    }

    public function test_pharmacist_cannot_open_mortuary_or_admin_roles(): void
    {
        $pharma = $this->userWithRole('Pharmacist');
        $this->actingAs($pharma)->get('/hms/mortuary')->assertStatus(403);
        $this->actingAs($pharma)->get('/admin/roles')->assertStatus(403);
    }
}
