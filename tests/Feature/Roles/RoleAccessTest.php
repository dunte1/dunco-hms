<?php

namespace Tests\Feature\Roles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_all_primary_roles_exist(): void
    {
        $roles = [
            'Super Admin', 'Hospital Admin', 'Doctor', 'Nurse',
            'Receptionist', 'Pharmacist', 'Lab Technician', 'Accountant', 'Patient',
        ];

        foreach ($roles as $role) {
            $this->assertTrue(Role::where('name', $role)->exists(), "Role {$role} should exist");
        }
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = $this->makeUserWithRole('Super Admin');

        $this->assertTrue($user->can('view patients'));
        $this->assertTrue($user->can('create prescriptions'));
        $this->assertTrue($user->can('enter test results'));
        $this->assertTrue($user->can('view invoices'));
        $this->assertTrue($user->can('manage employees'));
        $this->assertTrue($user->can('view analytics'));
    }

    public function test_doctor_can_prescribe_but_not_manage_employees(): void
    {
        $user = $this->makeUserWithRole('Doctor');

        $this->assertTrue($user->can('create prescriptions'));
        $this->assertTrue($user->can('view patients'));
        $this->assertTrue($user->can('add test requests'));
        $this->assertFalse($user->can('manage employees'));
        $this->assertFalse($user->can('create invoices'));
    }

    public function test_receptionist_can_register_patients_but_not_dispense(): void
    {
        $user = $this->makeUserWithRole('Receptionist');

        $this->assertTrue($user->can('add patients'));
        $this->assertTrue($user->can('view patients'));
        $this->assertTrue($user->can('create appointments'));
        $this->assertFalse($user->can('dispense medicines'));
        $this->assertFalse($user->can('enter test results'));
    }

    public function test_pharmacist_can_dispense_but_not_enter_lab_results(): void
    {
        $user = $this->makeUserWithRole('Pharmacist');

        $this->assertTrue($user->can('dispense medicines'));
        $this->assertTrue($user->can('view prescriptions'));
        $this->assertFalse($user->can('enter test results'));
        $this->assertFalse($user->can('create prescriptions'));
    }

    public function test_lab_technician_can_enter_results_but_not_dispense(): void
    {
        $user = $this->makeUserWithRole('Lab Technician');

        $this->assertTrue($user->can('enter test results'));
        $this->assertTrue($user->can('view test results'));
        $this->assertFalse($user->can('dispense medicines'));
        $this->assertFalse($user->can('create invoices'));
    }

    public function test_accountant_can_view_invoices_but_not_prescribe(): void
    {
        $user = $this->makeUserWithRole('Accountant');

        $this->assertTrue($user->can('view invoices'));
        $this->assertTrue($user->can('add payments'));
        $this->assertFalse($user->can('create prescriptions'));
        $this->assertFalse($user->can('dispense medicines'));
    }

    public function test_nurse_can_triage_but_not_dispense(): void
    {
        $user = $this->makeUserWithRole('Nurse');

        $this->assertTrue($user->can('manage triage records'));
        $this->assertTrue($user->can('manage queue'));
        $this->assertFalse($user->can('dispense medicines'));
        $this->assertFalse($user->can('create invoices'));
    }

    public function test_patient_role_has_no_staff_permissions(): void
    {
        $user = $this->makeUserWithRole('Patient');

        $this->assertFalse($user->can('view patients'));
        $this->assertFalse($user->can('create prescriptions'));
        $this->assertFalse($user->can('manage employees'));
    }

    public function test_permission_middleware_blocks_unauthorized_access(): void
    {
        $user = $this->makeUserWithRole('Patient');
        $this->actingAs($user);

        $response = $this->get('/hms/hr/employees');
        $response->assertForbidden();
    }

    public function test_hospital_admin_can_view_billing_but_not_super_admin_settings(): void
    {
        $user = $this->makeUserWithRole('Hospital Admin');

        $this->assertTrue($user->can('view invoices') || $user->can('view billing'));
        // Hospital Admin should not have all Super Admin permissions
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $hospitalAdmin = Role::where('name', 'Hospital Admin')->first();

        $superCount = $superAdmin->permissions()->count();
        $adminCount = $hospitalAdmin->permissions()->count();

        $this->assertLessThan($superCount, $adminCount, 'Hospital Admin should have fewer permissions than Super Admin');
    }

    public function test_user_with_no_role_denied_protected_pages(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        // No role assigned

        $this->actingAs($user);
        $response = $this->get('/hms/hr/employees');
        $response->assertForbidden();
    }

    public function test_sidebar_service_filters_by_permission(): void
    {
        $user = $this->makeUserWithRole('Doctor');
        $this->actingAs($user);

        $sections = app(\App\Services\SidebarService::class)->sections();
        $keys = array_column($sections, 'key');

        $this->assertContains('clinical', $keys, 'Doctor should see clinical section');
        $this->assertNotContains('administration', $keys, 'Doctor should not see administration');
    }

    public function test_receptionist_sidebar_hides_administration(): void
    {
        $user = $this->makeUserWithRole('Receptionist');
        $this->actingAs($user);

        $sections = app(\App\Services\SidebarService::class)->sections();
        $keys = array_column($sections, 'key');

        $this->assertContains('clinical', $keys);
        $this->assertNotContains('administration', $keys);
    }
}
