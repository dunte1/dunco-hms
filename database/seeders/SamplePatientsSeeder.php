<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SamplePatientsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Create 50 sample patients
        for ($i = 1; $i <= 50; $i++) {
            $gender = $faker->randomElement(['male', 'female', 'other']);
            $firstName = $faker->firstName($gender == 'other' ? null : $gender);
            $lastName = $faker->lastName();
            
            Patient::create([
                'patient_no' => 'PAT-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $faker->optional(0.8)->email(),
                'phone' => $faker->optional(0.9)->phoneNumber(),
                'dob' => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'gender' => $gender,
                'address' => $faker->optional(0.7)->address(),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ Successfully created 50 sample patients');
        
        // Display statistics
        $this->command->info("\n📊 Patient Statistics:");
        $this->command->info('Total Patients: ' . Patient::count());
        $this->command->info('Male Patients: ' . Patient::where('gender', 'male')->count());
        $this->command->info('Female Patients: ' . Patient::where('gender', 'female')->count());
        $this->command->info('Other Gender: ' . Patient::where('gender', 'other')->count());
        $this->command->info('Registered Today: ' . Patient::whereDate('created_at', today())->count());
    }
}
