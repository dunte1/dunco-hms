<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\EmployeeDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class HrController extends Controller
{
    public function index(): View
    {
        // Calculate real HR statistics
        $totalEmployees = Employee::where('status', 'active')->count();
        $totalDoctors = Employee::where('status', 'active')->where('position', 'like', '%Doctor%')->count();
        $totalNurses = Employee::where('status', 'active')->where('position', 'like', '%Nurse%')->count();
        $totalAdmin = Employee::where('status', 'active')->where('position', 'like', '%Admin%')->count();
        
        $stats = [
            ['label' => 'Total Employees', 'value' => $totalEmployees],
            ['label' => 'Doctors', 'value' => $totalDoctors],
            ['label' => 'Nurses', 'value' => $totalNurses],
            ['label' => 'Admin Staff', 'value' => $totalAdmin],
            ['label' => 'Present Today', 'value' => Attendance::whereDate('date', today())->where('status', 'present')->count()],
            ['label' => 'On Leave', 'value' => LeaveRequest::where('status', 'approved')->where('start_date', '<=', today())->where('end_date', '>=', today())->count()],
            ['label' => 'Departments', 'value' => EmployeeDepartment::count()],
            ['label' => 'Pending Leaves', 'value' => LeaveRequest::where('status', 'pending')->count()],
        ];

        // Chart data - Staff distribution by department
        $departmentDistribution = Employee::selectRaw('employee_departments.name as department, COUNT(*) as count')
            ->join('employee_departments', 'employees.department_id', '=', 'employee_departments.id')
            ->where('employees.status', 'active')
            ->groupBy('employee_departments.name')
            ->get();
        
        $deptChartLabels = $departmentDistribution->pluck('department')->toArray();
        $deptChartData = $departmentDistribution->pluck('count')->toArray();

        // Gender ratio chart
        $genderDistribution = Employee::selectRaw('gender, COUNT(*) as count')
            ->where('status', 'active')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();
        
        $genderChartLabels = $genderDistribution->pluck('gender')->map(fn($g) => ucfirst($g))->toArray();
        $genderChartData = $genderDistribution->pluck('count')->toArray();

        // Employment type statistics
        $employmentTypeStats = Employee::selectRaw('employment_type, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('employment_type')
            ->get();
        
        $empTypeLabels = $employmentTypeStats->pluck('employment_type')->map(fn($e) => ucfirst(str_replace('-', ' ', $e)))->toArray();
        $empTypeData = $employmentTypeStats->pluck('count')->toArray();

        // Attendance summary for this month
        $monthlyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(*) as count')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'present')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payroll summary
        $payrollSummary = [
            'this_month' => Payroll::whereMonth('pay_date', now()->month)->whereYear('pay_date', now()->year)->sum('net_salary') ?? 0,
            'pending' => Payroll::where('status', 'pending')->count(),
        ];

        // Contract expirations (next 30 days)
        $contractExpirations = Employee::where('status', 'active')
            ->whereNotNull('contract_end_date')
            ->whereBetween('contract_end_date', [today(), today()->addDays(30)])
            ->orderBy('contract_end_date')
            ->take(10)
            ->get();

        // Get recent attendance
        $recentAttendance = Attendance::with('user')
            ->whereDate('date', today())
            ->latest()
            ->take(10)
            ->get();

        // Get pending leave requests
        $pendingLeaves = LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming birthdays (next 7 days)
        $upcomingBirthdays = Employee::whereRaw('DAYOFYEAR(date_of_birth) BETWEEN DAYOFYEAR(NOW()) AND DAYOFYEAR(NOW()) + 7')
            ->where('status', 'active')
            ->take(5)
            ->get();

        return view('hms.hr.index', compact(
            'stats', 'recentAttendance', 'pendingLeaves', 'upcomingBirthdays',
            'deptChartLabels', 'deptChartData',
            'genderChartLabels', 'genderChartData',
            'empTypeLabels', 'empTypeData',
            'monthlyAttendance', 'payrollSummary', 'contractExpirations'
        ));
    }

    // Employee Management
    public function employees(): View
    {
        $employees = Employee::with('department')
            ->latest()
            ->paginate(20);
        
        $departments = EmployeeDepartment::all();
        
        return view('hms.hr.employees.index', compact('employees', 'departments'));
    }

    public function createEmployee(): View
    {
        $departments = EmployeeDepartment::all();
        return view('hms.hr.employees.create', compact('departments'));
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'department_id' => 'required|exists:employee_departments,id',
            'position' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,intern',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'emergency_contact' => 'nullable|string',
        ]);

        Employee::create($validated);

        return redirect()->route('hms.hr.employees')
            ->with('success', 'Employee added successfully.');
    }

    public function showEmployee(Employee $employee): View
    {
        $employee->load('department');
        
        // Get related data
        $attendance = Attendance::where('employee_id', $employee->id)
            ->latest()
            ->take(10)
            ->get();
        
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();
        
        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();
        
        return view('hms.hr.employees.show', compact('employee', 'attendance', 'payrolls', 'leaveRequests'));
    }

    public function editEmployee(Employee $employee): View
    {
        $employee->load('user');
        $departments = EmployeeDepartment::all();
        return view('hms.hr.employees.edit', compact('employee', 'departments'));
    }

    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
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
            'contract_type' => 'nullable|string',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
            'create_user_account' => 'nullable|boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo && \Storage::disk('public')->exists($employee->photo)) {
                \Storage::disk('public')->delete($employee->photo);
            }
            
            $photo = $request->file('photo');
            $photoName = 'employee_' . time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('employees/photos', $photoName, 'public');
            $validated['photo'] = $photoPath;
        }

        $employee->update($validated);

        // Handle user account password update or creation
        if ($employee->user_id && $employee->user) {
            // Update password if provided
            if (!empty($request->password)) {
                $employee->user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]);
            }
        } elseif ($request->has('create_user_account') && $request->create_user_account == '1') {
            // Create new user account
            $user = \App\Models\User::create([
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'email' => $employee->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password ?? 'password123'), // Default password if not provided
                'email_verified_at' => now(),
            ]);
            
            // Link employee to user
            $employee->update(['user_id' => $user->id]);
        }

        return redirect()->route('hms.hr.employees.index')
            ->with('status', 'Employee updated successfully!');
    }

    public function destroyEmployee(Employee $employee): RedirectResponse
    {
        $employee->delete();
        return redirect()->route('hms.hr.employees')
            ->with('success', 'Employee deleted successfully.');
    }

    // Attendance Management
    public function attendance(): View
    {
        $attendance = Attendance::with('employee')
            ->latest()
            ->paginate(20);
        
        return view('hms.hr.attendance.index', compact('attendance'));
    }

    public function markAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,absent,late,early_leave',
            'notes' => 'nullable|string',
        ]);

        // Calculate hours worked if check_out is provided
        if ($validated['check_out']) {
            $checkIn = \Carbon\Carbon::parse($validated['attendance_date'] . ' ' . $validated['check_in']);
            $checkOut = \Carbon\Carbon::parse($validated['attendance_date'] . ' ' . $validated['check_out']);
            $validated['hours_worked'] = $checkOut->diffInHours($checkIn);
        }

        Attendance::create($validated);

        return redirect()->route('hms.hr.attendance')
            ->with('success', 'Attendance marked successfully.');
    }

    // Leave Management
    public function leaveRequests(): View
    {
        $leaveRequests = LeaveRequest::with('employee')
            ->latest()
            ->paginate(20);
        
        return view('hms.hr.leave-requests.index', compact('leaveRequests'));
    }

    public function approveLeave(LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hms.hr.leave-requests')
            ->with('success', 'Leave request approved successfully.');
    }

    public function rejectLeave(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hms.hr.leave-requests')
            ->with('success', 'Leave request rejected.');
    }

    // Payroll Management
    public function payroll(): View
    {
        $payrolls = Payroll::with('employee')
            ->latest()
            ->paginate(20);
        
        return view('hms.hr.payroll.index', compact('payrolls'));
    }

    public function generatePayroll(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payroll_period' => 'required|string',
            'pay_date' => 'required|date',
        ]);

        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            Payroll::create([
                'employee_id' => $employee->id,
                'payroll_period' => $validated['payroll_period'],
                'pay_date' => $validated['pay_date'],
                'basic_salary' => $employee->salary,
                'overtime_pay' => 0,
                'bonus' => 0,
                'allowances' => 0,
                'deductions' => 0,
                'gross_salary' => $employee->salary,
                'net_salary' => $employee->salary,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('hms.hr.payroll')
            ->with('success', 'Payroll generated successfully.');
    }

    // Reports
    public function reports(): View
    {
        $monthlyAttendance = Attendance::selectRaw('MONTH(attendance_date) as month, COUNT(*) as count')
            ->whereYear('attendance_date', now()->year)
            ->groupBy('month')
            ->get();

        $departmentStats = Employee::selectRaw('employee_departments.name as department, COUNT(*) as count')
            ->join('employee_departments', 'employees.department_id', '=', 'employee_departments.id')
            ->where('employees.status', 'active')
            ->groupBy('employee_departments.name')
            ->get();

        $leaveStats = LeaveRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('hms.hr.reports', compact('monthlyAttendance', 'departmentStats', 'leaveStats'));
    }
}


