<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\OpdVisit;
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
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
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

        // Update OPD visit status if linked
        if (!empty($data['opd_visit_id'])) {
            OpdVisit::where('id', $data['opd_visit_id'])->update(['status' => 'lab_pending']);
        }

        // Create lab request items
        foreach ($data['lab_tests'] as $testId) {
            $labRequest->items()->create([
                'lab_test_id' => $testId,
            ]);
        }

        if (!empty($data['payment_id'])) {
            $mpesa->attachSource($data['payment_id'], 'lab_request', $labRequest->id);
        }

        // Cash lab orders: auto-create invoice so finance/billing can reconcile
        if ($data['billing_mode'] === 'cash' && $total > 0) {
            try {
                $invoice = \App\Models\Invoice::create([
                    'invoice_number' => 'INV-LAB-' . $labRequest->request_number,
                    'patient_id' => $labRequest->patient_id,
                    'doctor_id' => $labRequest->doctor_id ?? null,
                    'invoice_date' => now()->toDateString(),
                    'due_date' => now()->addDays(7)->toDateString(),
                    'subtotal' => $total,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $total,
                    'paid_amount' => 0,
                    'balance_amount' => $total,
                    'status' => 'pending',
                    'notes' => 'Lab request ' . $labRequest->request_number,
                ]);

                foreach ($data['lab_tests'] as $testId) {
                    $test = \App\Models\LabTest::find($testId);
                    if (!$test) {
                        continue;
                    }
                    $invoice->items()->create([
                        'item_type' => 'lab_test',
                        'item_name' => $test->test_name,
                        'description' => 'Lab test for ' . $labRequest->request_number,
                        'quantity' => 1,
                        'unit_price' => $test->price,
                        'total_price' => $test->price,
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Lab invoice auto-create failed', [
                    'lab_request_id' => $labRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request created');
    }

    public function show(LabRequest $labRequest): View
    {
        $this->authorize('view', $labRequest);

        $labRequest->load(['patient', 'doctor', 'items.labTest']);
        return view('hms.laboratory.requests.show', compact('labRequest'));
    }

    public function edit(LabRequest $labRequest): View
    {
        $this->authorize('update', $labRequest);

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
        // Controlled cancel — do not hard-delete lab requests/results.
        if (in_array($labRequest->status, ['completed', 'verified', 'released', 'validated'], true)) {
            return redirect()
                ->route('hms.laboratory.requests.index')
                ->with('error', 'Cannot delete a completed lab request. Use amendment workflow.');
        }

        $labRequest->update([
            'status' => 'cancelled',
        ]);

        \App\Models\AuditLog::log(
            'user',
            auth()->id(),
            'lab_request.cancel',
            'LabRequest',
            $labRequest->id,
            null,
            ['status' => 'cancelled'],
            'Lab request cancelled (not deleted)'
        );

        return redirect()->route('hms.laboratory.requests.index')->with('status', 'Lab request cancelled');
    }

    public function reportPdf(LabRequest $labRequest)
    {
        $labRequest->load(['patient', 'doctor', 'items.labTest']);
        $pdf = PDF::loadView('hms.laboratory.lab-report-pdf', compact('labRequest'));
        return $pdf->download("Lab-Report-{$labRequest->request_number}.pdf");
    }
}
