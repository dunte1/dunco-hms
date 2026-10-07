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
        
        // Only the Super Admin account gets Super Admin (all permissions)
        $superAdminEmails = ['dunthecan02@gmail.com'];
        $admins = User::whereIn('email', $superAdminEmails)->get();

        // Remove Super Admin from any other users so only one Super Admin exists
        User::whereHas('roles', fn ($q) => $q->where('name', 'Super Admin'))
            ->whereNotIn('email', $superAdminEmails)
            ->get()
            ->each(function ($user) use ($admins) {
                $user->syncRoles($user->roles->where('name', '!=', 'Super Admin')->pluck('name')->all());
            });

        if ($admins->isEmpty()) {
            $this->command->error('❌ Super Admin user not found (expected dunthecan02@gmail.com)');
            return;
        }

        foreach ($admins as $admin) {
            $admin->syncRoles(['Super Admin']);
        }

        $this->command->info('✅ Super Admin role with all permissions assigned to: ' . $admins->pluck('email')->implode(', '));
        $this->command->info('Total permissions: ' . $allPermissions->count());
    }
}

