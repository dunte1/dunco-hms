<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Analgesics', 'Antibiotics', 'Antihypertensives', 'Antidiabetics',
            'Respiratory', 'Gastrointestinal', 'Vitamins & Supplements', 'Emergency',
        ];

        foreach ($categories as $cat) {
            MedicineCategory::firstOrCreate(['name' => $cat], ['description' => "{$cat} medicines"]);
        }

        $medicines = [
            ['Paracetamol 500mg', 'Analgesics', 'tablet', '500mg', 2.50, 500],
            ['Ibuprofen 400mg', 'Analgesics', 'tablet', '400mg', 3.00, 300],
            ['Amoxicillin 500mg', 'Antibiotics', 'capsule', '500mg', 8.00, 200],
            ['Azithromycin 500mg', 'Antibiotics', 'tablet', '500mg', 15.00, 150],
            ['Ceftriaxone 1g', 'Antibiotics', 'injection', '1g', 25.00, 80],
            ['Metronidazole 400mg', 'Antibiotics', 'tablet', '400mg', 5.00, 180],
            ['Amlodipine 5mg', 'Antihypertensives', 'tablet', '5mg', 4.00, 220],
            ['Losartan 50mg', 'Antihypertensives', 'tablet', '50mg', 6.50, 160],
            ['Metformin 500mg', 'Antidiabetics', 'tablet', '500mg', 3.50, 250],
            ['Insulin Glargine 100IU', 'Antidiabetics', 'injection', '100IU', 850.00, 40],
            ['Salbutamol Inhaler', 'Respiratory', 'inhaler', '100mcg', 45.00, 60],
            ['Prednisolone 5mg', 'Respiratory', 'tablet', '5mg', 4.50, 140],
            ['Omeprazole 20mg', 'Gastrointestinal', 'capsule', '20mg', 5.50, 190],
            ['ORS Sachet', 'Gastrointestinal', 'sachet', '20.5g', 1.50, 400],
            ['Ondansetron 4mg', 'Gastrointestinal', 'injection', '4mg', 12.00, 90],
            ['Vitamin B Complex', 'Vitamins & Supplements', 'tablet', '—', 2.00, 300],
            ['Vitamin C 1000mg', 'Vitamins & Supplements', 'tablet', '1000mg', 3.00, 250],
            ['Ferrous Sulphate', 'Vitamins & Supplements', 'tablet', '200mg', 1.20, 350],
            ['Adrenaline 1mg', 'Emergency', 'injection', '1mg', 18.00, 70],
            ['Normal Saline 0.9%', 'Emergency', 'iv_fluid', '500ml', 4.00, 200],
            ['Ringer Lactate', 'Emergency', 'iv_fluid', '500ml', 5.00, 150],
            ['Diazepam 5mg', 'Emergency', 'tablet', '5mg', 3.50, 100],
            ['Warfarin 5mg', 'Anticoagulants', 'tablet', '5mg', 7.00, 80],
            ['Enoxaparin 40mg', 'Anticoagulants', 'injection', '40mg', 95.00, 50],
        ];

        // Ensure Anticoagulants category exists
        MedicineCategory::firstOrCreate(['name' => 'Anticoagulants'], ['description' => 'Anticoagulant medicines']);

        foreach ($medicines as $med) {
            $category = MedicineCategory::where('name', $med[1])->first()
                ?? MedicineCategory::firstOrCreate(['name' => $med[1]], ['description' => "{$med[1]} medicines"]);

            Medicine::firstOrCreate(
                ['name' => $med[0]],
                [
                    'category_id' => $category->id,
                    'dosage_form' => $med[2],
                    'strength' => $med[3],
                    'unit_price' => $med[4],
                    'stock_quantity' => $med[5],
                    'minimum_stock' => 20,
                ]
            );
        }

        $this->command->info('✅ Seeded ' . Medicine::count() . ' medicines across categories');
    }
}
