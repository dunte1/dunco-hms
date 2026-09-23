<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestsController extends Controller
{
    public function index(): View
    {
        $leaveRequests = LeaveRequest::with(['employee', 'approvedBy'])->latest('created_at')->paginate(10);
        return view('hms.hr.leave-requests.index', compact('leaveRequests'));
    }

    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.hr.leave-requests.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,vacation,personal,maternity,emergency',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = \Carbon\Carbon::parse($data['end_date']);
        $data['total_days'] = $startDate->diffInDays($endDate) + 1;

        // Check leave balance if leave_type_id is provided
        if (!empty($data['leave_type_id'])) {
            $year = $startDate->year;
            $balance = LeaveBalance::where('employee_id', $data['employee_id'])
                ->where('leave_type_id', $data['leave_type_id'])
                ->where('year', $year)
                ->first();

            if ($balance && $balance->available_days < $data['total_days']) {
                return back()->withInput()->with('error', "Insufficient leave balance. Remaining: {$balance->available_days} days, Requested: {$data['total_days']} days.");
            }
        }

        LeaveRequest::create($data);
        return redirect()->route('hms.hr.leave-requests.index')->with('status', 'Leave request submitted');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Hospital Admin', 'HR Officer'])) {
            return back()->with('error', 'Only HR Officers or Administrators can approve leave requests');
        }

        $data = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'admin_notes' => $data['admin_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'leave_approved', 'LeaveRequest', $leaveRequest->id, ['status' => 'pending'], ['status' => 'approved'], 'Leave request approved for employee ' . $leaveRequest->employee_id);

        // Increment used_days on the leave balance
        if ($leaveRequest->leave_type_id) {
            $year = $leaveRequest->start_date->year;
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)
                ->where('year', $year)
                ->first();

            if ($balance) {
                $balance->increment('used_days', $leaveRequest->total_days);
            }
        }

        return back()->with('status', 'Leave request approved');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Hospital Admin', 'HR Officer'])) {
            return back()->with('error', 'Only HR Officers or Administrators can reject leave requests');
        }

        $data = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_notes' => $data['admin_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'leave_rejected', 'LeaveRequest', $leaveRequest->id, ['status' => 'pending'], ['status' => 'rejected'], 'Leave request rejected for employee ' . $leaveRequest->employee_id);

        return back()->with('status', 'Leave request rejected');
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['employee', 'leaveType']);
        return view('hms.hr.leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest): View
    {
        $leaveTypes = \App\Models\LeaveType::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('first_name')->get();
        return view('hms.hr.leave-requests.edit', compact('leaveRequest', 'leaveTypes', 'employees'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $leaveRequest->update($data);
        return redirect()->route('hms.hr.leave-requests.index')->with('status', 'Leave request updated');
    }

    public function destroy(LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->delete();
        return redirect()->route('hms.hr.leave-requests.index')->with('status', 'Leave request deleted');
    }
}
