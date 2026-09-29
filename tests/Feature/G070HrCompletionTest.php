<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Payroll;
use App\Models\EmployeeContract;
use App\Models\DisciplinaryRecord;
use App\Models\StaffLicence;
use App\Models\PayrollExport;
use App\Models\StaffDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class G070HrCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $department = EmployeeDepartment::create(['name' => 'Clinical', 'description' => 'Clinical dept']);

        $this->employee = Employee::create([
            'employee_id' => 'EMP-2026-0001',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0712345678',
            'date_of_birth' => '1990-01-15',
            'gender' => 'female',
            'address' => '123 Nairobi Rd',
            'department_id' => $department->id,
            'position' => 'Nurse',
            'employment_type' => 'full_time',
            'hire_date' => '2024-01-01',
            'salary' => 80000,
            'status' => 'active',
        ]);
    }

    public function test_contract_creation(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/hr/contracts', [
            'employee_id' => $this->employee->id,
            'contract_type' => 'permanent',
            'start_date' => '2026-01-01',
            'end_date' => null,
            'salary' => 80000,
            'salary_type' => 'monthly',
            'notes' => 'Initial permanent contract',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employee_contracts', [
            'employee_id' => $this->employee->id,
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);
    }

    public function test_contract_listing(): void
    {
        EmployeeContract::create([
            'employee_id' => $this->employee->id,
            'contract_type' => 'temporary',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(6)->toDateString(),
            'salary' => 60000,
            'salary_type' => 'monthly',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get('/hms/hr/contracts');
        $response->assertOk();
    }

    public function test_disciplinary_record_workflow(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/hr/disciplinary', [
            'employee_id' => $this->employee->id,
            'incident_date' => now()->toDateString(),
            'description' => 'Repeated tardiness without justification.',
            'category' => 'warning',
            'severity' => 'minor',
            'action_taken' => 'Verbal warning issued.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('disciplinary_records', [
            'employee_id' => $this->employee->id,
            'status' => 'open',
        ]);

        $record = DisciplinaryRecord::first();
        $response = $this->actingAs($this->user)->post("/hms/hr/disciplinary/{$record->id}/resolve", [
            'resolution_notes' => 'Employee acknowledged the issue and committed to improvement.',
        ]);

        $response->assertRedirect();
        $record->refresh();
        $this->assertEquals('resolved', $record->status);
        $this->assertNotNull($record->resolution_date);
    }

    public function test_licence_creation_with_expiry_tracking(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/hr/licences', [
            'employee_id' => $this->employee->id,
            'licence_type' => 'nursing',
            'licence_number' => 'NCK-99887',
            'issuing_body' => 'Nursing Council of Kenya',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addMonths(2)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('staff_licences', [
            'employee_id' => $this->employee->id,
            'licence_number' => 'NCK-99887',
            'status' => 'active',
        ]);
    }

    public function test_expiring_licences_query(): void
    {
        StaffLicence::create([
            'employee_id' => $this->employee->id,
            'licence_type' => 'medical',
            'licence_number' => 'KMPDC-11111',
            'issuing_body' => 'KMPDC',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addDays(15)->toDateString(),
            'status' => 'active',
        ]);

        StaffLicence::create([
            'employee_id' => $this->employee->id,
            'licence_type' => 'nursing',
            'licence_number' => 'NCK-22222',
            'issuing_body' => 'Nursing Council of Kenya',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addDays(60)->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get('/hms/hr/licences/expiring');
        $response->assertOk();
    }

    public function test_payroll_export_creation(): void
    {
        Payroll::create([
            'employee_id' => $this->employee->id,
            'payroll_period' => 'September 2026',
            'pay_date' => '2026-09-30',
            'basic_salary' => 80000,
            'overtime_pay' => 5000,
            'bonus' => 0,
            'allowances' => 10000,
            'deductions' => 12000,
            'gross_salary' => 95000,
            'net_salary' => 83000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post('/hms/hr/payroll-export', [
            'export_month' => 9,
            'export_year' => 2026,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_exports', [
            'export_month' => 9,
            'export_year' => 2026,
            'employee_count' => 1,
            'status' => 'draft',
        ]);
    }

    public function test_staff_document_upload(): void
    {
        $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post('/hms/hr/staff-documents', [
            'employee_id' => $this->employee->id,
            'document_type' => 'contract',
            'title' => 'Employment Contract 2026',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('staff_documents', [
            'employee_id' => $this->employee->id,
            'document_type' => 'contract',
            'title' => 'Employment Contract 2026',
            'uploaded_by' => $this->user->id,
        ]);
    }

    public function test_staff_documents_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/hr/staff-documents');
        $response->assertOk();
    }

    public function test_payroll_export_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/hr/payroll-export');
        $response->assertOk();
    }
}
