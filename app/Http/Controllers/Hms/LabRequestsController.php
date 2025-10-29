<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabRequestsController extends Controller
{
    public function index(): View
    {
        $labRequests = LabRequest::with(['patient', 'doctor', 'items.labTest'])->latest('request_date')->paginate(10);
        return view('hms.laboratory.requests.index', compact('labRequests'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $labTests = LabTest::where('is_active', true)->orderBy('test_name')->get(['id', 'test_name', 'price']);
        return view('hms.laboratory.requests.create', compact('patients', 'doctors', 'labTests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'request_date' => 'required|date',
            'clinical_notes' => 'nullable|string',
            'lab_tests' => 'required|array|min:1',
            'lab_tests.*' => 'exists:lab_tests,id',
        ]);

        // Generate request number
        $data['request_number'] = 'LAB-' . date('Y') . '-' . str_pad(LabRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        $labRequest = LabRequest::create($data);

        // Create lab request items
        foreach ($data['lab_tests'] as $testId) {
            $labRequest->items()->create([
                'lab_test_id' => $testId,
            ]);
        }

        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request created');
    }

    public function show(LabRequest $labRequest): View
    {
        $labRequest->load(['patient', 'doctor', 'items.labTest']);
        return view('hms.laboratory.requests.show', compact('labRequest'));
    }
}
