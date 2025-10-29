<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\InsuranceProvider;
use App\Models\PatientInsurance;
use App\Models\InsuranceClaim;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InsuranceController extends Controller
{
    public function companies(Request $request): View
    {
        $query = InsuranceProvider::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by coverage percentage
        if ($request->filled('coverage_min')) {
            $query->where('coverage_percentage', '>=', $request->coverage_min);
        }

        if ($request->filled('coverage_max')) {
            $query->where('coverage_percentage', '<=', $request->coverage_max);
        }

        $companies = $query->withCount(['patientInsurances', 'claims'])
                          ->latest()
                          ->paginate(15)
                          ->withQueryString();

        // Statistics
        $stats = [
            'total_companies' => InsuranceProvider::count(),
            'active_companies' => InsuranceProvider::where('is_active', true)->count(),
            'total_patients_insured' => PatientInsurance::distinct('patient_id')->count(),
            'total_claims' => InsuranceClaim::count(),
            'total_coverage_amount' => InsuranceProvider::sum('coverage_limit'),
            'average_coverage_percentage' => InsuranceProvider::where('is_active', true)->avg('coverage_percentage'),
        ];

        return view('hms.insurance.companies', compact('companies', 'stats'));
    }

    public function createCompany(): View
    {
        return view('hms.insurance.companies.create');
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:insurance_providers,name',
            'code' => 'required|string|max:10|unique:insurance_providers,code',
            'description' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'coverage_limit' => 'nullable|numeric|min:0',
            'copayment_percentage' => 'nullable|numeric|min:0|max:100',
            'deductible_amount' => 'nullable|numeric|min:0',
            'policy_number_prefix' => 'nullable|string|max:10',
            'claim_submission_url' => 'nullable|url|max:255',
            'api_endpoint' => 'nullable|url|max:255',
            'api_key' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        InsuranceProvider::create($data);

        return redirect()->route('hms.insurance.companies')
            ->with('status', 'Insurance company created successfully');
    }

    public function editCompany(InsuranceProvider $company): View
    {
        return view('hms.insurance.companies.edit', compact('company'));
    }

    public function updateCompany(Request $request, InsuranceProvider $company): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:insurance_providers,name,' . $company->id,
            'code' => 'required|string|max:10|unique:insurance_providers,code,' . $company->id,
            'description' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'coverage_limit' => 'nullable|numeric|min:0',
            'copayment_percentage' => 'nullable|numeric|min:0|max:100',
            'deductible_amount' => 'nullable|numeric|min:0',
            'policy_number_prefix' => 'nullable|string|max:10',
            'claim_submission_url' => 'nullable|url|max:255',
            'api_endpoint' => 'nullable|url|max:255',
            'api_key' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $company->update($data);

        return redirect()->route('hms.insurance.companies')
            ->with('status', 'Insurance company updated successfully');
    }

    public function destroyCompany(InsuranceProvider $company): RedirectResponse
    {
        // Check if company has any patient insurances or claims
        if ($company->patientInsurances()->count() > 0 || $company->claims()->count() > 0) {
            return redirect()->route('hms.insurance.companies')
                ->with('error', 'Cannot delete insurance company with existing patient insurances or claims');
        }

        $company->delete();

        return redirect()->route('hms.insurance.companies')
            ->with('status', 'Insurance company deleted successfully');
    }

    public function policies(Request $request): View
    {
        $query = PatientInsurance::with(['patient', 'insuranceProvider']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('policy_number', 'like', "%{$search}%")
                  ->orWhere('group_number', 'like', "%{$search}%")
                  ->orWhere('policy_holder_name', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('insuranceProvider', function($providerQuery) use ($search) {
                      $providerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by insurance provider
        if ($request->filled('insurance_provider_id')) {
            $query->where('insurance_provider_id', $request->insurance_provider_id);
        }

        // Filter by coverage type
        if ($request->filled('coverage_type')) {
            $query->where('coverage_type', $request->coverage_type);
        }

        // Filter by expiry date
        if ($request->filled('expiry_filter')) {
            switch ($request->expiry_filter) {
                case 'expired':
                    $query->where('expiry_date', '<', now());
                    break;
                case 'expiring_soon':
                    $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                    break;
                case 'active':
                    $query->where('expiry_date', '>', now());
                    break;
            }
        }

        $policies = $query->latest('effective_date')->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total_policies' => PatientInsurance::count(),
            'active_policies' => PatientInsurance::where('is_active', true)->count(),
            'expired_policies' => PatientInsurance::where('expiry_date', '<', now())->count(),
            'expiring_soon' => PatientInsurance::whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
            'total_coverage_amount' => PatientInsurance::sum('coverage_amount'),
            'primary_policies' => PatientInsurance::where('is_primary', true)->count(),
            'today_policies' => PatientInsurance::whereDate('effective_date', today())->count(),
            'this_month_policies' => PatientInsurance::whereBetween('effective_date', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();

        return view('hms.insurance.policies', compact('policies', 'stats', 'insuranceProviders'));
    }

    public function index(): View
    {
        // Insurance dashboard with overview statistics
        $stats = [
            'total_providers' => InsuranceProvider::count(),
            'active_providers' => InsuranceProvider::where('is_active', true)->count(),
            'total_policies' => PatientInsurance::count(),
            'active_policies' => PatientInsurance::where('is_active', true)->count(),
            'total_claims' => InsuranceClaim::count(),
            'pending_claims' => InsuranceClaim::where('status', 'pending')->count(),
            'total_claimed_amount' => InsuranceClaim::sum('claimed_amount'),
            'total_approved_amount' => InsuranceClaim::sum('approved_amount'),
        ];

        $recentClaims = InsuranceClaim::with(['patient', 'patientInsurance.insuranceProvider'])
            ->latest('claim_date')
            ->limit(5)
            ->get();

        $recentPolicies = PatientInsurance::with(['patient', 'insuranceProvider'])
            ->latest('effective_date')
            ->limit(5)
            ->get();

        return view('hms.insurance.index', compact('stats', 'recentClaims', 'recentPolicies'));
    }

    public function createPolicy(): View
    {
        $patients = \App\Models\Patient::orderBy('first_name')->get();
        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();
        
        return view('hms.insurance.policies.create', compact('patients', 'insuranceProviders'));
    }

    public function storePolicy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'insurance_provider_id' => 'required|exists:insurance_providers,id',
            'policy_number' => 'required|string|max:255|unique:patient_insurance,policy_number',
            'group_number' => 'nullable|string|max:255',
            'policy_holder_name' => 'nullable|string|max:255',
            'policy_holder_relationship' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'expiry_date' => 'required|date|after:effective_date',
            'coverage_amount' => 'nullable|numeric|min:0',
            'coverage_type' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
            'copayment_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_primary'] = $request->has('is_primary');
        $data['is_active'] = $request->has('is_active');

        PatientInsurance::create($data);

        return redirect()->route('hms.insurance.policies')
            ->with('status', 'Insurance policy created successfully');
    }

    public function showPolicy(PatientInsurance $policy): View
    {
        $policy->load(['patient', 'insuranceProvider']);
        return view('hms.insurance.policies.show', compact('policy'));
    }

    public function editPolicy(PatientInsurance $policy): View
    {
        $patients = \App\Models\Patient::orderBy('first_name')->get();
        $insuranceProviders = InsuranceProvider::where('is_active', true)->orderBy('name')->get();
        
        return view('hms.insurance.policies.edit', compact('policy', 'patients', 'insuranceProviders'));
    }

    public function updatePolicy(Request $request, PatientInsurance $policy): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'insurance_provider_id' => 'required|exists:insurance_providers,id',
            'policy_number' => 'required|string|max:255|unique:patient_insurance,policy_number,' . $policy->id,
            'group_number' => 'nullable|string|max:255',
            'policy_holder_name' => 'nullable|string|max:255',
            'policy_holder_relationship' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'expiry_date' => 'required|date|after:effective_date',
            'coverage_amount' => 'nullable|numeric|min:0',
            'coverage_type' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
            'copayment_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_primary'] = $request->has('is_primary');
        $data['is_active'] = $request->has('is_active');

        $policy->update($data);

        return redirect()->route('hms.insurance.policies')
            ->with('status', 'Insurance policy updated successfully');
    }

    public function destroyPolicy(PatientInsurance $policy): RedirectResponse
    {
        // Check if policy has any claims
        if ($policy->claims()->count() > 0) {
            return redirect()->route('hms.insurance.policies')
                ->with('error', 'Cannot delete insurance policy with existing claims');
        }

        $policy->delete();

        return redirect()->route('hms.insurance.policies')
            ->with('status', 'Insurance policy deleted successfully');
    }
}
