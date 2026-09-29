<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineBatch;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Dispensation;
use App\Models\DispensationItem;
use App\Models\DrugReturn;
use App\Models\ControlledDrugRegister;
use App\Models\GoodsReceivedNote;
use App\Models\GoodsReceivedItem;
use App\Models\Store;
use App\Models\StoreStock;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G034PharmacyCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private MedicineCategory $category;
    private Medicine $medicine;
    private Prescription $prescription;
    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'view patients']);
        Permission::create(['name' => 'manage pharmacy']);
        $this->user->givePermissionTo(['view patients', 'manage pharmacy']);

        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $this->category = MedicineCategory::create(['name' => 'Antibiotics']);
        $this->medicine = Medicine::create([
            'name' => 'Amoxicillin',
            'category_id' => $this->category->id,
            'dosage_form' => 'capsule',
            'strength' => '500mg',
            'unit_price' => 50,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
        ]);
        $this->store = Store::create([
            'name' => 'Pharmacy Store',
            'code' => 'PH-01',
            'type' => 'pharmacy',
            'status' => 'active',
        ]);
        $this->prescription = Prescription::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'prescription_date' => now(),
            'status' => 'active',
        ]);
    }

    public function test_dispensation_creation_with_stock_deduction(): void
    {
        $prescriptionItem = PrescriptionItem::create([
            'prescription_id' => $this->prescription->id,
            'medicine_id' => $this->medicine->id,
            'dosage' => '500mg',
            'frequency' => 'three times daily',
            'quantity' => 30,
            'duration_days' => 10,
        ]);

        $initialStock = $this->medicine->fresh()->stock_quantity;

        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.dispensations.store'), [
            'prescription_id' => $this->prescription->id,
            'items' => [
                [
                    'prescription_item_id' => $prescriptionItem->id,
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 30,
                    'unit_price' => 50,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dispensations', [
            'prescription_id' => $this->prescription->id,
            'patient_id' => $this->patient->id,
            'pharmacist_id' => $this->user->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('dispensation_items', [
            'medicine_id' => $this->medicine->id,
            'quantity' => 30,
            'unit_price' => 50,
            'total_price' => 1500,
        ]);
        $this->assertEquals($initialStock - 30, $this->medicine->fresh()->stock_quantity);
    }

    public function test_pharmacist_verification_step(): void
    {
        $dispensation = Dispensation::create([
            'prescription_id' => $this->prescription->id,
            'patient_id' => $this->patient->id,
            'pharmacist_id' => $this->user->id,
            'dispensation_number' => 'DSP-20260928-00001',
            'total_amount' => 1500,
            'net_amount' => 1500,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.dispensations.verify', $dispensation));

        $response->assertRedirect();
        $this->assertDatabaseHas('dispensations', [
            'id' => $dispensation->id,
            'status' => 'verified',
            'verified_by' => $this->user->id,
        ]);
        $this->assertNotNull($dispensation->fresh()->verified_at);
    }

    public function test_drug_return_request_workflow(): void
    {
        // Submit return request
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.returns.store'), [
            'patient_id' => $this->patient->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 5,
            'reason' => 'Patient no longer needs medication',
            'return_type' => 'patient_return',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('drug_returns', [
            'patient_id' => $this->patient->id,
            'medicine_id' => $this->medicine->id,
            'quantity' => 5,
            'status' => 'pending',
        ]);

        $drugReturn = DrugReturn::latest()->first();

        // Approve the return
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.returns.approve', $drugReturn));
        $response->assertRedirect();
        $this->assertDatabaseHas('drug_returns', [
            'id' => $drugReturn->id,
            'status' => 'approved',
            'approved_by' => $this->user->id,
        ]);

        $initialStock = $this->medicine->fresh()->stock_quantity;

        // Process the return (should add stock back for patient_return)
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.returns.process', $drugReturn));
        $response->assertRedirect();
        $this->assertDatabaseHas('drug_returns', [
            'id' => $drugReturn->id,
            'status' => 'processed',
            'processed_by' => $this->user->id,
        ]);
        $this->assertNotNull($drugReturn->fresh()->processed_at);
        $this->assertEquals($initialStock + 5, $this->medicine->fresh()->stock_quantity);
    }

    public function test_controlled_drug_register_logging(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.controlled-drugs.store'), [
            'medicine_id' => $this->medicine->id,
            'transaction_type' => 'received',
            'quantity' => 100,
            'notes' => 'Initial stock received',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('controlled_drug_registers', [
            'medicine_id' => $this->medicine->id,
            'transaction_type' => 'received',
            'quantity' => 100,
            'balance_after' => 100,
            'performed_by' => $this->user->id,
        ]);

        // Dispense from the register
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.controlled-drugs.store'), [
            'medicine_id' => $this->medicine->id,
            'transaction_type' => 'dispensed',
            'quantity' => 20,
            'notes' => 'Dispensed to patient',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('controlled_drug_registers', [
            'medicine_id' => $this->medicine->id,
            'transaction_type' => 'dispensed',
            'quantity' => 20,
            'balance_after' => 80,
        ]);
    }

    public function test_grn_creation_with_items(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.grn.store'), [
            'store_id' => $this->store->id,
            'received_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'batch_number' => 'BATCH-001',
                    'quantity_received' => 200,
                    'unit_cost' => 40,
                    'expiry_date' => now()->addYear()->toDateString(),
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('goods_received_notes', [
            'store_id' => $this->store->id,
            'received_by' => $this->user->id,
            'total_amount' => 8000,
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('goods_received_items', [
            'medicine_id' => $this->medicine->id,
            'batch_number' => 'BATCH-001',
            'quantity_received' => 200,
            'unit_cost' => 40,
        ]);
        $this->assertEquals(300, $this->medicine->fresh()->stock_quantity);
        $this->assertDatabaseHas('medicine_batches', [
            'medicine_id' => $this->medicine->id,
            'store_id' => $this->store->id,
            'batch_number' => 'BATCH-001',
            'quantity' => 200,
        ]);
    }

    public function test_grn_verification(): void
    {
        $grn = GoodsReceivedNote::create([
            'grn_number' => 'GRN-20260928-00001',
            'store_id' => $this->store->id,
            'received_by' => $this->user->id,
            'received_at' => now(),
            'total_amount' => 8000,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.pharmacy.grn.verify', $grn));

        $response->assertRedirect();
        $this->assertDatabaseHas('goods_received_notes', [
            'id' => $grn->id,
            'status' => 'verified',
            'verified_by' => $this->user->id,
        ]);
        $this->assertNotNull($grn->fresh()->verified_at);
    }
}
