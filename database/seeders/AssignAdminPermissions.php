<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignAdminPermissions extends Seeder
{
    public function run()
    {
        // Get or create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        
        // Give Super Admin role all permissions
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);
        
        // Assign Super Admin role to admin@example.com
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            // Remove any old roles first
            $admin->syncRoles(['Super Admin']);
            $this->command->info('✅ Super Admin role with all permissions assigned to admin@example.com');
            $this->command->info('Total permissions: ' . $allPermissions->count());
        } else {
            $this->command->error('❌ Admin user not found');
        }
    }
}

