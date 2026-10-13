<?php

namespace Tests\Feature\Clinical;

use App\Models\Doctor;
use App\Models\LabRequest;
use App\Models\Medicine;
use App\Models\OpdVisit;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Services\PatientFlowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrescriptionLabTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_prescription_store_sets_pharmacy_pending(): void
    {
        $user = $this->makeUserWithRole('Doctor');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATRX001']);
        $doctor = Doctor::factory()->create();
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'in_consultation',
        ]);

        $medicine = $this->makeMedicine(['name' => 'Amoxicillin', 'unit_price' => 200]);

        $response = $this->post(route('hms.pharmacy.prescriptions.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'prescription_date' => now()->toDateString(),
            'symptoms' => 'Cough',
            'diagnosis' => 'Bacterial infection',
            'notes' => 'Test',
            'medicines' => [
                [
                    'medicine_id' => $medicine->id,
                    'dosage' => '500mg',
                    'frequency' => 'Twice daily',
                    'quantity' => 10,
                    'duration_days' => 5,
                    'instructions' => 'After meals',
                ],
            ],
        ]);

        $response->assertRedirect();
        $opd->refresh();
        $this->assertSame('pharmacy_pending', $opd->status);

        $prescription = Prescription::where('patient_id', $patient->id)->first();
        $this->assertNotNull($prescription);
        $this->assertCount(1, $prescription->items);
    }

    public function test_lab_request_store_sets_lab_pending(): void
    {
        $user = $this->makeUserWithRole('Doctor');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATLAB001']);
        $doctor = Doctor::factory()->create();
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'in_consultation',
        ]);

        $labTest = $this->makeLabTest(['test_name' => 'Full Blood Count']);

        $response = $this->post(route('hms.laboratory.requests.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'request_date' => now()->toDateString(),
            'clinical_notes' => 'Fever workup',
            'lab_tests' => [$labTest->id],
            'billing_mode' => 'cash',
        ]);

        $response->assertRedirect();
        $opd->refresh();
        $this->assertSame('lab_pending', $opd->status);

        $lab = LabRequest::where('patient_id', $patient->id)->first();
        $this->assertNotNull($lab);
    }

    public function test_lab_results_return_opd_to_consultation(): void
    {
        $user = $this->makeUserWithRole('Lab Technician');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATLAB002']);
        $doctor = Doctor::factory()->create();
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'lab_pending',
        ]);

        $labTest = $this->makeLabTest(['test_name' => 'Malaria RDT', 'price' => 300]);

        $lab = LabRequest::create([
            'request_number' => 'LAB-RX-002',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $item = $lab->items()->create([
            'lab_test_id' => $labTest->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('hms.laboratory.requests.results.store', $lab), [
            'results' => [
                $item->id => [
                    'result_value' => 'Negative',
                    'unit' => '',
                    'result_notes' => 'Normal',
                ],
            ],
            'results_notes' => 'All normal',
        ]);

        $response->assertRedirect();
        $lab->refresh();
        $this->assertSame('completed', $lab->status);
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);
    }

    public function test_dispense_prescription_advances_opd(): void
    {
        $user = $this->makeUserWithRole('Pharmacist');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATRX002']);
        $doctor = Doctor::factory()->create();
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'pharmacy_pending',
        ]);

        $medicine = $this->makeMedicine(['name' => 'Paracetamol', 'unit_price' => 50, 'stock_quantity' => 200]);

        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'opd_visit_id' => $opd->id,
            'prescription_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $prescription->items()->create([
            'medicine_id' => $medicine->id,
            'dosage' => '500mg',
            'frequency' => 'TDS',
            'quantity' => 10,
            'duration_days' => 3,
        ]);

        $response = $this->post(route('hms.pharmacy.prescriptions.dispense', $prescription), [
            'billing_mode' => 'cash',
        ]);

        $response->assertRedirect();
        $prescription->refresh();
        $this->assertSame('dispensed', $prescription->status);
        $medicine->refresh();
        $this->assertSame(190, (int) $medicine->stock_quantity);

        $opd->refresh();
        $this->assertSame('billing_pending', $opd->status);
    }

    public function test_start_consultation_route_exists_and_works(): void
    {
        $user = $this->makeUserWithRole('Doctor');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATRX003']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'triaged',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Route::has('hms.opd.start-consultation'));

        $response = $this->post(route('hms.opd.start-consultation', $opd));
        $response->assertRedirect();
        $opd->refresh();
        $this->assertSame('in_consultation', $opd->status);
    }

    public function test_finalize_encounter_requires_diagnosis_or_notes(): void
    {
        $user = $this->makeUserWithRole('Doctor');
        $this->actingAs($user);

        $patient = Patient::factory()->create(['patient_no' => 'PATRX004']);
        $opd = OpdVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => now()->toDateString(),
            'status' => 'in_consultation',
        ]);

        $response = $this->post(route('hms.opd.finalize', $opd));
        $response->assertSessionHas('error');

        $opd->update(['diagnosis' => 'Test dx']);
        $response = $this->post(route('hms.opd.finalize', $opd));
        $response->assertRedirect();
        $opd->refresh();
        $this->assertSame('completed', $opd->status);
    }

    public function test_medicine_stock_decrements_on_dispense(): void
    {
        $medicine = $this->makeMedicine(['name' => 'Ibuprofen', 'unit_price' => 80, 'stock_quantity' => 50]);

        $patient = Patient::factory()->create(['patient_no' => 'PATRX005']);
        $doctor = Doctor::factory()->create();
        $prescription = Prescription::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'prescription_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $prescription->items()->create([
            'medicine_id' => $medicine->id,
            'dosage' => '400mg',
            'frequency' => 'BD',
            'quantity' => 7,
            'duration_days' => 3,
        ]);

        $user = $this->makeUserWithRole('Pharmacist');
        $this->actingAs($user);

        $this->post(route('hms.pharmacy.prescriptions.dispense', $prescription), [
            'billing_mode' => 'cash',
        ]);

        $medicine->refresh();
        $this->assertSame(43, (int) $medicine->stock_quantity);
    }
}
