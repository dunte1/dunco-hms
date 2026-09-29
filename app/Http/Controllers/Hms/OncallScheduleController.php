<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OncallSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OncallScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $query = OncallSchedule::with('doctor', 'department');

        if ($request->filled('date')) {
            $query->where('oncall_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $schedules = $query->orderBy('oncall_date', 'desc')->orderBy('start_time')->paginate(15);

        return view('hms.credentialing.oncall', compact('schedules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'department_id' => 'nullable|exists:employee_departments,id',
            'oncall_date' => 'required|date',
            'shift_type' => 'required|in:day,night,24h',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'scheduled';

        OncallSchedule::create($data);

        return redirect()->route('hms.credentialing.oncall.index')
            ->with('success', 'On-call schedule created successfully!');
    }
}
