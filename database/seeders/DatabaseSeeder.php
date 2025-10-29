<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin'], ['label' => 'Super Administrator']);

        $workflow = json_decode(file_get_contents(base_path('workflow.json')), true);
        $permissions = $workflow['rbac']['default_permissions'] ?? [];
        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate(['name' => $permName], ['label' => null]);
            $superAdmin->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $user->roles()->syncWithoutDetaching([$superAdmin->id]);
    }
}
