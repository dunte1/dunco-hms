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
        $stats = [
            ['label' => 'Total Employees', 'value' => Employee::where('status', 'active')->count()],
            ['label' => 'Present Today', 'value' => Attendance::whereDate('attendance_date', today())->where('status', 'present')->count()],
            ['label' => 'On Leave', 'value' => LeaveRequest::where('status', 'approved')->where('start_date', '<=', today())->where('end_date', '>=', today())->count()],
            ['label' => 'Departments', 'value' => EmployeeDepartment::count()],
        ];

        // Get recent attendance
        $recentAttendance = Attendance::with('employee')
            ->whereDate('attendance_date', today())
            ->latest()
            ->take(10)
            ->get();

        // Get pending leave requests
        $pendingLeaves = LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming birthdays
        $upcomingBirthdays = Employee::whereRaw('DAYOFYEAR(date_of_birth) BETWEEN DAYOFYEAR(NOW()) AND DAYOFYEAR(NOW()) + 7')
            ->where('status', 'active')
            ->take(5)
            ->get();

        return view('hms.hr.index', compact('stats', 'recentAttendance', 'pendingLeaves', 'upcomingBirthdays'));
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
        $departments = EmployeeDepartment::all();
        return view('hms.hr.employees.edit', compact('employee', 'departments'));
    }

    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
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

        $employee->update($validated);

        return redirect()->route('hms.hr.employees')
            ->with('success', 'Employee updated successfully.');
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


