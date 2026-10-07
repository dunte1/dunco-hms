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
        
        // Assign Super Admin role to admin accounts
        $adminEmails = ['dunthecan02@gmail.com', 'admin@duncohms.com', 'admin@example.com', 'admin@hospital.com'];
        $admins = User::whereIn('email', $adminEmails)->get();

        if ($admins->isEmpty()) {
            $admins = User::whereHas('roles', fn ($q) => $q->where('name', 'Super Admin'))->get();
        }

        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                $admin->syncRoles(['Super Admin']);
            }
            $this->command->info('✅ Super Admin role with all permissions assigned to: ' . $admins->pluck('email')->implode(', '));
            $this->command->info('Total permissions: ' . $allPermissions->count());
        } else {
            $this->command->error('❌ Admin user not found');
        }
    }
}

