<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
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

        LeaveRequest::create($data);
        return redirect()->route('hms.hr.leave-requests.index')->with('status', 'Leave request submitted');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'admin_notes' => $data['admin_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Leave request approved');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_notes' => $data['admin_notes'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Leave request rejected');
    }
}
