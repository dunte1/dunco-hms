<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Reset the permission cache before each test
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->seed(\Database\Seeders\SidebarPermissionsSeeder::class);
        $this->seed(\Database\Seeders\GrantAdminAllPermissions::class);
    }

    public function test_all_32_roles_exist_in_the_database(): void
    {
        $expectedRoles = [
            'Super Admin', 'Hospital Admin', 'Doctor', 'Nurse',
            'Receptionist', 'Pharmacist', 'Lab Technician', 'Radiologist',
            'Accountant', 'Case Handler', 'Ambulance Operator', 'HR Officer',
            'Patient', 'System Auditor', 'Support Staff', 'Telemedicine Doctor',
            'Inventory Manager', 'Procurement Officer', 'IT Support',
            'Marketing Manager', 'System AI Bot', 'Maternity Nurse',
            'ICU Nurse', 'Theatre Nurse', 'CSSD Technician',
            'Mortuary Attendant', 'Security Officer', 'Quality Officer',
            'Biomedical Engineer', 'Dietitian', 'Social Worker',
            'Mental Health Professional',
        ];

        $this->assertCount(32, $expectedRoles);

        foreach ($expectedRoles as $roleName) {
            $this->assertDatabaseHas('roles', ['name' => $roleName]);
        }

        $this->assertEquals(32, Role::count());
    }

    public function test_raise_it_tickets_permission_exists(): void
    {
        $this->assertDatabaseHas('permissions', ['name' => 'raise it tickets']);
        $this->assertTrue(Permission::where('name', 'raise it tickets')->exists());
    }

    public function test_manage_it_tickets_permission_exists(): void
    {
        $this->assertDatabaseHas('permissions', ['name' => 'manage it tickets']);
        $this->assertTrue(Permission::where('name', 'manage it tickets')->exists());
    }

    public function test_raise_it_tickets_assigned_to_expected_roles(): void
    {
        $expectedRoles = [
            'Doctor', 'Nurse', 'Receptionist', 'Pharmacist',
            'Lab Technician', 'Radiologist', 'Accountant', 'Case Handler',
            'Ambulance Operator', 'HR Officer', 'Patient', 'Support Staff',
            'Telemedicine Doctor', 'Inventory Manager', 'Procurement Officer',
            'IT Support', 'Marketing Manager', 'Maternity Nurse',
            'ICU Nurse', 'Theatre Nurse', 'CSSD Technician',
            'Mortuary Attendant', 'Security Officer', 'Quality Officer',
            'Biomedical Engineer', 'Dietitian', 'Social Worker',
            'Mental Health Professional',
        ];

        $permission = Permission::where('name', 'raise it tickets')->first();
        $assignedRoles = $permission->roles->pluck('name')->toArray();

        foreach ($expectedRoles as $roleName) {
            $this->assertContains(
                $roleName,
                $assignedRoles,
                "Role '{$roleName}' should have 'raise it tickets' permission"
            );
        }
    }

    public function test_manage_it_tickets_assigned_to_super_admin_hospital_admin_it_support(): void
    {
        $expectedRoles = ['Super Admin', 'Hospital Admin', 'IT Support'];

        $permission = Permission::where('name', 'manage it tickets')->first();
        $assignedRoles = $permission->roles->pluck('name')->toArray();

        foreach ($expectedRoles as $roleName) {
            $this->assertContains(
                $roleName,
                $assignedRoles,
                "Role '{$roleName}' should have 'manage it tickets' permission"
            );
        }
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $totalPermissions = Permission::count();
        $superAdmin = Role::where('name', 'Super Admin')->first();

        $this->assertNotNull($superAdmin, 'Super Admin role should exist');
        $this->assertEquals(
            $totalPermissions,
            $superAdmin->permissions->count(),
            "Super Admin should have all {$totalPermissions} permissions"
        );
    }

    public function test_user_with_no_role_has_zero_permissions(): void
    {
        $user = User::factory()->create();
        $user->syncRoles([]);

        $this->assertEmpty($user->roles);
        $this->assertEquals(0, $user->getAllPermissions()->count());
    }

    public function test_each_role_permissions_accessible_via_user_can_method(): void
    {
        $roles = Role::with('permissions')->get();

        foreach ($roles as $role) {
            $user = User::factory()->create();
            $user->syncRoles([$role->name]);

            foreach ($role->permissions as $permission) {
                $this->assertTrue(
                    $user->can($permission->name),
                    "User with role '{$role->name}' should be able to '{$permission->name}'"
                );
            }
        }
    }

    public function test_total_permission_count(): void
    {
        $this->assertGreaterThanOrEqual(346, Permission::count());
    }

    public function test_hospital_admin_has_manage_it_tickets(): void
    {
        $hospitalAdmin = Role::where('name', 'Hospital Admin')->first();
        $this->assertTrue($hospitalAdmin->hasPermissionTo('manage it tickets'));
    }

    public function test_it_support_has_manage_it_tickets(): void
    {
        $itSupport = Role::where('name', 'IT Support')->first();
        $this->assertTrue($itSupport->hasPermissionTo('manage it tickets'));
    }
}
