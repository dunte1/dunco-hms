<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeesController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with('department')->orderBy('first_name')->paginate(10);
        return view('hms.hr.employees.index', compact('employees'));
    }

    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->pluck('name', 'id');
        return view('hms.hr.employees.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'department_id' => 'required|exists:employee_departments,id',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string',
        ]);

        // Generate employee ID
        $data['employee_id'] = 'EMP-' . date('Y') . '-' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

        Employee::create($data);
        return redirect()->route('hms.hr.employees.index')->with('status', 'Employee added');
    }
}
