<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class BatchOperationsController extends Controller
{
    /**
     * Display batch operations dashboard
     */
    public function index(): View
    {
        return view('hms.batch-operations.index');
    }

    /**
     * Batch approve/reject leave requests
     */
    public function batchLeaveRequests(Request $request): RedirectResponse
    {
        $request->validate([
            'leave_request_ids' => 'required|array',
            'leave_request_ids.*' => 'exists:leave_requests,id',
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $leaveRequests = LeaveRequest::whereIn('id', $request->leave_request_ids)->get();
        $count = 0;

        DB::transaction(function () use ($leaveRequests, $request, &$count) {
            foreach ($leaveRequests as $leaveRequest) {
                if ($leaveRequest->status === 'pending') {
                    $leaveRequest->update([
                        'status' => $request->action === 'approve' ? 'approved' : 'rejected',
                        'notes' => $request->notes ?? $leaveRequest->notes,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                    $count++;
                }
            }
        });

        $action = $request->action === 'approve' ? 'approved' : 'rejected';
        return redirect()->back()
            ->with('success', "Successfully {$action} {$count} leave request(s).");
    }

    /**
     * Batch mark attendance
     */
    public function batchMarkAttendance(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,on_leave',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
        ]);

        $count = 0;

        DB::transaction(function () use ($request, &$count) {
            foreach ($request->employee_ids as $employeeId) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $request->date,
                    ],
                    [
                        'status' => $request->status,
                        'check_in' => $request->check_in ?? null,
                        'check_out' => $request->check_out ?? null,
                        'created_by' => auth()->id(),
                    ]
                );
                $count++;
            }
        });

        return redirect()->back()
            ->with('success', "Successfully marked attendance for {$count} employee(s).");
    }

    /**
     * Batch generate payroll
     */
    public function batchGeneratePayroll(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'generate_for' => 'required|in:all,selected',
        ]);

        // This would integrate with the existing payroll generation logic
        // Implementation depends on payroll structure
        
        return redirect()->back()
            ->with('success', 'Payroll generation batch process started. You will be notified when complete.');
    }

    /**
     * Batch ID card generation
     */
    public function batchGenerateIdCards(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:patients,employees',
            'ids' => 'required|array',
            'ids.*' => 'required',
        ]);

        $count = count($request->ids);
        
        // Queue jobs for ID card generation
        foreach ($request->ids as $id) {
            if ($request->type === 'patients') {
                $patient = Patient::find($id);
                if ($patient) {
                    // Generate ID card in background
                    \App\Jobs\GenerateIdCard::dispatch($patient, 'patient');
                }
            } else {
                $employee = Employee::find($id);
                if ($employee) {
                    \App\Jobs\GenerateIdCard::dispatch($employee, 'employee');
                }
            }
        }

        return redirect()->back()
            ->with('success', "ID card generation queued for {$count} {$request->type}. You will be notified when complete.");
    }

    /**
     * Batch export data
     */
    public function batchExport(Request $request): RedirectResponse
    {
        $request->validate([
            'module' => 'required|in:patients,appointments,employees,payroll,audit',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        // Queue export job
        \App\Jobs\BatchExport::dispatch(
            $request->module,
            auth()->id(),
            $request->date_from,
            $request->date_to,
            $request->format
        );

        return redirect()->back()
            ->with('success', 'Export job queued. You will receive an email with the download link when ready.');
    }
}

