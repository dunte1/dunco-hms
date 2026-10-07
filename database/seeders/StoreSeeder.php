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
        $mainPharmacy = Store::firstOrCreate(['code' => 'PHARM-01'], [
            'name' => 'Main Pharmacy',
            'description' => 'Primary hospital pharmacy - all medications dispensed from here',
            'type' => 'pharmacy', 'status' => 'active', 'is_main' => true,
        ]);

        $emergencyStore = Store::firstOrCreate(['code' => 'PHARM-EM'], [
            'name' => 'Emergency Pharmacy',
            'description' => 'Emergency department pharmacy - critical care medications',
            'type' => 'emergency', 'status' => 'active', 'is_main' => false,
        ]);

        $wardA = Store::firstOrCreate(['code' => 'WRD-A'], [
            'name' => 'Ward A Store',
            'description' => 'General ward medication storage',
            'type' => 'ward', 'status' => 'active', 'is_main' => false,
        ]);

        $wardB = Store::firstOrCreate(['code' => 'WRD-B'], [
            'name' => 'Ward B Store',
            'description' => 'Surgical ward medication storage',
            'type' => 'ward', 'status' => 'active', 'is_main' => false,
        ]);

        $warehouse = Store::firstOrCreate(['code' => 'WH-01'], [
            'name' => 'Central Warehouse',
            'description' => 'Bulk storage and procurement warehouse',
            'type' => 'warehouse', 'status' => 'active', 'is_main' => false,
        ]);

        $satellite = Store::firstOrCreate(['code' => 'SAT-01'], [
            'name' => 'Satellite Clinic Store',
            'description' => 'Satellite clinic pharmacy outpost',
            'type' => 'satellite', 'status' => 'active', 'is_main' => false,
        ]);

        $medicines = Medicine::all();
        foreach ($medicines as $med) {
            $qty = rand(20, 200);
            if (! StoreStock::where('store_id', $mainPharmacy->id)->where('medicine_id', $med->id)->exists()) {
                StoreStock::create([
                    'store_id' => $mainPharmacy->id,
                    'medicine_id' => $med->id,
                    'quantity' => $qty,
                    'minimum_stock' => 10,
                    'maximum_stock' => 500,
                    'average_cost' => $med->unit_price * 0.6,
                ]);
            }

            $batchNo = 'BAT-' . strtoupper(substr(md5($med->name), 0, 6));
            if (! MedicineBatch::where('medicine_id', $med->id)->where('store_id', $mainPharmacy->id)->where('batch_number', $batchNo)->exists()) {
                MedicineBatch::create([
                    'medicine_id' => $med->id,
                    'store_id' => $mainPharmacy->id,
                    'batch_number' => $batchNo,
                    'quantity' => $qty,
                    'quantity_sold' => rand(0, 10),
                    'unit_cost' => $med->unit_price * 0.6,
                    'unit_price' => $med->unit_price,
                    'expiry_date' => now()->addMonths(rand(6, 24)),
                    'status' => 'active',
                ]);
            }

            $emQty = rand(5, 30);
            if (! StoreStock::where('store_id', $emergencyStore->id)->where('medicine_id', $med->id)->exists()) {
                StoreStock::create([
                    'store_id' => $emergencyStore->id,
                    'medicine_id' => $med->id,
                    'quantity' => $emQty,
                    'minimum_stock' => 5,
                    'maximum_stock' => 100,
                    'average_cost' => $med->unit_price * 0.6,
                ]);
            }

            $whQty = rand(100, 500);
            if (! StoreStock::where('store_id', $warehouse->id)->where('medicine_id', $med->id)->exists()) {
                StoreStock::create([
                    'store_id' => $warehouse->id,
                    'medicine_id' => $med->id,
                    'quantity' => $whQty,
                    'minimum_stock' => 50,
                    'maximum_stock' => 2000,
                    'average_cost' => $med->unit_price * 0.5,
                ]);
            }

            $whBatch = 'WH-BAT-' . strtoupper(substr(md5($med->name . 'wh'), 0, 6));
            if (! MedicineBatch::where('medicine_id', $med->id)->where('store_id', $warehouse->id)->where('batch_number', $whBatch)->exists()) {
                MedicineBatch::create([
                    'medicine_id' => $med->id,
                    'store_id' => $warehouse->id,
                    'batch_number' => $whBatch,
                    'quantity' => $whQty,
                    'quantity_sold' => 0,
                    'unit_cost' => $med->unit_price * 0.5,
                    'unit_price' => $med->unit_price,
                    'expiry_date' => now()->addMonths(rand(12, 36)),
                    'status' => 'active',
                ]);
            }
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
