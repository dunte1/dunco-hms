<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\InsuranceClaim;
use App\Models\PatientInsurance;
use App\Models\InsuranceProvider;
use App\Models\Patient;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InsuranceClaimsController extends Controller
{
    public function index(Request $request): View
    {
        $query = InsuranceClaim::with(['patient', 'patientInsurance.insuranceProvider', 'invoice']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhere('insurance_reference', 'like', "%{$search}%")
                  ->orWhere('diagnosis_code', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('patientInsurance.insuranceProvider', function($providerQuery) use ($search) {
                      $providerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by insurance provider
        if ($request->filled('insurance_provider_id')) {
            $query->whereHas('patientInsurance', function($q) use ($request) {
                $q->where('insurance_provider_id', $request->insurance_provider_id);
            });
        }

        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('claim_date', [$request->from_date, $request->to_date]);
        }

        $claims = $query->latest('claim_date')->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total_claims' => InsuranceClaim::count(),
            'pending_claims' => InsuranceClaim::where('status', 'pending')->count(),
            'approved_claims' => InsuranceClaim::where('status', 'approved')->count(),
            'rejected_claims' => InsuranceClaim::where('status', 'rejected')->count(),
            'total_claimed_amount' => InsuranceClaim::sum('claimed_amount'),
            'total_approved_amount' => InsuranceClaim::sum('approved_amount'),
            'total_paid_amount' => InsuranceClaim::sum('paid_amount'),
            'today_claims' => InsuranceClaim::whereDate('claim_date', today())->count(),
        ];

        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();

        return view('hms.insurance.claims.index', compact('claims', 'stats', 'insuranceProviders'));
    }

    public function create(): View
    {
        $patients = Patient::with('patientInsurances.insuranceProvider')->get();
        $invoices = Invoice::with('patient')->where('status', '!=', 'paid')->get();
        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();

        return view('hms.insurance.claims.create', compact('patients', 'invoices', 'insuranceProviders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'patient_insurance_id' => 'required|exists:patient_insurance,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'claim_date' => 'required|date',
            'service_date' => 'required|date',
            'claimed_amount' => 'required|numeric|min:0',
            'diagnosis_code' => 'nullable|string|max:255',
            'diagnosis_description' => 'nullable|string',
            'treatment_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate claim number
        $data['claim_number'] = 'CLM-' . str_pad(InsuranceClaim::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['status'] = 'pending';

        InsuranceClaim::create($data);

        return redirect()->route('hms.insurance.claims.index')
            ->with('status', 'Insurance claim created successfully');
    }

    public function show(InsuranceClaim $claim): View
    {
        $claim->load(['patient', 'patientInsurance.insuranceProvider', 'invoice']);
        return view('hms.insurance.claims.show', compact('claim'));
    }

    public function edit(InsuranceClaim $claim): View
    {
        $patients = Patient::with('patientInsurances.insuranceProvider')->get();
        $invoices = Invoice::with('patient')->get();
        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();

        return view('hms.insurance.claims.edit', compact('claim', 'patients', 'invoices', 'insuranceProviders'));
    }

    public function update(Request $request, InsuranceClaim $claim): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'patient_insurance_id' => 'required|exists:patient_insurance,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'claim_date' => 'required|date',
            'service_date' => 'required|date',
            'claimed_amount' => 'required|numeric|min:0',
            'approved_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,submitted,under_review,approved,partially_approved,rejected,paid',
            'diagnosis_code' => 'nullable|string|max:255',
            'diagnosis_description' => 'nullable|string',
            'treatment_details' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'submission_date' => 'nullable|date',
            'approval_date' => 'nullable|date',
            'payment_date' => 'nullable|date',
            'insurance_reference' => 'nullable|string|max:255',
        ]);

        $claim->update($data);

        return redirect()->route('hms.insurance.claims.index')
            ->with('status', 'Insurance claim updated successfully');
    }

    public function submit(InsuranceClaim $claim): RedirectResponse
    {
        $claim->update([
            'status' => 'submitted',
            'submission_date' => now(),
        ]);

        return back()->with('status', 'Claim submitted successfully');
    }

    public function approve(Request $request, InsuranceClaim $claim): RedirectResponse
    {
        $data = $request->validate([
            'approved_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $claim->update([
            'status' => 'approved',
            'approved_amount' => $data['approved_amount'],
            'approval_date' => now(),
            'notes' => $data['notes'] ?? $claim->notes,
        ]);

        return back()->with('status', 'Claim approved successfully');
    }

    public function reject(Request $request, InsuranceClaim $claim): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $claim->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
        ]);

        return back()->with('status', 'Claim rejected successfully');
    }

    public function recordPayment(Request $request, InsuranceClaim $claim): RedirectResponse
    {
        $data = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $claim->update([
            'paid_amount' => $data['paid_amount'],
            'payment_date' => $data['payment_date'],
            'status' => $data['paid_amount'] >= $claim->approved_amount ? 'paid' : 'partially_approved',
        ]);

        return back()->with('status', 'Payment recorded successfully');
    }

    public function destroy(InsuranceClaim $claim): RedirectResponse
    {
        $claim->delete();

        return redirect()->route('hms.insurance.claims.index')
            ->with('status', 'Insurance claim deleted successfully');
    }
}
