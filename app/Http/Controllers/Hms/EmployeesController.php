<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
        $employees = Employee::orderBy('first_name')->select('id', 'first_name', 'last_name')->get();
        return view('hms.hr.employees.create', compact('departments', 'employees'));
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id' => 'required|exists:employee_departments,id',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string',
            'nationality' => 'nullable|string',
            'id_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_branch' => 'nullable|string',
            'next_of_kin_name' => 'nullable|string',
            'next_of_kin_relationship' => 'nullable|string',
            'next_of_kin_contact' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:employees,id',
            'contract_type' => 'nullable|in:permanent,contract,temporary',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'create_user_account' => 'nullable|boolean',
            'password' => 'required_if:create_user_account,1|nullable|string|min:8|confirmed',
        ]);

        // Generate employee ID
        $data['employee_id'] = 'EMP-' . date('Y') . '-' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = 'employee_' . time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('employees/photos', $photoName, 'public');
            $data['photo'] = $photoPath;
        }

        // Create user account if requested
        if ($request->has('create_user_account') && $request->create_user_account) {
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($request->password),
                'phone' => $data['phone'] ?? null,
                'email_verified_at' => now(),
            ]);
            
            $data['user_id'] = $user->id;
        }

        // Remove user account creation fields from employee data
        unset($data['create_user_account'], $data['password'], $data['password_confirmation']);

        $employee = Employee::create($data);
        
        $message = 'Employee added successfully! Employee ID: ' . $employee->employee_id;
        if ($employee->user_id) {
            $message .= ' (Login account created)';
        }
        
        return redirect()->route('hms.hr.employees.index')->with('success', $message);
    }
}
