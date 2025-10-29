<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OperationReport;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Nurse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationReportsController extends Controller
{
    public function index(): View
    {
        $operations = OperationReport::with(['patient', 'surgeon', 'assistantDoctor', 'anesthesiologist', 'nurse'])
            ->latest('operation_date')
            ->paginate(10);
        return view('hms.operations.index', compact('operations'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.operations.create', compact('patients', 'doctors', 'nurses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'operation_name' => 'required|string',
            'operation_description' => 'required|string',
            'operation_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'surgeon_id' => 'required|exists:doctors,id',
            'assistant_doctor_id' => 'nullable|exists:doctors,id',
            'anesthesiologist_id' => 'nullable|exists:doctors,id',
            'nurse_id' => 'nullable|exists:nurses,id',
            'anesthesia_type' => 'nullable|string',
            'pre_operation_notes' => 'nullable|string',
            'operation_notes' => 'required|string',
            'post_operation_notes' => 'nullable|string',
            'complications' => 'nullable|string',
            'outcome' => 'required|in:successful,complications,unsuccessful',
            'follow_up_instructions' => 'nullable|string',
        ]);

        // Calculate duration
        $startTime = \Carbon\Carbon::parse($data['start_time']);
        $endTime = \Carbon\Carbon::parse($data['end_time']);
        $data['duration_minutes'] = $endTime->diffInMinutes($startTime);

        $data['report_number'] = 'OP-' . date('Y') . '-' . str_pad(OperationReport::count() + 1, 6, '0', STR_PAD_LEFT);

        OperationReport::create($data);
        return redirect()->route('hms.operations.index')->with('status', 'Operation report created');
    }
}
