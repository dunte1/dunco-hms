<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreStock;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $mainPharmacy = Store::create([
            'name' => 'Main Pharmacy', 'code' => 'PHARM-01',
            'description' => 'Primary hospital pharmacy - all medications dispensed from here',
            'type' => 'pharmacy', 'status' => 'active', 'is_main' => true,
        ]);

        $emergencyStore = Store::create([
            'name' => 'Emergency Pharmacy', 'code' => 'PHARM-EM',
            'description' => 'Emergency department pharmacy - critical care medications',
            'type' => 'emergency', 'status' => 'active', 'is_main' => false,
        ]);

        $wardA = Store::create([
            'name' => 'Ward A Store', 'code' => 'WRD-A',
            'description' => 'General ward medication storage',
            'type' => 'ward', 'status' => 'active', 'is_main' => false,
        ]);

        $wardB = Store::create([
            'name' => 'Ward B Store', 'code' => 'WRD-B',
            'description' => 'Surgical ward medication storage',
            'type' => 'ward', 'status' => 'active', 'is_main' => false,
        ]);

        $warehouse = Store::create([
            'name' => 'Central Warehouse', 'code' => 'WH-01',
            'description' => 'Bulk storage and procurement warehouse',
            'type' => 'warehouse', 'status' => 'active', 'is_main' => false,
        ]);

        $satellite = Store::create([
            'name' => 'Satellite Clinic Store', 'code' => 'SAT-01',
            'description' => 'Satellite clinic pharmacy outpost',
            'type' => 'satellite', 'status' => 'active', 'is_main' => false,
        ]);

        $medicines = Medicine::all();
        foreach ($medicines as $med) {
            $qty = rand(20, 200);
            StoreStock::create([
                'store_id' => $mainPharmacy->id,
                'medicine_id' => $med->id,
                'quantity' => $qty,
                'minimum_stock' => 10,
                'maximum_stock' => 500,
                'average_cost' => $med->unit_price * 0.6,
            ]);

            MedicineBatch::create([
                'medicine_id' => $med->id,
                'store_id' => $mainPharmacy->id,
                'batch_number' => 'BAT-' . strtoupper(substr(md5($med->name), 0, 6)),
                'quantity' => $qty,
                'quantity_sold' => rand(0, 10),
                'unit_cost' => $med->unit_price * 0.6,
                'unit_price' => $med->unit_price,
                'expiry_date' => now()->addMonths(rand(6, 24)),
                'status' => 'active',
            ]);

            $emQty = rand(5, 30);
            StoreStock::create([
                'store_id' => $emergencyStore->id,
                'medicine_id' => $med->id,
                'quantity' => $emQty,
                'minimum_stock' => 5,
                'maximum_stock' => 100,
                'average_cost' => $med->unit_price * 0.6,
            ]);

            $whQty = rand(100, 500);
            StoreStock::create([
                'store_id' => $warehouse->id,
                'medicine_id' => $med->id,
                'quantity' => $whQty,
                'minimum_stock' => 50,
                'maximum_stock' => 2000,
                'average_cost' => $med->unit_price * 0.5,
            ]);

            MedicineBatch::create([
                'medicine_id' => $med->id,
                'store_id' => $warehouse->id,
                'batch_number' => 'WH-BAT-' . strtoupper(substr(md5($med->name . 'wh'), 0, 6)),
                'quantity' => $whQty,
                'quantity_sold' => 0,
                'unit_cost' => $med->unit_price * 0.5,
                'unit_price' => $med->unit_price,
                'expiry_date' => now()->addMonths(rand(12, 36)),
                'status' => 'active',
            ]);
        }

        $this->command->info("Created 6 stores with stock data:");
        $this->command->info("  - Main Pharmacy (PHARM-01)");
        $this->command->info("  - Emergency Pharmacy (PHARM-EM)");
        $this->command->info("  - Ward A Store (WRD-A)");
        $this->command->info("  - Ward B Store (WRD-B)");
        $this->command->info("  - Central Warehouse (WH-01)");
        $this->command->info("  - Satellite Clinic Store (SAT-01)");
        $this->command->info("  Stock seeded for " . $medicines->count() . " medicines across stores");
    }
}
