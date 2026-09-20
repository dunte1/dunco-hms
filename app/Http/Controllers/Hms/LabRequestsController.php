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
use Barryvdh\DomPDF\Facade\Pdf;

class LabRequestsController extends Controller
{
    public function index(): View
    {
        $labRequests = LabRequest::with(['patient', 'doctor', 'items.labTest'])->latest('request_date')->paginate(10);
        return view('hms.laboratory.requests.index', compact('labRequests'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone']);
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
            'billing_mode' => 'required|in:sha,mpesa,cash',
            'payment_id' => 'nullable|integer|exists:payments,id',
        ]);

        $total = LabTest::whereIn('id', $data['lab_tests'])->sum('price');

        $mpesa = app(\App\Services\MpesaService::class);

        if ($data['billing_mode'] === 'mpesa') {
            if (empty($data['payment_id']) || !$mpesa->hasConfirmedPayment($data['patient_id'], $data['payment_id'], $total)) {
                return back()->withErrors(['payment_id' => 'A completed M-Pesa payment for the full lab amount is required to create the request.'])
                    ->withInput();
            }
        } elseif ($data['billing_mode'] === 'sha') {
            $coverage = $mpesa->coverage(Patient::findOrFail($data['patient_id']), 'lab_test');
            if (!$coverage['covered']) {
                return back()->withErrors(['billing_mode' => 'This patient is not covered under SHA for lab tests. Please collect M-Pesa or cash payment instead.'])
                    ->withInput();
            }
            $data['payment_id'] = null;
        } else {
            $data['payment_id'] = null;
        }

        // Generate request number
        $data['request_number'] = 'LAB-' . date('Y') . '-' . str_pad(LabRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        $labRequest = LabRequest::create($data);

        // Create lab request items
        foreach ($data['lab_tests'] as $testId) {
            $labRequest->items()->create([
                'lab_test_id' => $testId,
            ]);
        }

        if (!empty($data['payment_id'])) {
            $mpesa->attachSource($data['payment_id'], 'lab_request', $labRequest->id);
        }

        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request created');
    }

    public function show(LabRequest $labRequest): View
    {
        $labRequest->load(['patient', 'doctor', 'items.labTest']);
        return view('hms.laboratory.requests.show', compact('labRequest'));
    }

    public function edit(LabRequest $labRequest): View
    {
        $labRequest->load(['items.labTest']);
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $labTests = LabTest::where('is_active', true)->orderBy('test_name')->get(['id', 'test_name', 'price']);
        return view('hms.laboratory.requests.edit', compact('labRequest', 'patients', 'doctors', 'labTests'));
    }

    public function update(Request $request, LabRequest $labRequest): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'request_date' => 'required|date',
            'clinical_notes' => 'nullable|string',
            'lab_tests' => 'required|array|min:1',
            'lab_tests.*' => 'exists:lab_tests,id',
        ]);

        $labRequest->update($data);

        // Sync lab request items
        $labRequest->items()->delete();
        foreach ($data['lab_tests'] as $testId) {
            $labRequest->items()->create([
                'lab_test_id' => $testId,
            ]);
        }

        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request updated');
    }

    public function destroy(LabRequest $labRequest): RedirectResponse
    {
        $labRequest->items()->delete();
        $labRequest->delete();
        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request deleted');
    }

    public function reportPdf(LabRequest $labRequest)
    {
        $labRequest->load(['patient', 'doctor', 'items.labTest']);
        $pdf = PDF::loadView('hms.laboratory.lab-report-pdf', compact('labRequest'));
        return $pdf->download("Lab-Report-{$labRequest->request_number}.pdf");
    }
}
