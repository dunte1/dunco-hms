<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function tearDown(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        parent::tearDown();
    }

    /**
     * Seed a minimal role/permission set used by feature tests.
     */
    protected function seedRolesAndPermissions(): void
    {
        $permissions = [
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'view appointments', 'create appointments', 'manage appointments',
            'manage queue', 'manage triage records',
            'create prescriptions', 'view prescriptions', 'dispense medicines',
            'add test requests', 'view test results', 'enter test results',
            'view invoices', 'create invoices', 'add payments', 'view billing',
            'view dashboard analytics', 'view analytics', 'export reports',
            'manage employees', 'view reports',
            'manage immunization schedules',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        $roleMap = [
            'Super Admin' => $permissions,
            'Hospital Admin' => [
                'view patients', 'add patients', 'edit patients',
                'view appointments', 'create appointments', 'manage appointments',
                'manage queue', 'manage triage records',
                'view prescriptions', 'view invoices', 'view billing',
                'view dashboard analytics', 'view analytics', 'export reports',
                'view reports', 'manage immunization schedules',
            ],
            'Doctor' => [
                'view patients', 'view appointments', 'manage queue',
                'create prescriptions', 'view prescriptions',
                'add test requests', 'view test results',
                'view dashboard analytics',
            ],
            'Nurse' => [
                'view patients', 'manage queue', 'manage triage records',
                'view dashboard analytics',
            ],
            'Receptionist' => [
                'view patients', 'add patients', 'edit patients',
                'view appointments', 'create appointments',
                'view dashboard analytics',
            ],
            'Pharmacist' => [
                'view prescriptions', 'dispense medicines', 'view patients',
                'view dashboard analytics',
            ],
            'Lab Technician' => [
                'add test requests', 'view test results', 'enter test results',
                'view patients', 'view dashboard analytics',
            ],
            'Accountant' => [
                'view invoices', 'create invoices', 'add payments', 'view billing',
                'view dashboard analytics', 'export reports',
            ],
            'Patient' => [],
        ];

        foreach ($roleMap as $roleName => $perms) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($perms);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Create a user with a role and return them.
     */
    protected function makeUserWithRole(string $roleName, array $attributes = [])
    {
        $user = \App\Models\User::factory()->create($attributes);
        $user->syncRoles([$roleName]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user;
    }

    /**
     * Create a medicine with all required NOT NULL columns.
     */
    protected function makeMedicine(array $overrides = [])
    {
        $category = \App\Models\MedicineCategory::firstOrCreate(['name' => 'General']);

        return \App\Models\Medicine::create(array_merge([
            'name' => 'Test Medicine ' . uniqid(),
            'category_id' => $category->id,
            'dosage_form' => 'tablet',
            'strength' => '500mg',
            'unit_price' => 100,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
            'expiry_date' => now()->addYear()->toDateString(),
        ], $overrides));
    }

    /**
     * Create a lab test with all required NOT NULL columns.
     */
    protected function makeLabTest(array $overrides = [])
    {
        return \App\Models\LabTest::create(array_merge([
            'test_name' => 'Test Panel ' . uniqid(),
            'price' => 500,
            'is_active' => true,
        ], $overrides));
    }

    /**
     * Assert a response is HTML (not JSON).
     */
    protected function assertIsHtmlPage($response, string $message = ''): void
    {
        $content = $response->getContent();
        $this->assertFalse(
            str_starts_with(trim($content), '{') || str_starts_with(trim($content), '['),
            $message ?: 'Response should not be JSON'
        );
        $this->assertTrue(
            str_contains($content, '<html') || str_contains($content, '<!DOCTYPE'),
            $message ?: 'Response should be an HTML page'
        );
    }
}
