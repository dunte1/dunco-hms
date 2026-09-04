<?php
$dir = '/home/duncoweb/hmse.duncowebsolutions.co.ke';
require $dir . '/vendor/autoload.php';
$app = require_once $dir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "========================================\n";
echo "  ROLE & PERMISSION AUDIT\n";
echo "========================================\n\n";

// 1. List all roles and their permission counts
echo "ROLES AND PERMISSIONS:\n";
echo str_repeat('-', 60) . "\n";
$roles = Role::withCount('permissions')->get();
foreach ($roles as $role) {
    echo sprintf("  %-25s %d permissions\n", $role->name, $role->permissions_count);
}

echo "\n";
echo str_repeat('-', 60) . "\n\n";

// 2. For each user, show their role and permission count
echo "USER ROLES:\n";
echo str_repeat('-', 60) . "\n";
$users = User::with('roles')->get();
foreach ($users as $u) {
    $roleNames = $u->roles->pluck('name')->implode(', ') ?: 'none';
    $permCount = $u->getAllPermissions()->count();
    echo sprintf("  %-35s %-20s (%d perms)\n", $u->email, $roleNames, $permCount);
}

echo "\n";
echo str_repeat('-', 60) . "\n\n";

// 3. Show permissions per role
echo "PERMISSIONS BY ROLE:\n\n";
foreach ($roles as $role) {
    $role->load('permissions');
    echo strtoupper($role->name) . ":\n";
    foreach ($role->permissions as $perm) {
        echo "  - {$perm->name}\n";
    }
    echo "\n";
}

// 4. Check sidebar permissions
echo "========================================\n";
echo "  SIDEBAR PERMISSION CHECKS\n";
echo "========================================\n\n";

// Get all @can directives from sidebar
$sidebarPath = $dir . '/resources/views/partials/sidebar.blade.php';
$sidebar = file_get_contents($sidebarPath);
preg_match_all('/@can\([\'"]([^\'"]+)[\'"]\)/', $sidebar, $matches);
$requiredPermissions = array_unique($matches[1]);

echo "Permissions required by sidebar (" . count($requiredPermissions) . "):\n";
foreach ($requiredPermissions as $perm) {
    $exists = Permission::where('name', $perm)->exists();
    echo "  " . ($exists ? "OK" : "MISSING") . "  $perm\n";
}

echo "\n";

// 5. Check which sidebar items each role can see
echo "========================================\n";
echo "  SIDEBAR VISIBILITY BY ROLE\n";
echo "========================================\n\n";

$importantRoles = ['Super Admin', 'Hospital Admin', 'Doctor', 'Nurse', 'Receptionist', 'Pharmacist', 'Lab Technician', 'Accountant', 'HR Officer', 'Patient'];

foreach ($importantRoles as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if (!$role) continue;
    
    $role->load('permissions');
    $rolePerms = $role->permissions->pluck('name')->toArray();
    
    echo strtoupper($roleName) . ":\n";
    foreach ($requiredPermissions as $perm) {
        $has = in_array($perm, $rolePerms);
        echo "  " . ($has ? "  YES" : "   NO") . "  $perm\n";
    }
    echo "\n";
}
