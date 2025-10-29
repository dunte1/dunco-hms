<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AssignUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Sample staff data
        $staff = [
            // Doctors
            ['name' => 'Dr. John Smith', 'email' => 'dr.john@hospital.com', 'role' => 'Doctor'],
            ['name' => 'Dr. Sarah Johnson', 'email' => 'dr.sarah@hospital.com', 'role' => 'Doctor'],
            ['name' => 'Dr. Michael Brown', 'email' => 'dr.michael@hospital.com', 'role' => 'Doctor'],
            ['name' => 'Dr. Emily Davis', 'email' => 'dr.emily@hospital.com', 'role' => 'Doctor'],
            
            // Nurses
            ['name' => 'Nurse Maria Garcia', 'email' => 'nurse.maria@hospital.com', 'role' => 'Nurse'],
            ['name' => 'Nurse Jennifer Wilson', 'email' => 'nurse.jennifer@hospital.com', 'role' => 'Nurse'],
            ['name' => 'Nurse Patricia Martinez', 'email' => 'nurse.patricia@hospital.com', 'role' => 'Nurse'],
            ['name' => 'Nurse Linda Rodriguez', 'email' => 'nurse.linda@hospital.com', 'role' => 'Nurse'],
            ['name' => 'Nurse Barbara Hernandez', 'email' => 'nurse.barbara@hospital.com', 'role' => 'Nurse'],
            
            // Receptionists
            ['name' => 'Emma Thompson', 'email' => 'emma.reception@hospital.com', 'role' => 'Receptionist'],
            ['name' => 'Olivia Anderson', 'email' => 'olivia.reception@hospital.com', 'role' => 'Receptionist'],
            ['name' => 'Sophia Taylor', 'email' => 'sophia.reception@hospital.com', 'role' => 'Receptionist'],
            
            // Lab Technicians
            ['name' => 'James White', 'email' => 'james.lab@hospital.com', 'role' => 'Lab Technician'],
            ['name' => 'Robert Harris', 'email' => 'robert.lab@hospital.com', 'role' => 'Lab Technician'],
            ['name' => 'William Clark', 'email' => 'william.lab@hospital.com', 'role' => 'Lab Technician'],
            
            // Pharmacists
            ['name' => 'David Lewis', 'email' => 'david.pharmacy@hospital.com', 'role' => 'Pharmacist'],
            ['name' => 'Richard Lee', 'email' => 'richard.pharmacy@hospital.com', 'role' => 'Pharmacist'],
            ['name' => 'Joseph Walker', 'email' => 'joseph.pharmacy@hospital.com', 'role' => 'Pharmacist'],
            
            // Accountants
            ['name' => 'Charles Hall', 'email' => 'charles.accounting@hospital.com', 'role' => 'Accountant'],
            ['name' => 'Thomas Allen', 'email' => 'thomas.accounting@hospital.com', 'role' => 'Accountant'],
            
            // HR Officers
            ['name' => 'Christopher Young', 'email' => 'christopher.hr@hospital.com', 'role' => 'HR Officer'],
        ];

        $defaultPassword = Hash::make('password123');

        foreach ($staff as $staffMember) {
            // Create or update user
            $user = User::firstOrCreate(
                ['email' => $staffMember['email']],
                [
                    'name' => $staffMember['name'],
                    'password' => $defaultPassword,
                ]
            );

            // Assign role
            $role = Role::where('name', $staffMember['role'])->first();
            if ($role) {
                $user->syncRoles([$staffMember['role']]);
            }
        }

        $this->command->info('✅ Successfully created/updated ' . count($staff) . ' staff members');
        $this->command->info('Default password for all staff: password123');
        
        // Display role breakdown
        $this->command->info("\n📊 Staff by Role:");
        $this->command->info('Doctors: ' . User::role('Doctor')->count());
        $this->command->info('Nurses: ' . User::role('Nurse')->count());
        $this->command->info('Receptionists: ' . User::role('Receptionist')->count());
        $this->command->info('Lab Technicians: ' . User::role('Lab Technician')->count());
        $this->command->info('Pharmacists: ' . User::role('Pharmacist')->count());
        $this->command->info('Accountants: ' . User::role('Accountant')->count());
        $this->command->info('HR Officers: ' . User::role('HR Officer')->count());
    }
}
