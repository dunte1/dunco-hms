<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RadiologyRequest;
use App\Models\RadiologyTest;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadiologyRequestsController extends Controller
{
    public function index(): View
    {
        $radiologyRequests = RadiologyRequest::with(['patient', 'doctor', 'radiologyTest'])->latest('request_date')->paginate(10);
        return view('hms.radiology.requests.index', compact('radiologyRequests'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $radiologyTests = RadiologyTest::where('is_active', true)->orderBy('test_name')->get(['id', 'test_name', 'price']);
        return view('hms.radiology.requests.create', compact('patients', 'doctors', 'radiologyTests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'radiology_test_id' => 'required|exists:radiology_tests,id',
            'request_date' => 'required|date',
            'appointment_date' => 'nullable|date|after_or_equal:request_date',
            'clinical_notes' => 'nullable|string',
        ]);

        // Generate request number
        $data['request_number'] = 'RAD-' . date('Y') . '-' . str_pad(RadiologyRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        RadiologyRequest::create($data);
        return redirect()->route('hms.radiology.requests.index')->with('status', 'Radiology request created');
    }

    public function show(RadiologyRequest $radiologyRequest): View
    {
        $radiologyRequest->load(['patient', 'doctor', 'radiologyTest']);
        return view('hms.radiology.requests.show', compact('radiologyRequest'));
    }

    public function edit(RadiologyRequest $radiologyRequest): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $radiologyTests = RadiologyTest::where('is_active', true)->orderBy('test_name')->get(['id', 'test_name', 'price']);
        return view('hms.radiology.requests.edit', compact('radiologyRequest', 'patients', 'doctors', 'radiologyTests'));
    }

    public function update(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'radiology_test_id' => 'required|exists:radiology_tests,id',
            'request_date' => 'required|date',
            'appointment_date' => 'nullable|date|after_or_equal:request_date',
            'clinical_notes' => 'nullable|string',
        ]);

        $radiologyRequest->update($data);
        return redirect()->route('hms.radiology.requests.index')->with('status', 'Radiology request updated');
    }

    public function destroy(RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $radiologyRequest->delete();
        return redirect()->route('hms.radiology.requests.index')->with('status', 'Radiology request deleted');
    }
}
