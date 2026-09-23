<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveBalanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = LeaveBalance::with(['employee', 'leaveType']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        $balances = $query->orderBy('year', 'desc')
            ->orderBy('employee_id')
            ->paginate(20)
            ->withQueryString();

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('hms.hr.leave-balances.index', compact('balances', 'employees', 'leaveTypes'));
    }

    public function employeeBalance(Employee $employee): View
    {
        $year = request('year', date('Y'));
        $balances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->where('year', $year)
            ->get();

        return view('hms.hr.leave-balances.employee', compact('employee', 'balances', 'year'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'year' => 'required|integer|min:2020|max:2100',
            'entitled_days' => 'required|integer|min:0',
            'carried_forward_days' => 'nullable|integer|min:0',
        ]);

        $data['carried_forward_days'] = $data['carried_forward_days'] ?? 0;

        LeaveBalance::updateOrCreate(
            [
                'employee_id' => $data['employee_id'],
                'leave_type_id' => $data['leave_type_id'],
                'year' => $data['year'],
            ],
            [
                'entitled_days' => $data['entitled_days'],
                'carried_forward_days' => $data['carried_forward_days'],
            ]
        );

        return redirect()->route('hms.hr.leave-balances.index')
            ->with('success', 'Leave balance saved successfully.');
    }

    public function seedBalances(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $year = $data['year'];
        $employees = Employee::where('status', 'active')->get();
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $created = 0;

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $leaveType) {
                $record = LeaveBalance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $year,
                    ],
                    [
                        'entitled_days' => $leaveType->default_days,
                        'carried_forward_days' => 0,
                        'used_days' => 0,
                    ]
                );
                if ($record->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        return redirect()->route('hms.hr.leave-balances.index')
            ->with('success', "Created {$created} new leave balance records for {$year}.");
    }
}
