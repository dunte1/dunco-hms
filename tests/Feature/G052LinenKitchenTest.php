<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ward;
use App\Models\Patient;
use App\Models\LinenRecord;
use App\Models\LaundryBatch;
use App\Models\MealOrder;
use App\Models\KitchenInventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G052LinenKitchenTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_linen_issue_and_return(): void
    {
        $ward = Ward::create(['name' => 'Ward A', 'code' => 'WA-01', 'capacity' => 20]);

        $response = $this->actingAs($this->user)->post(route('hms.linen.records.store'), [
            'linen_type' => 'bed_sheets',
            'quantity' => 10,
            'ward_id' => $ward->id,
            'notes' => 'For Ward A beds',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $record = LinenRecord::first();
        $this->assertNotNull($record);
        $this->assertEquals('bed_sheets', $record->linen_type);
        $this->assertEquals(10, $record->quantity);
        $this->assertEquals($ward->id, $record->ward_id);
        $this->assertEquals('soiled', $record->status);
        $this->assertNotNull($record->issue_date);

        $response = $this->actingAs($this->user)->post(route('hms.linen.records.return', $record));

        $response->assertRedirect();
        $record->refresh();
        $this->assertEquals('clean', $record->status);
        $this->assertNotNull($record->return_date);
    }

    public function test_linen_index(): void
    {
        LinenRecord::create([
            'linen_type' => 'towels',
            'quantity' => 5,
            'status' => 'clean',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.linen.records.index'));

        $response->assertOk();
    }

    public function test_laundry_batch_creation_and_completion(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.linen.batches.store'), [
            'collected_date' => '2026-09-28',
            'total_items' => 50,
            'ward_ids' => [1, 2, 3],
            'notes' => 'Weekly batch from wards',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $batch = LaundryBatch::first();
        $this->assertNotNull($batch);
        $this->assertStringStartsWith('LB-', $batch->batch_number);
        $this->assertEquals(50, $batch->total_items);
        $this->assertEquals('collected', $batch->status);
        $this->assertEquals($this->user->id, $batch->processed_by);
        $this->assertEquals([1, 2, 3], $batch->ward_ids);

        $response = $this->actingAs($this->user)->post(route('hms.linen.batches.complete', $batch));

        $response->assertRedirect();
        $batch->refresh();
        $this->assertEquals('complete', $batch->status);
        $this->assertNotNull($batch->processed_date);
    }

    public function test_meal_ordering_and_delivery(): void
    {
        $patient = Patient::factory()->create();
        $ward = Ward::create(['name' => 'Ward B', 'code' => 'WB-01', 'capacity' => 15]);

        $response = $this->actingAs($this->user)->post(route('hms.kitchen.meals.store'), [
            'patient_id' => $patient->id,
            'ward_id' => $ward->id,
            'meal_type' => 'lunch',
            'diet_type' => 'diabetic',
            'quantity' => 2,
            'order_date' => '2026-09-28',
            'order_time' => '12:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $meal = MealOrder::first();
        $this->assertNotNull($meal);
        $this->assertEquals($patient->id, $meal->patient_id);
        $this->assertEquals($ward->id, $meal->ward_id);
        $this->assertEquals('lunch', $meal->meal_type);
        $this->assertEquals('diabetic', $meal->diet_type);
        $this->assertEquals(2, $meal->quantity);
        $this->assertEquals('ordered', $meal->status);
        $this->assertEquals($this->user->id, $meal->ordered_by);

        $response = $this->actingAs($this->user)->post(route('hms.kitchen.meals.deliver', $meal));

        $response->assertRedirect();
        $meal->refresh();
        $this->assertEquals('delivered', $meal->status);
        $this->assertNotNull($meal->delivered_at);
    }

    public function test_meal_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('hms.kitchen.meals.index'));

        $response->assertOk();
    }

    public function test_kitchen_inventory_crud(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.kitchen.inventory.store'), [
            'item_name' => 'Rice',
            'category' => 'staples',
            'quantity' => 100.50,
            'unit' => 'kg',
            'reorder_level' => 20.00,
            'expiry_date' => '2027-01-15',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $item = KitchenInventoryItem::first();
        $this->assertNotNull($item);
        $this->assertEquals('Rice', $item->item_name);
        $this->assertEquals('staples', $item->category);
        $this->assertEquals('100.50', $item->quantity);
        $this->assertEquals('kg', $item->unit);
        $this->assertEquals('20.00', $item->reorder_level);
        $this->assertNotNull($item->last_restocked_at);

        $response = $this->actingAs($this->user)->post(route('hms.kitchen.inventory.restock', $item), [
            'quantity' => 50.25,
        ]);

        $response->assertRedirect();
        $item->refresh();
        $this->assertEquals('150.75', $item->quantity);
    }

    public function test_kitchen_inventory_index(): void
    {
        KitchenInventoryItem::create([
            'item_name' => 'Milk',
            'category' => 'dairy',
            'quantity' => 30,
            'unit' => 'litre',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.kitchen.inventory.index'));

        $response->assertOk();
    }
}
