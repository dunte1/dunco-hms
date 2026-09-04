<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

app()[PermissionRegistrar::class]->forgetCachedPermissions();

echo "FIXING PERMISSIONS...\n\n";

// 1. Add missing permissions
$missingPerms = [
    'view doctors' => 'View doctor profiles and schedules',
    'manage nurses' => 'Manage nurse profiles and assignments',
    'manage ambulances' => 'Manage ambulance fleet and calls',
    'manage case handlers' => 'Manage case handler staff',
    'manage blood bank' => 'Manage blood bank operations',
];

foreach ($missingPerms as $name => $desc) {
    $perm = Permission::firstOrCreate(
        ['name' => $name, 'guard_name' => 'web'],
        ['description' => $desc]
    );
    echo "  Created/verified permission: $name\n";
}

// 2. Assign missing permissions to Super Admin
$superAdmin = Role::where('name', 'Super Admin')->first();
if ($superAdmin) {
    $allPerms = Permission::all()->pluck('name')->toArray();
    $superAdmin->syncPermissions($allPerms);
    echo "\n  Super Admin now has " . count($allPerms) . " permissions\n";
}

// 3. Assign missing permissions to Hospital Admin
$hospitalAdmin = Role::where('name', 'Hospital Admin')->first();
if ($hospitalAdmin) {
    $hospitalAdmin->givePermissionTo(['view doctors', 'manage nurses', 'manage ambulances', 'manage case handlers', 'manage blood bank']);
    echo "  Hospital Admin updated\n";
}

// 4. Assign 'view doctors' to Doctor and Nurse roles
$doctor = Role::where('name', 'Doctor')->first();
if ($doctor) {
    $doctor->givePermissionTo(['view doctors']);
    echo "  Doctor role updated\n";
}

$nurse = Role::where('name', 'Nurse')->first();
if ($nurse) {
    $nurse->givePermissionTo(['view doctors', 'manage nurses']);
    echo "  Nurse role updated\n";
}

// 5. Assign 'manage blood bank' to relevant roles
foreach (['Pharmacist', 'Lab Technician'] as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        $role->givePermissionTo(['manage blood bank']);
        echo "  $roleName role updated\n";
    }
}

// 6. Assign 'manage ambulances' to relevant roles
$ambulanceRole = Role::where('name', 'Ambulance Operator')->first();
if ($ambulanceRole) {
    $ambulanceRole->givePermissionTo(['manage ambulances']);
    echo "  Ambulance Operator role updated\n";
}

// 7. Remove duplicate empty SuperAdmin role (no permissions)
$emptySuperAdmin = Role::where('name', 'SuperAdmin')->first();
if ($emptySuperAdmin && $emptySuperAdmin->permissions->isEmpty()) {
    // Move any users with this role to Super Admin
    $usersWithRole = \App\Models\User::role('SuperAdmin')->get();
    foreach ($usersWithRole as $u) {
        $u->removeRole('SuperAdmin');
        $u->assignRole('Super Admin');
    }
    $emptySuperAdmin->delete();
    echo "\n  Removed duplicate empty 'SuperAdmin' role\n";
}

app()[PermissionRegistrar::class]->forgetCachedPermissions();

echo "\nDONE!\n\n";

// Verify
echo "FINAL PERMISSION COUNTS:\n";
$roles = Role::withCount('permissions')->get();
foreach ($roles as $role) {
    echo "  {$role->name}: {$role->permissions_count} permissions\n";
}
