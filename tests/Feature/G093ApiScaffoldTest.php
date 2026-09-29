<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\Prescription;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\RadiologyTest;
use App\Models\RadiologyCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class G093ApiScaffoldTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Patient $patient;
    protected Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        $this->user = User::factory()->create(['status' => 'active']);
        $this->user->assignRole('Super Admin');

        $dept = DoctorDepartment::create(['name' => 'General']);
        $this->patient = Patient::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0712345678',
            'dob' => '1990-05-15',
            'gender' => 'male',
        ]);
        $this->doctor = Doctor::create([
            'first_name' => 'James',
            'last_name' => 'Mwangi',
            'email' => 'dr@example.com',
            'phone' => '0712345678',
            'doctor_department_id' => $dept->id,
        ]);
    }

    private function authHeaders(): array
    {
        $token = $this->user->createToken('test-token')->plainTextToken;
        return ['Authorization' => 'Bearer ' . $token];
    }

    // ── Authentication ──────────────────────────────────────────────────

    public function test_unauthenticated_requests_are_rejected(): void
    {
        $endpoints = [
            ['GET', '/api/v1/patients'],
            ['POST', '/api/v1/patients'],
            ['GET', '/api/v1/prescriptions'],
            ['POST', '/api/v1/prescriptions'],
            ['GET', '/api/v1/lab-requests'],
            ['POST', '/api/v1/lab-requests'],
            ['GET', '/api/v1/radiology-requests'],
            ['POST', '/api/v1/radiology-requests'],
        ];

        foreach ($endpoints as [$method, $uri]) {
            $response = $this->json($method, $uri);
            $response->assertStatus(401);
        }
    }

    // ── Patients ────────────────────────────────────────────────────────

    public function test_patients_index_returns_paginated_json(): void
    {
        Patient::factory()->count(20)->create();

        $response = $this->json('GET', '/api/v1/patients', [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_patients_store_validates_required_fields(): void
    {
        $response = $this->json('POST', '/api/v1/patients', [], $this->authHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'dob', 'gender']);
    }

    public function test_patients_store_creates_patient(): void
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '0798765432',
            'dob' => '1985-03-20',
            'gender' => 'female',
        ];

        $response = $this->json('POST', '/api/v1/patients', $data, $this->authHeaders());

        $response->assertStatus(201)
            ->assertJson(['success' => true, 'message' => 'Patient created successfully']);

        $this->assertDatabaseHas('patients', ['email' => 'jane@example.com']);
    }

    public function test_patients_show_returns_patient(): void
    {
        $response = $this->json('GET', "/api/v1/patients/{$this->patient->id}", [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['id' => $this->patient->id]]);
    }

    public function test_patients_show_returns_404_for_nonexistent(): void
    {
        $response = $this->json('GET', '/api/v1/patients/99999', [], $this->authHeaders());

        $response->assertStatus(404);
    }

    public function test_patients_update_modifies_patient(): void
    {
        $response = $this->json('PUT', "/api/v1/patients/{$this->patient->id}", [
            'first_name' => 'Updated',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Patient updated successfully']);

        $this->assertDatabaseHas('patients', ['id' => $this->patient->id, 'first_name' => 'Updated']);
    }

    public function test_patients_destroy_deletes_patient(): void
    {
        $freshPatient = Patient::create([
            'first_name' => 'ToDelete',
            'last_name' => 'Patient',
            'dob' => '1995-01-01',
            'gender' => 'male',
        ]);

        $response = $this->json('DELETE', "/api/v1/patients/{$freshPatient->id}", [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Patient deleted successfully']);

        $this->assertDatabaseCount('patients', 1);
    }

    // ── Prescriptions ───────────────────────────────────────────────────

    public function test_prescriptions_index_returns_paginated_json(): void
    {
        Prescription::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'prescription_date' => now(),
            'status' => 'active',
        ]);

        $response = $this->json('GET', '/api/v1/prescriptions', [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_prescriptions_store_validates_required_fields(): void
    {
        $response = $this->json('POST', '/api/v1/prescriptions', [], $this->authHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['patient_id', 'doctor_id', 'prescription_date']);
    }

    public function test_prescriptions_store_creates_prescription(): void
    {
        $data = [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'prescription_date' => now()->toDateString(),
            'symptoms' => 'Fever, cough',
            'diagnosis' => 'Common cold',
            'status' => 'active',
        ];

        $response = $this->json('POST', '/api/v1/prescriptions', $data, $this->authHeaders());

        $response->assertStatus(201)
            ->assertJson(['success' => true, 'message' => 'Prescription created successfully']);

        $this->assertDatabaseHas('prescriptions', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
        ]);
    }

    public function test_prescriptions_show_returns_prescription(): void
    {
        $prescription = Prescription::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'prescription_date' => now(),
            'status' => 'active',
        ]);

        $response = $this->json('GET', "/api/v1/prescriptions/{$prescription->id}", [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['id' => $prescription->id]]);
    }

    public function test_prescriptions_update_modifies_prescription(): void
    {
        $prescription = Prescription::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'prescription_date' => now(),
            'status' => 'active',
        ]);

        $response = $this->json('PUT', "/api/v1/prescriptions/{$prescription->id}", [
            'status' => 'completed',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Prescription updated successfully']);

        $this->assertDatabaseHas('prescriptions', ['id' => $prescription->id, 'status' => 'completed']);
    }

    // ── Lab Requests ────────────────────────────────────────────────────

    public function test_lab_requests_index_returns_paginated_json(): void
    {
        LabRequest::create([
            'request_number' => 'LAB-001',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'request_date' => now(),
        ]);

        $response = $this->json('GET', '/api/v1/lab-requests', [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_lab_requests_store_validates_required_fields(): void
    {
        $response = $this->json('POST', '/api/v1/lab-requests', [], $this->authHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['patient_id', 'request_date']);
    }

    public function test_lab_requests_store_creates_request(): void
    {
        $data = [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'request_date' => now()->toDateString(),
            'clinical_notes' => 'CBC test requested',
            'status' => 'pending',
        ];

        $response = $this->json('POST', '/api/v1/lab-requests', $data, $this->authHeaders());

        $response->assertStatus(201)
            ->assertJson(['success' => true, 'message' => 'Lab request created successfully']);

        $this->assertDatabaseHas('lab_requests', [
            'patient_id' => $this->patient->id,
            'request_number' => 'LAB-000001',
        ]);
    }

    public function test_lab_requests_show_returns_request(): void
    {
        $labRequest = LabRequest::create([
            'request_number' => 'LAB-002',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'request_date' => now(),
        ]);

        $response = $this->json('GET', "/api/v1/lab-requests/{$labRequest->id}", [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['id' => $labRequest->id]]);
    }

    public function test_lab_requests_update_modifies_request(): void
    {
        $labRequest = LabRequest::create([
            'request_number' => 'LAB-003',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'request_date' => now(),
        ]);

        $response = $this->json('PUT', "/api/v1/lab-requests/{$labRequest->id}", [
            'status' => 'completed',
            'results_notes' => 'All results normal',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Lab request updated successfully']);

        $this->assertDatabaseHas('lab_requests', ['id' => $labRequest->id, 'status' => 'completed']);
    }

    // ── Radiology Requests ──────────────────────────────────────────────

    public function test_radiology_requests_index_returns_paginated_json(): void
    {
        $category = RadiologyCategory::create(['name' => 'Imaging']);
        $test = RadiologyTest::create([
            'test_name' => 'X-Ray',
            'price' => 1000,
            'is_active' => true,
            'category_id' => $category->id,
        ]);

        RadiologyRequest::create([
            'request_number' => 'RAD-001',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'radiology_test_id' => $test->id,
            'request_date' => now(),
        ]);

        $response = $this->json('GET', '/api/v1/radiology-requests', [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_radiology_requests_store_validates_required_fields(): void
    {
        $response = $this->json('POST', '/api/v1/radiology-requests', [], $this->authHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['patient_id', 'radiology_test_id', 'request_date']);
    }

    public function test_radiology_requests_store_creates_request(): void
    {
        $category = RadiologyCategory::create(['name' => 'Imaging']);
        $test = RadiologyTest::create([
            'test_name' => 'MRI',
            'price' => 5000,
            'is_active' => true,
            'category_id' => $category->id,
        ]);

        $data = [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'radiology_test_id' => $test->id,
            'request_date' => now()->toDateString(),
            'clinical_notes' => 'Chest X-ray for suspected pneumonia',
            'status' => 'pending',
        ];

        $response = $this->json('POST', '/api/v1/radiology-requests', $data, $this->authHeaders());

        $response->assertStatus(201)
            ->assertJson(['success' => true, 'message' => 'Radiology request created successfully']);

        $this->assertDatabaseHas('radiology_requests', [
            'patient_id' => $this->patient->id,
            'request_number' => 'RAD-000001',
        ]);
    }

    public function test_radiology_requests_show_returns_request(): void
    {
        $category = RadiologyCategory::create(['name' => 'Imaging']);
        $test = RadiologyTest::create([
            'test_name' => 'CT Scan',
            'price' => 8000,
            'is_active' => true,
            'category_id' => $category->id,
        ]);

        $radiologyRequest = RadiologyRequest::create([
            'request_number' => 'RAD-002',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'radiology_test_id' => $test->id,
            'request_date' => now(),
        ]);

        $response = $this->json('GET', "/api/v1/radiology-requests/{$radiologyRequest->id}", [], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['id' => $radiologyRequest->id]]);
    }

    public function test_radiology_requests_update_modifies_request(): void
    {
        $category = RadiologyCategory::create(['name' => 'Imaging']);
        $test = RadiologyTest::create([
            'test_name' => 'Ultrasound',
            'price' => 3000,
            'is_active' => true,
            'category_id' => $category->id,
        ]);

        $radiologyRequest = RadiologyRequest::create([
            'request_number' => 'RAD-003',
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'radiology_test_id' => $test->id,
            'request_date' => now(),
        ]);

        $response = $this->json('PUT', "/api/v1/radiology-requests/{$radiologyRequest->id}", [
            'status' => 'completed',
            'findings' => 'No abnormalities detected',
            'impression' => 'Normal',
        ], $this->authHeaders());

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Radiology request updated successfully']);

        $this->assertDatabaseHas('radiology_requests', ['id' => $radiologyRequest->id, 'status' => 'completed']);
    }
}
