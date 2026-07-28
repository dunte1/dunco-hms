<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeesImportExportController extends Controller
{
    /**
     * Export employees to CSV/Excel
     */
    public function export(Request $request)
    {
        $employees = Employee::with('department')
            ->when($request->department_id, function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        $filename = 'employees_export_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Employee ID', 'First Name', 'Last Name', 'Email', 'Phone',
                'Date of Birth', 'Gender', 'Department', 'Position', 'Employment Type',
                'Hire Date', 'Salary', 'Status', 'Nationality', 'ID Number',
                'Bank Name', 'Account Number', 'Supervisor ID'
            ]);
            
            // Data
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_id,
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email,
                    $employee->phone ?? '',
                    $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '',
                    $employee->gender ?? '',
                    $employee->department->name ?? '',
                    $employee->position,
                    $employee->employment_type,
                    $employee->hire_date->format('Y-m-d'),
                    $employee->salary ?? 0,
                    $employee->status,
                    $employee->nationality ?? '',
                    $employee->id_number ?? '',
                    $employee->bank_name ?? '',
                    $employee->account_number ?? '',
                    $employee->supervisor_id ?? '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        return view('hms.hr.employees.import');
    }

    /**
     * Import employees from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            if (empty($data)) {
                return redirect()->back()->with('error', 'File is empty or invalid.');
            }

            $headers = array_shift($data); // Remove header row
            $rows = $data;

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                try {
                    // Map Excel columns to employee fields
                    $employeeData = [
                        'first_name' => $row[1] ?? null,
                        'last_name' => $row[2] ?? null,
                        'email' => $row[3] ?? null,
                        'phone' => $row[4] ?? null,
                        'date_of_birth' => !empty($row[5]) ? \Carbon\Carbon::parse($row[5]) : null,
                        'gender' => strtolower($row[6] ?? ''),
                        'position' => $row[8] ?? null,
                        'employment_type' => str_replace(' ', '_', strtolower($row[9] ?? 'full_time')),
                        'hire_date' => !empty($row[10]) ? \Carbon\Carbon::parse($row[10]) : now(),
                        'salary' => $row[11] ?? 0,
                        'status' => strtolower($row[12] ?? 'active'),
                        'nationality' => $row[13] ?? null,
                        'id_number' => $row[14] ?? null,
                        'bank_name' => $row[15] ?? null,
                        'account_number' => $row[16] ?? null,
                    ];

                    // Find department by name
                    if (!empty($row[7])) {
                        $department = EmployeeDepartment::where('name', $row[7])->first();
                        if ($department) {
                            $employeeData['department_id'] = $department->id;
                        } else {
                            $errors[] = "Row " . ($index + 2) . ": Department '{$row[7]}' not found";
                            continue;
                        }
                    }

                    // Validate required fields
                    $validator = Validator::make($employeeData, [
                        'first_name' => 'required|string',
                        'last_name' => 'required|string',
                        'email' => 'required|email|unique:employees,email',
                        'department_id' => 'required|exists:employee_departments,id',
                        'position' => 'required|string',
                        'employment_type' => 'required',
                        'hire_date' => 'required|date',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Row " . ($index + 2) . ": " . $validator->errors()->first();
                        continue;
                    }

                    // Generate employee ID
                    $employeeData['employee_id'] = 'EMP-' . date('Y') . '-' . str_pad(Employee::count() + 1 + $imported, 4, '0', STR_PAD_LEFT);

                    Employee::create($employeeData);
                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} employees.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " errors occurred.";
            }

            return redirect()->route('hms.hr.employees.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download template for import
     */
    public function downloadTemplate()
    {
        $filename = 'employee_import_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Employee ID', 'First Name', 'Last Name', 'Email', 'Phone',
                'Date of Birth', 'Gender', 'Department', 'Position', 'Employment Type',
                'Hire Date', 'Salary', 'Status', 'Nationality', 'ID Number',
                'Bank Name', 'Account Number', 'Supervisor ID'
            ]);
            
            // Sample row
            fputcsv($file, [
                'EMP-2025-0001', 'John', 'Doe', 'john.doe@example.com', '+254712345678',
                '1990-01-15', 'male', 'Medical', 'Doctor', 'full_time',
                '2024-01-01', '50000', 'active', 'Kenyan', '12345678',
                'Equity Bank', '1234567890', ''
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

