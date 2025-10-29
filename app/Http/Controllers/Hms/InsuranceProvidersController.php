<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\InsuranceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InsuranceProvidersController extends Controller
{
    public function index(Request $request): View
    {
        $query = InsuranceProvider::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('policy_number_prefix', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $providers = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total_providers' => InsuranceProvider::count(),
            'active_providers' => InsuranceProvider::where('is_active', true)->count(),
            'total_patients_insured' => \App\Models\PatientInsurance::distinct('patient_id')->count(),
            'total_claims' => \App\Models\InsuranceClaim::count(),
        ];

        return view('hms.insurance.providers.index', compact('providers', 'stats'));
    }

    public function create(): View
    {
        return view('hms.insurance.providers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:insurance_providers,code',
            'policy_number_prefix' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'coverage_limit' => 'nullable|numeric|min:0',
            'copayment_percentage' => 'nullable|numeric|min:0|max:100',
            'deductible_amount' => 'nullable|numeric|min:0',
            'claim_submission_url' => 'nullable|url',
            'api_endpoint' => 'nullable|url',
            'api_key' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        InsuranceProvider::create($data);

        return redirect()->route('hms.insurance.providers.index')
            ->with('status', 'Insurance provider created successfully');
    }

    public function show(InsuranceProvider $provider): View
    {
        $provider->load(['patientInsurances.patient', 'claims']);

        $stats = [
            'total_patients' => $provider->patientInsurances()->distinct('patient_id')->count(),
            'active_policies' => $provider->patientInsurances()->where('is_active', true)->count(),
            'total_claims' => $provider->claims()->count(),
            'pending_claims' => $provider->claims()->where('status', 'pending')->count(),
            'approved_amount' => $provider->claims()->sum('approved_amount'),
            'paid_amount' => $provider->claims()->sum('paid_amount'),
        ];

        return view('hms.insurance.providers.show', compact('provider', 'stats'));
    }

    public function edit(InsuranceProvider $provider): View
    {
        return view('hms.insurance.providers.edit', compact('provider'));
    }

    public function update(Request $request, InsuranceProvider $provider): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:insurance_providers,code,' . $provider->id,
            'policy_number_prefix' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'coverage_limit' => 'nullable|numeric|min:0',
            'copayment_percentage' => 'nullable|numeric|min:0|max:100',
            'deductible_amount' => 'nullable|numeric|min:0',
            'claim_submission_url' => 'nullable|url',
            'api_endpoint' => 'nullable|url',
            'api_key' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $provider->update($data);

        return redirect()->route('hms.insurance.providers.show', $provider)
            ->with('status', 'Insurance provider updated successfully');
    }

    public function destroy(InsuranceProvider $provider): RedirectResponse
    {
        // Check if provider has active policies
        $activeCount = $provider->patientInsurances()->where('is_active', true)->count();
        
        if ($activeCount > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete provider with active policies. Please deactivate all policies first.'
            ]);
        }

        $provider->delete();

        return redirect()->route('hms.insurance.providers.index')
            ->with('status', 'Insurance provider deleted successfully');
    }

    public function verify(Request $request, InsuranceProvider $provider)
    {
        $data = $request->validate([
            'policy_number' => 'required|string',
            'patient_name' => 'required|string',
            'patient_dob' => 'required|date',
        ]);

        // Verify with insurance provider API
        // This would integrate with actual API
        $verificationResult = $this->verifyWithProvider($provider, $data);

        return response()->json($verificationResult);
    }

    private function verifyWithProvider(InsuranceProvider $provider, array $data): array
    {
        // Simulate API verification
        // In production, this would call the actual insurance provider API

        if (!$provider->api_endpoint) {
            return [
                'success' => false,
                'message' => 'API endpoint not configured for this provider'
            ];
        }

        // Simulate successful verification
        return [
            'success' => true,
            'message' => 'Policy verified successfully',
            'data' => [
                'policy_number' => $data['policy_number'],
                'status' => 'active',
                'coverage_limit' => $provider->coverage_limit,
                'copayment_percentage' => $provider->copayment_percentage,
                'deductible_amount' => $provider->deductible_amount,
                'valid_until' => now()->addYear()->format('Y-m-d'),
            ]
        ];
    }
}
