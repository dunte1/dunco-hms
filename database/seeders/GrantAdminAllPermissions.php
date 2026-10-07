<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GrantAdminAllPermissions extends Seeder
{
    /**
     * Run the database seeds to grant all permissions to admin user
     */
    public function run(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔍 Granting all permissions to admin user...');

        // Step 1: Ensure Super Admin role exists
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web']
        );
        $this->command->info('✅ Super Admin role found/created');

        // Step 2: Assign ALL permissions to Super Admin role
        $allPermissions = Permission::all();
        if ($allPermissions->isEmpty()) {
            $this->command->error('❌ No permissions found. Please run RolesAndPermissionsSeeder first.');
            return;
        }
        
        $superAdminRole->syncPermissions($allPermissions);
        $this->command->info('✅ Super Admin role now has ' . $allPermissions->count() . ' permissions');

        // Step 3: Only dunthecan02@gmail.com is Super Admin
        $superAdminEmails = ['dunthecan02@gmail.com'];

        // Remove Super Admin from everyone else
        User::whereHas('roles', fn ($q) => $q->where('name', 'Super Admin'))
            ->whereNotIn('email', $superAdminEmails)
            ->get()
            ->each(function ($user) {
                $remaining = $user->roles->where('name', '!=', 'Super Admin')->pluck('name')->all();
                $user->syncRoles($remaining);
            });

        $adminFound = false;
        foreach ($superAdminEmails as $email) {
            $admin = User::where('email', $email)->first();

            if ($admin) {
                $admin->syncRoles(['Super Admin']);
                $admin->save();

                $this->command->info('✅ Super Admin role assigned to: ' . $email);
                $this->command->info('   User: ' . $admin->name);
                $this->command->info('   Total permissions: ' . $allPermissions->count());
                $adminFound = true;
                break;
            }
        }

        if (!$adminFound) {
            $this->command->error('❌ Super Admin user not found: dunthecan02@gmail.com');
        }

        // Clear cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $this->command->info('');
        $this->command->info('✅ Permission setup complete!');
        $this->command->info('👤 Please log out and log back in to see all menu groups.');
    }
}

