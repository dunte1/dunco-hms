<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MedicalEquipment;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Models\CalibrationRecord;
use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\AssetDisposal;
use App\Models\EmployeeDepartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class G050MaintenanceCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->user = User::factory()->create();
        $this->user->assignRole('Biomedical Engineer');
    }

    private function createEquipment(array $overrides = []): MedicalEquipment
    {
        return MedicalEquipment::create(array_merge([
            'name' => 'Test Equipment',
            'serial_number' => 'SN-' . uniqid(),
            'category' => 'diagnostic',
        ], $overrides));
    }

    public function test_maintenance_request_creation(): void
    {
        $equipment = $this->createEquipment();

        $response = $this->actingAs($this->user)->post(route('maintenance.requests.store'), [
            'equipment_id' => $equipment->id,
            'request_type' => 'corrective',
            'priority' => 'high',
            'description' => 'Pump making unusual noise',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $request = MaintenanceRequest::first();
        $this->assertNotNull($request);
        $this->assertEquals($equipment->id, $request->equipment_id);
        $this->assertEquals($this->user->id, $request->requested_by);
        $this->assertEquals('corrective', $request->request_type);
        $this->assertEquals('high', $request->priority);
        $this->assertEquals('pending', $request->status);
    }

    public function test_maintenance_request_with_assignment(): void
    {
        $equipment = $this->createEquipment();
        $assignee = User::factory()->create();

        $response = $this->actingAs($this->user)->post(route('maintenance.requests.store'), [
            'equipment_id' => $equipment->id,
            'request_type' => 'preventive',
            'priority' => 'medium',
            'description' => 'Scheduled maintenance',
            'assigned_to' => $assignee->id,
        ]);

        $response->assertRedirect();

        $request = MaintenanceRequest::first();
        $this->assertEquals($assignee->id, $request->assigned_to);
        $this->assertEquals('assigned', $request->status);
    }

    public function test_work_order_lifecycle(): void
    {
        $equipment = $this->createEquipment();

        $response = $this->actingAs($this->user)->post(route('maintenance.work-orders.store'), [
            'equipment_id' => $equipment->id,
            'work_type' => 'corrective',
            'title' => 'Replace bearing',
            'description' => 'Bearing replacement on pump motor',
            'assigned_to' => $this->user->id,
            'labor_hours' => 4.5,
            'cost' => 1200.00,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $order = WorkOrder::first();
        $this->assertNotNull($order);
        $this->assertEquals('open', $order->status);
        $this->assertEquals(4.5, (float) $order->labor_hours);
        $this->assertEquals(1200.00, (float) $order->cost);

        $response = $this->actingAs($this->user)->post(route('maintenance.work-orders.complete', $order));

        $response->assertRedirect();
        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertNotNull($order->completed_at);
    }

    public function test_work_order_completes_linked_request(): void
    {
        $equipment = $this->createEquipment();

        $request = MaintenanceRequest::create([
            'equipment_id' => $equipment->id,
            'requested_by' => $this->user->id,
            'request_type' => 'corrective',
            'priority' => 'high',
            'description' => 'Broken display',
            'status' => 'in_progress',
        ]);

        $order = WorkOrder::create([
            'request_id' => $request->id,
            'equipment_id' => $equipment->id,
            'work_type' => 'corrective',
            'title' => 'Fix display',
            'description' => 'Replace LCD panel',
            'assigned_to' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($this->user)->post(route('maintenance.work-orders.complete', $order));

        $request->refresh();
        $this->assertEquals('completed', $request->status);
    }

    public function test_calibration_recording_with_next_due(): void
    {
        $equipment = $this->createEquipment();

        $response = $this->actingAs($this->user)->post(route('maintenance.calibrations.store'), [
            'equipment_id' => $equipment->id,
            'calibration_date' => '2026-09-01',
            'next_due_date' => '2027-03-01',
            'result' => 'pass',
            'certificate_number' => 'CAL-2026-001',
            'performed_by_vendor' => true,
            'vendor_name' => 'MedCal Inc',
            'cost' => 500.00,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $cal = CalibrationRecord::first();
        $this->assertNotNull($cal);
        $this->assertEquals($equipment->id, $cal->equipment_id);
        $this->assertEquals('pass', $cal->result);
        $this->assertEquals('2027-03-01', $cal->next_due_date->toDateString());
        $this->assertTrue($cal->performed_by_vendor);
        $this->assertEquals(500.00, (float) $cal->cost);
    }

    public function test_asset_creation_with_value_tracking(): void
    {
        $dept = EmployeeDepartment::create(['name' => 'Radiology']);

        $response = $this->actingAs($this->user)->post(route('assets.store'), [
            'name' => 'X-Ray Machine',
            'category' => 'medical',
            'purchase_date' => '2025-01-15',
            'purchase_cost' => 500000.00,
            'current_value' => 450000.00,
            'location' => 'Building A, Room 3',
            'department_id' => $dept->id,
            'serial_number' => 'XR-001',
            'manufacturer' => 'Siemens',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $asset = Asset::first();
        $this->assertNotNull($asset);
        $this->assertStringStartsWith('AST-', $asset->asset_number);
        $this->assertEquals('active', $asset->status);
        $this->assertEquals(500000.00, (float) $asset->purchase_cost);
        $this->assertEquals(450000.00, (float) $asset->current_value);
        $this->assertEquals($dept->id, $asset->department_id);
    }

    public function test_asset_transfer(): void
    {
        $dept = EmployeeDepartment::create(['name' => 'Pharmacy']);
        $newDept = EmployeeDepartment::create(['name' => 'Laboratory']);

        $asset = Asset::create([
            'asset_number' => 'AST-TEST-001',
            'name' => 'Centrifuge',
            'category' => 'medical',
            'location' => 'Building B',
            'department_id' => $dept->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('assets.transfer', $asset), [
            'to_location' => 'Building C',
            'to_department_id' => $newDept->id,
            'reason' => 'Department restructure',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $asset->refresh();
        $this->assertEquals('Building C', $asset->location);
        $this->assertEquals($newDept->id, $asset->department_id);
        $this->assertEquals('transferred', $asset->status);

        $transfer = AssetTransfer::first();
        $this->assertNotNull($transfer);
        $this->assertEquals('Building B', $transfer->from_location);
        $this->assertEquals($this->user->id, $transfer->transferred_by);
    }

    public function test_asset_disposal(): void
    {
        $asset = Asset::create([
            'asset_number' => 'AST-DISP-001',
            'name' => 'Old Printer',
            'category' => 'it',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('assets.dispose', $asset), [
            'disposal_method' => 'recycled',
            'reason' => 'End of life, non-functional',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $asset->refresh();
        $this->assertEquals('disposed', $asset->status);

        $disposal = AssetDisposal::first();
        $this->assertNotNull($disposal);
        $this->assertEquals('recycled', $disposal->disposal_method);
        $this->assertEquals($this->user->id, $disposal->approved_by);
    }
}
