<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\EmployeeDepartment;
use App\Models\BedType;
use App\Models\Bed;
use App\Models\MedicineCategory;
use App\Models\MedicineBrand;
use App\Models\LabCategory;
use App\Models\LabTest;
use App\Models\LabRequest;
use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;
use App\Models\RadiologyRequest;
use App\Models\ICD10Code;
use App\Models\MedicalHistory;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\BloodDonor;
use App\Models\BloodGroup;
use App\Models\Ambulance;
use App\Models\QueueManagement;
use App\Models\OperationReport;
use App\Models\CaseHandler;
use App\Models\VisitorLog;
use App\Models\IpdAdmission;
use App\Models\DiagnosisCategory;
use App\Models\MedicalEquipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudCompleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Patient $patient;
    protected Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->admin = User::factory()->create();
        $dept = DoctorDepartment::create(['name' => 'General']);
        $this->patient = Patient::create([
            'first_name' => 'John', 'last_name' => 'Doe',
            'email' => 'john@test.com', 'phone' => '0712345678',
            'date_of_birth' => '1990-05-15', 'gender' => 'male',
        ]);
        $this->doctor = Doctor::create([
            'first_name' => 'James', 'last_name' => 'Mwangi',
            'email' => 'dr@test.com', 'phone' => '0712345678',
            'department_id' => $dept->id,
        ]);
    }

    private function testCrud($model, array $createData, array $updateData)
    {
        $m = $model::create($createData);
        $this->assertDatabaseHas((new $model)->getTable(), [$createData[array_key_first($createData)] => $createData[array_key_first($createData)]]);
        $m->update($updateData);
        $m->delete();
    }

    /** @test */ public function doctor_departments() { $this->testCrud(DoctorDepartment::class, ['name' => 'Cardiology'], ['name' => 'Cardiology U']); }
    /** @test */ public function employee_departments() { $this->testCrud(EmployeeDepartment::class, ['name' => 'Nursing'], ['name' => 'Nursing U']); }
    /** @test */ public function bed_types() { $this->testCrud(BedType::class, ['name' => 'ICU', 'charge_per_day' => 100], ['name' => 'ICU U']); }
    /** @test */ public function icd10() { $this->testCrud(ICD10Code::class, ['code' => 'A00', 'description' => 'Cholera'], ['description' => 'Cholera U']); }
    /** @test */ public function medicine_categories() { $this->testCrud(MedicineCategory::class, ['name' => 'Antibiotics'], ['name' => 'Antibiotics U']); }
    /** @test */ public function medicine_brands() { $this->testCrud(MedicineBrand::class, ['name' => 'Pharma'], ['name' => 'Pharma U']); }
    /** @test */ public function diagnosis_categories() { $this->testCrud(DiagnosisCategory::class, ['name' => 'Infectious', 'code' => 'INF'], ['name' => 'Infectious U']); }
    /** @test */ public function blood_groups() { $this->testCrud(BloodGroup::class, ['name' => 'O+', 'code' => 'O+'], ['name' => 'O+ U']); }
    /** @test */ public function medical_history() { $h = MedicalHistory::create(['patient_id' => $this->patient->id, 'condition' => 'Diabetes', 'recorded_date' => now()->format('Y-m-d'), 'diagnosed_by' => 'Dr. Test']); $this->assertNotNull($h->id); $h->update(['treatment' => 'Metformin']); $h->delete(); }
    /** @test */ public function medical_equipment() { $this->testCrud(MedicalEquipment::class, ['name' => 'MRI', 'category' => 'diagnostic', 'serial_number' => 'MRI-001', 'status' => 'operational'], ['status' => 'maintenance']); }

    /** @test */
    public function beds()
    {
        $type = BedType::create(['name' => 'General', 'charge_per_day' => 50]);
        $bed = Bed::create(['bed_number' => 'B-001', 'ward_name' => 'Ward A', 'bed_type_id' => $type->id]);
        $this->assertDatabaseHas('beds', ['bed_number' => 'B-001']);
        $bed->update(['ward_name' => 'Ward B']);
        $bed->delete();
    }

    /** @test */
    public function lab_requests()
    {
        $cat = LabCategory::create(['name' => 'Pathology']);
        $test = LabTest::create(['test_name' => 'CBC', 'price' => 500, 'is_active' => true, 'category_id' => $cat->id]);
        $req = LabRequest::create(['request_number' => 'LAB-001', 'patient_id' => $this->patient->id, 'doctor_id' => $this->doctor->id, 'request_date' => now()]);
        $this->assertDatabaseHas('lab_requests', ['request_number' => 'LAB-001']);
        $req->delete();
    }

    /** @test */
    public function radiology_requests()
    {
        $cat = RadiologyCategory::create(['name' => 'Imaging']);
        $test = RadiologyTest::create(['test_name' => 'X-Ray', 'price' => 1000, 'is_active' => true, 'category_id' => $cat->id]);
        $req = RadiologyRequest::create(['request_number' => 'RAD-001', 'patient_id' => $this->patient->id, 'doctor_id' => $this->doctor->id, 'radiology_test_id' => $test->id, 'request_date' => now()]);
        $this->assertDatabaseHas('radiology_requests', ['request_number' => 'RAD-001']);
        $req->delete();
    }

    /** @test */
    public function payments()
    {
        $inv = Invoice::create(['invoice_number' => 'INV-001', 'patient_id' => $this->patient->id, 'total_amount' => 5000, 'paid_amount' => 0, 'balance_amount' => 5000, 'status' => 'pending', 'invoice_date' => now(), 'due_date' => now()->addDays(30)]);
        $pay = Payment::create(['invoice_id' => $inv->id, 'patient_id' => $this->patient->id, 'amount' => 2000, 'payment_method' => 'cash', 'payment_date' => now()]);
        $this->assertDatabaseHas('payments', ['amount' => 2000]);
        $pay->delete();
    }

    /** @test */
    public function blood_donors()
    {
        $group = BloodGroup::create(['name' => 'A+', 'code' => 'A+']);
        $donor = BloodDonor::create(['donor_id' => 'DON-001', 'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@test.com', 'phone' => '0712345678', 'date_of_birth' => '1990-01-01', 'gender' => 'female', 'blood_group_id' => $group->id, 'address' => 'Nairobi']);
        $this->assertDatabaseHas('blood_donors', ['donor_id' => 'DON-001']);
        $donor->delete();
    }

    /** @test */
    public function ambulance()
    {
        $amb = Ambulance::create(['vehicle_number' => 'KBA-001', 'driver_name' => 'Driver', 'driver_phone' => '0712345678', 'vehicle_type' => 'basic']);
        $this->assertDatabaseHas('ambulances', ['vehicle_number' => 'KBA-001']);
        $amb->delete();
    }

    /** @test */
    public function queue()
    {
        $q = QueueManagement::create(['patient_id' => $this->patient->id, 'department' => 'OPD', 'status' => 'waiting', 'priority' => 'normal', 'queue_number' => 'Q-001', 'patient_name' => 'John Doe', 'queue_type' => 'opd', 'check_in_time' => now()]);
        $this->assertNotNull($q->id);
        $q->update(['status' => 'serving']);
    }

    /** @test */
    public function operation_reports()
    {
        $r = OperationReport::create(['report_number' => 'OPR-001', 'patient_id' => $this->patient->id, 'operation_name' => 'Appendectomy', 'operation_description' => 'Surgery', 'operation_date' => now(), 'surgeon_id' => $this->doctor->id, 'start_time' => '08:00', 'end_time' => '10:00', 'duration_minutes' => 120, 'operation_notes' => 'Notes', 'outcome' => 'Successful']);
        $this->assertDatabaseHas('operation_reports', ['operation_name' => 'Appendectomy']);
        $r->delete();
    }

    /** @test */
    public function case_handlers()
    {
        $h = CaseHandler::create(['first_name' => 'Social', 'last_name' => 'Worker', 'email' => 'sw@test.com', 'phone' => '0712345678', 'handler_id' => 'CH-001', 'specialization' => 'Social Work', 'qualifications' => 'MSW', 'address' => 'Nairobi', 'joining_date' => now()->toDateString()]);
        $this->assertDatabaseHas('case_handlers', ['first_name' => 'Social']);
        $h->delete();
    }

    /** @test */
    public function visitors()
    {
        $v = VisitorLog::create(['visitor_name' => 'Visitor A', 'visitor_phone' => '0712345678', 'visitor_type' => 'visitor', 'purpose' => 'Visit', 'check_in_time' => now()]);
        $this->assertDatabaseHas('visitor_logs', ['visitor_name' => 'Visitor A']);
        $v->update(['visitor_name' => 'Visitor B']);
    }

    /** @test */
    public function discharge_summary()
    {
        $a = IpdAdmission::create(['patient_id' => $this->patient->id, 'doctor_id' => $this->doctor->id, 'status' => 'discharged', 'discharge_date' => now(), 'diagnosis' => 'Appendicitis', 'admission_date' => now()->subDays(3)]);
        $this->assertDatabaseHas('ipd_admissions', ['status' => 'discharged']);
        $a->delete();
    }
}
