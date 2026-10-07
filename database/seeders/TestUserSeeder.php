<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with different roles
        // ONE Super Admin only; Hospital Admin is a separate limited role
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'dunthecan02@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Hospital Admin',
                'email' => 'admin@duncohms.com',
                'password' => bcrypt('password'),
                'role' => 'Hospital Admin'
            ],
            [
                'name' => 'Dr. John Smith',
                'email' => 'doctor@duncohms.com',
                'password' => bcrypt('password'),
                'role' => 'Doctor'
            ],
            [
                'name' => 'Nurse Jane Doe',
                'email' => 'nurse@duncohms.com',
                'password' => bcrypt('password'),
                'role' => 'Nurse'
            ],
            [
                'name' => 'Receptionist Mike',
                'email' => 'receptionist@duncohms.com',
                'password' => bcrypt('password'),
                'role' => 'Receptionist'
            ],
            [
                'name' => 'Patient Test',
                'email' => 'patient@duncohms.com',
                'password' => bcrypt('password'),
                'role' => 'Patient'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );

            // Only Super Admin account gets ALL permissions
            if ($userData['role'] === 'Super Admin') {
                $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
                $role->syncPermissions(Permission::all());
                $user->syncRoles(['Super Admin']);
                continue;
            }

            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                $user->syncRoles([$userData['role']]);
            }
        }

        echo "Test users created successfully!\n";
        echo "Login credentials:\n";
        echo "- Super Admin (ALL access): dunthecan02@gmail.com / password\n";
        echo "- Hospital Admin (ops only): admin@duncohms.com / password\n";
        echo "- Doctor: doctor@duncohms.com / password\n";
        echo "- Nurse: nurse@duncohms.com / password\n";
        echo "- Receptionist: receptionist@duncohms.com / password\n";
        echo "- Patient: patient@duncohms.com / password\n";
    }
}
