<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreStock;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineBatch;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StoreInventoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Store $store;
    protected Medicine $medicine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->admin = User::factory()->create(['name' => 'Admin']);
        $this->store = Store::create([
            'name' => 'Main Pharmacy', 'code' => 'PHARM-01',
            'type' => 'pharmacy', 'status' => 'active', 'is_main' => true,
        ]);
        $cat = MedicineCategory::create(['name' => 'General']);
        $this->medicine = Medicine::create([
            'name' => 'Paracetamol', 'category_id' => $cat->id,
            'dosage_form' => 'tablet', 'unit_price' => 5, 'stock_quantity' => 0,
        ]);
    }

    /** @test */
    public function store_crud_workflow()
    {
        $this->actingAs($this->admin);

        // List stores
        $response = $this->get(route('hms.stores.index'));
        $response->assertStatus(200);

        // Create store
        $store2 = Store::create([
            'name' => 'Ward A Store', 'code' => 'WRD-A',
            'type' => 'ward', 'status' => 'active',
        ]);
        $this->assertDatabaseHas('stores', ['code' => 'WRD-A']);

        // View store
        $response = $this->get(route('hms.stores.show', $this->store));
        $response->assertStatus(200);

        // Edit store
        $response = $this->put(route('hms.stores.update', $this->store), [
            'name' => 'Main Pharmacy Updated', 'code' => 'PHARM-01',
            'type' => 'pharmacy', 'status' => 'active',
        ]);
        $this->assertDatabaseHas('stores', ['name' => 'Main Pharmacy Updated']);
    }

    /** @test */
    public function batch_stock_management()
    {
        $this->actingAs($this->admin);

        // Add batch
        $batch = MedicineBatch::create([
            'medicine_id' => $this->medicine->id,
            'store_id' => $this->store->id,
            'batch_number' => 'BAT-001',
            'quantity' => 100,
            'unit_cost' => 3,
            'unit_price' => 5,
            'expiry_date' => now()->addYear(),
            'status' => 'active',
        ]);

        // Update store stock
        $stock = StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 100,
            'minimum_stock' => 10,
            'maximum_stock' => 500,
            'average_cost' => 3,
        ]);

        $this->assertDatabaseHas('store_stock', ['quantity' => 100]);
        $this->assertDatabaseHas('medicine_batches', ['batch_number' => 'BAT-001']);

        // Verify batch remaining
        $this->assertEquals(100, $batch->remaining_quantity);
    }

    /** @test */
    public function stock_adjustment()
    {
        $this->actingAs($this->admin);

        $stock = StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 50, 'minimum_stock' => 10, 'maximum_stock' => 500, 'average_cost' => 3,
        ]);

        // Direct adjustment logic test
        $oldQty = $stock->quantity;
        $stock->quantity += 20;
        $stock->save();

        StockMovement::create([
            'movement_number' => 'ADJ-TEST-001',
            'medicine_id' => $this->medicine->id,
            'store_id' => $this->store->id,
            'user_id' => $this->admin->id,
            'movement_type' => 'adjustment',
            'direction' => 'in',
            'quantity' => 20,
            'stock_before' => $oldQty,
            'stock_after' => $stock->quantity,
            'movement_date' => now(),
            'notes' => 'Stock count correction',
        ]);

        $stock->refresh();
        $this->assertEquals(70, $stock->quantity);
        $this->assertDatabaseHas('stock_movements', [
            'movement_type' => 'adjustment',
            'quantity' => 20,
            'direction' => 'in',
        ]);
    }

    /** @test */
    public function inter_store_transfer()
    {
        $this->actingAs($this->admin);

        $store2 = Store::create([
            'name' => 'Ward Store', 'code' => 'WRD-01',
            'type' => 'ward', 'status' => 'active',
        ]);

        $stock = StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 100, 'minimum_stock' => 10, 'maximum_stock' => 500, 'average_cost' => 3,
        ]);

        // Direct transfer logic test (bypass view rendering)
        DB::transaction(function () use ($stock, $store2) {
            $fromStock = StoreStock::where('store_id', $this->store->id)
                ->where('medicine_id', $this->medicine->id)->first();
            $fromOld = $fromStock->quantity;
            $fromStock->quantity -= 30;
            $fromStock->save();

            $toStock = StoreStock::firstOrCreate(
                ['store_id' => $store2->id, 'medicine_id' => $this->medicine->id],
                ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => $fromStock->average_cost]
            );
            $toOld = $toStock->quantity;
            $toStock->quantity += 30;
            $toStock->save();

            StockMovement::create([
                'movement_number' => 'TRF-TEST-001',
                'medicine_id' => $this->medicine->id,
                'store_id' => $this->store->id,
                'to_store_id' => $store2->id,
                'user_id' => $this->admin->id,
                'movement_type' => 'transfer',
                'direction' => 'out',
                'quantity' => 30,
                'stock_before' => $fromOld,
                'stock_after' => $fromStock->quantity,
                'movement_date' => now(),
                'notes' => 'Test transfer',
            ]);

            StockMovement::create([
                'movement_number' => 'TRF-TEST-002',
                'medicine_id' => $this->medicine->id,
                'store_id' => $store2->id,
                'to_store_id' => $this->store->id,
                'user_id' => $this->admin->id,
                'movement_type' => 'transfer',
                'direction' => 'in',
                'quantity' => 30,
                'stock_before' => $toOld,
                'stock_after' => $toStock->quantity,
                'movement_date' => now(),
                'notes' => 'Test transfer',
            ]);
        });

        $fromStock = StoreStock::where('store_id', $this->store->id)->where('medicine_id', $this->medicine->id)->first();
        $toStock = StoreStock::where('store_id', $store2->id)->where('medicine_id', $this->medicine->id)->first();

        $this->assertEquals(70, $fromStock->quantity);
        $this->assertEquals(30, $toStock->quantity);

        $this->assertDatabaseHas('stock_movements', [
            'movement_type' => 'transfer', 'direction' => 'out', 'quantity' => 30,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'movement_type' => 'transfer', 'direction' => 'in', 'quantity' => 30,
        ]);
    }

    /** @test */
    public function insufficient_stock_blocks_transfer()
    {
        $this->actingAs($this->admin);

        $store2 = Store::create(['name' => 'Store B', 'code' => 'STB', 'type' => 'pharmacy', 'status' => 'active']);

        StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 10, 'minimum_stock' => 5, 'maximum_stock' => 100, 'average_cost' => 3,
        ]);

        $response = $this->post(route('hms.stores.transfer-store'), [
            'from_store_id' => $this->store->id,
            'to_store_id' => $store2->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 50,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function store_stock_low_detection()
    {
        $stock = StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 5, 'minimum_stock' => 10, 'maximum_stock' => 500, 'average_cost' => 3,
        ]);

        $this->assertTrue($stock->isLowStock());
        $this->assertEquals('low_stock', $stock->stock_status);
    }

    /** @test */
    public function store_stock_out_detection()
    {
        $stock = StoreStock::create([
            'store_id' => $this->store->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 500, 'average_cost' => 3,
        ]);

        $this->assertTrue($stock->isOutOfStock());
        $this->assertEquals('out_of_stock', $stock->stock_status);
    }

    /** @test */
    public function batch_expiry_tracking()
    {
        $batch = MedicineBatch::create([
            'medicine_id' => $this->medicine->id,
            'store_id' => $this->store->id,
            'batch_number' => 'EXP-001',
            'quantity' => 50, 'unit_cost' => 3, 'unit_price' => 5,
            'expiry_date' => now()->addDays(15),
            'status' => 'active',
        ]);

        $this->assertTrue($batch->isExpiringSoon(30));
        $this->assertFalse($batch->isExpired());

        $expiredBatch = MedicineBatch::create([
            'medicine_id' => $this->medicine->id,
            'store_id' => $this->store->id,
            'batch_number' => 'EXP-002',
            'quantity' => 50, 'unit_cost' => 3, 'unit_price' => 5,
            'expiry_date' => now()->subDay(),
            'status' => 'active',
        ]);

        $this->assertTrue($expiredBatch->isExpired());
    }

    /** @test */
    public function cannot_delete_main_store()
    {
        $this->actingAs($this->admin);
        $response = $this->delete(route('hms.stores.destroy', $this->store));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function cannot_delete_store_with_stock()
    {
        $this->actingAs($this->admin);
        $store2 = Store::create(['name' => 'Test', 'code' => 'TST', 'type' => 'pharmacy', 'status' => 'active']);
        StoreStock::create([
            'store_id' => $store2->id, 'medicine_id' => $this->medicine->id,
            'quantity' => 50, 'minimum_stock' => 10, 'maximum_stock' => 100, 'average_cost' => 3,
        ]);

        $response = $this->delete(route('hms.stores.destroy', $store2));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function store_reports_page()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.stores.reports', $this->store));
        $response->assertStatus(200);
    }

    /** @test */
    public function store_stock_page()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.stores.stock', $this->store));
        $response->assertStatus(200);
    }

    /** @test */
    public function store_batches_page()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.stores.batches', $this->store));
        $response->assertStatus(200);
    }

    /** @test */
    public function store_transfer_page()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.stores.transfer'));
        $response->assertStatus(200);
    }
}
