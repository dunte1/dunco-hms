<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $attendance = Attendance::with('user')->latest('date')->paginate(10);
        return view('hms.hr.attendance.index', compact('attendance'));
    }

    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.hr.attendance.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        // Calculate hours worked if both check_in and check_out are provided
        if ($data['check_in'] && $data['check_out']) {
            $checkIn = \Carbon\Carbon::parse($data['attendance_date'] . ' ' . $data['check_in']);
            $checkOut = \Carbon\Carbon::parse($data['attendance_date'] . ' ' . $data['check_out']);
            $data['hours_worked'] = $checkOut->diffInMinutes($checkIn);
        } else {
            $data['hours_worked'] = 0;
        }

        Attendance::create($data);
        return redirect()->route('hms.hr.attendance.index')->with('status', 'Attendance recorded');
    }
}
