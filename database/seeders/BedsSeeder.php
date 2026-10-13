<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\BedType;
use Illuminate\Database\Seeder;

class BedsSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'General Ward', 'charge_per_day' => 2000, 'description' => 'Standard general ward bed'],
            ['name' => 'Private', 'charge_per_day' => 5000, 'description' => 'Private room bed'],
            ['name' => 'Semi-Private', 'charge_per_day' => 3500, 'description' => 'Semi-private room bed'],
            ['name' => 'ICU', 'charge_per_day' => 10000, 'description' => 'Intensive Care Unit bed'],
            ['name' => 'HDU', 'charge_per_day' => 7000, 'description' => 'High Dependency Unit bed'],
            ['name' => 'Maternity', 'charge_per_day' => 3000, 'description' => 'Maternity ward bed'],
            ['name' => 'Isolation', 'charge_per_day' => 4000, 'description' => 'Isolation room bed'],
            ['name' => 'Pediatric', 'charge_per_day' => 2500, 'description' => 'Pediatric ward bed'],
        ];

        foreach ($types as $type) {
            BedType::firstOrCreate(['name' => $type['name']], $type);
        }

        $bedTypes = BedType::pluck('id', 'name');

        $beds = [
            // General Ward A
            ['bed_number' => 'A-01', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-02', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-03', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-04', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-05', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-06', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-07', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],
            ['bed_number' => 'A-08', 'ward_name' => 'General Ward A', 'bed_type' => 'General Ward'],

            // General Ward B
            ['bed_number' => 'B-01', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],
            ['bed_number' => 'B-02', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],
            ['bed_number' => 'B-03', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],
            ['bed_number' => 'B-04', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],
            ['bed_number' => 'B-05', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],
            ['bed_number' => 'B-06', 'ward_name' => 'General Ward B', 'bed_type' => 'General Ward'],

            // Private Rooms
            ['bed_number' => 'P-01', 'ward_name' => 'Private Wing', 'bed_type' => 'Private'],
            ['bed_number' => 'P-02', 'ward_name' => 'Private Wing', 'bed_type' => 'Private'],
            ['bed_number' => 'P-03', 'ward_name' => 'Private Wing', 'bed_type' => 'Private'],
            ['bed_number' => 'P-04', 'ward_name' => 'Private Wing', 'bed_type' => 'Private'],
            ['bed_number' => 'P-05', 'ward_name' => 'Private Wing', 'bed_type' => 'Private'],

            // Semi-Private
            ['bed_number' => 'SP-01', 'ward_name' => 'Semi-Private Wing', 'bed_type' => 'Semi-Private'],
            ['bed_number' => 'SP-02', 'ward_name' => 'Semi-Private Wing', 'bed_type' => 'Semi-Private'],
            ['bed_number' => 'SP-03', 'ward_name' => 'Semi-Private Wing', 'bed_type' => 'Semi-Private'],
            ['bed_number' => 'SP-04', 'ward_name' => 'Semi-Private Wing', 'bed_type' => 'Semi-Private'],

            // ICU
            ['bed_number' => 'ICU-01', 'ward_name' => 'ICU', 'bed_type' => 'ICU'],
            ['bed_number' => 'ICU-02', 'ward_name' => 'ICU', 'bed_type' => 'ICU'],
            ['bed_number' => 'ICU-03', 'ward_name' => 'ICU', 'bed_type' => 'ICU'],
            ['bed_number' => 'ICU-04', 'ward_name' => 'ICU', 'bed_type' => 'ICU'],

            // HDU
            ['bed_number' => 'HDU-01', 'ward_name' => 'HDU', 'bed_type' => 'HDU'],
            ['bed_number' => 'HDU-02', 'ward_name' => 'HDU', 'bed_type' => 'HDU'],
            ['bed_number' => 'HDU-03', 'ward_name' => 'HDU', 'bed_type' => 'HDU'],

            // Maternity
            ['bed_number' => 'M-01', 'ward_name' => 'Maternity Ward', 'bed_type' => 'Maternity'],
            ['bed_number' => 'M-02', 'ward_name' => 'Maternity Ward', 'bed_type' => 'Maternity'],
            ['bed_number' => 'M-03', 'ward_name' => 'Maternity Ward', 'bed_type' => 'Maternity'],
            ['bed_number' => 'M-04', 'ward_name' => 'Maternity Ward', 'bed_type' => 'Maternity'],
            ['bed_number' => 'M-05', 'ward_name' => 'Maternity Ward', 'bed_type' => 'Maternity'],

            // Isolation
            ['bed_number' => 'ISO-01', 'ward_name' => 'Isolation Unit', 'bed_type' => 'Isolation'],
            ['bed_number' => 'ISO-02', 'ward_name' => 'Isolation Unit', 'bed_type' => 'Isolation'],

            // Pediatric
            ['bed_number' => 'PED-01', 'ward_name' => 'Pediatric Ward', 'bed_type' => 'Pediatric'],
            ['bed_number' => 'PED-02', 'ward_name' => 'Pediatric Ward', 'bed_type' => 'Pediatric'],
            ['bed_number' => 'PED-03', 'ward_name' => 'Pediatric Ward', 'bed_type' => 'Pediatric'],
            ['bed_number' => 'PED-04', 'ward_name' => 'Pediatric Ward', 'bed_type' => 'Pediatric'],
        ];

        foreach ($beds as $bed) {
            Bed::firstOrCreate(
                ['bed_number' => $bed['bed_number']],
                [
                    'ward_name' => $bed['ward_name'],
                    'bed_type_id' => $bedTypes[$bed['bed_type']] ?? $bedTypes->first(),
                    'is_available' => true,
                ]
            );
        }

        $this->command?->info('Bed types: '.BedType::count().', Beds: '.Bed::count());
    }
}
