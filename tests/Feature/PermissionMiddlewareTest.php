<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    private function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole($roleName);
        return $user;
    }

    private function createUserWithoutPermission(): User
    {
        $user = User::factory()->create(['status' => 'active']);
        // Patient role has limited permissions — no 'add patients'
        $user->assignRole('Patient');
        return $user;
    }

    // ── Unauthenticated access ───────────────────────────────────────

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/hms/patients');
        $response->assertRedirect(route('login'));
    }

    // ── Patient routes: permission:view patients|add patients|edit patients ─────────

    public function test_user_with_view_patients_can_access_patient_index(): void
    {
        $user = $this->createUserWithRole('Receptionist'); // has 'view patients'
        $response = $this->actingAs($user)->get('/hms/patients');
        $response->assertStatus(200);
    }

    public function test_user_without_patient_permission_gets_403(): void
    {
        // Create user with no role — should have no permissions
        $user = User::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->post('/hms/patients', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'dob' => '1990-01-01',
            'gender' => 'male',
            'phone' => '0700000000',
        ]);
        $response->assertStatus(403);
    }

    public function test_doctor_can_access_patient_index(): void
    {
        $user = $this->createUserWithRole('Doctor'); // has 'view patients'
        $response = $this->actingAs($user)->get('/hms/patients');
        $response->assertStatus(200);
    }

    // ── Appointment routes: permission:create appointments|manage appointments ──────

    public function test_receptionist_can_access_appointments(): void
    {
        $user = $this->createUserWithRole('Receptionist'); // has 'create appointments'
        $response = $this->actingAs($user)->get('/hms/appointments');
        $response->assertStatus(200);
    }

    public function test_pharmacist_cannot_access_appointments(): void
    {
        $user = $this->createUserWithRole('Pharmacist'); // no appointment permissions
        $response = $this->actingAs($user)->get('/hms/appointments');
        $response->assertStatus(403);
    }

    // ── IPD routes: permission:admit patients|manage admissions|view patients ───────

    public function test_nurse_can_access_ipd(): void
    {
        $user = $this->createUserWithRole('Nurse'); // has 'admit patients'
        $response = $this->actingAs($user)->get('/hms/ipd');
        $response->assertStatus(200);
    }

    public function test_user_with_no_role_gets_403_on_ipd(): void
    {
        // Create user with no role — should have no permissions
        $user = User::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->get('/hms/ipd');
        $response->assertStatus(403);
    }

    // ── Queue routes: permission:create appointments|manage appointments ────────────

    public function test_receptionist_can_access_queue(): void
    {
        $user = $this->createUserWithRole('Receptionist');
        $response = $this->actingAs($user)->get('/hms/queue');
        $response->assertStatus(200);
    }

    // ── Triage routes: permission:manage patient vitals|view patients ──────────────

    public function test_nurse_can_access_triage(): void
    {
        $user = $this->createUserWithRole('Nurse'); // has 'manage patient vitals'
        $response = $this->actingAs($user)->get('/hms/triage');
        $response->assertStatus(200);
    }

    public function test_user_with_no_role_gets_403_on_triage(): void
    {
        // Create user with no role — should have no permissions
        $user = User::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->get('/hms/triage');
        $response->assertStatus(403);
    }

    // ── Admin roles: permission:manage roles|manage permissions ────────────────────

    public function test_super_admin_can_access_roles(): void
    {
        $user = $this->createUserWithRole('Super Admin'); // has 'manage roles'
        $response = $this->actingAs($user)->get('/admin/roles');
        $response->assertStatus(200);
    }

    public function test_doctor_cannot_access_roles(): void
    {
        $user = $this->createUserWithRole('Doctor'); // no 'manage roles'
        $response = $this->actingAs($user)->get('/admin/roles');
        $response->assertStatus(403);
    }

    // ── Super Admin can access everything ──────────────────────────────────────────

    public function test_super_admin_can_access_all_protected_routes(): void
    {
        $user = $this->createUserWithRole('Super Admin');

        $routes = [
            '/hms/patients',
            '/hms/appointments',
            '/hms/ipd',
            '/hms/queue',
            '/hms/triage',
            '/admin/roles',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200, "Super Admin should access {$route}");
        }
    }

    // ── Multiple permissions (OR logic) ────────────────────────────────────────────

    public function test_user_with_any_of_multiple_permissions_is_granted(): void
    {
        // Doctor has 'view patients' but not 'add patients'
        // The middleware accepts 'view patients|add patients|edit patients'
        $user = $this->createUserWithRole('Doctor');
        $response = $this->actingAs($user)->get('/hms/patients');
        $response->assertStatus(200);
    }

    public function test_user_with_no_role_is_denied(): void
    {
        // Create a user with NO role — should have zero permissions
        $user = User::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->post('/hms/patients', [
            'first_name' => 'Test',
            'last_name' => 'Denied',
            'dob' => '1990-01-01',
            'gender' => 'male',
            'phone' => '0700000000',
        ]);
        $response->assertStatus(403);
    }

    // ── API routes (Sanctum) still work independently ──────────────────────────────

    public function test_api_routes_use_sanctum_not_permission_middleware(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/patients');

        // API uses Sanctum auth, not the web permission middleware
        $response->assertStatus(200);
    }

    // ── Dashboard requires auth ────────────────────────────────────────────────────

    public function test_dashboard_requires_auth(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = $this->createUserWithRole('Support Staff');
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }
}
