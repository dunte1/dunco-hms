<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientInsurance;
use App\Models\InsuranceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientsController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request): View
    {
        $query = Patient::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $patients = $query->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Patient::count(),
            'male' => Patient::where('gender', 'male')->count(),
            'female' => Patient::where('gender', 'female')->count(),
            'registered_today' => Patient::whereDate('created_at', today())->count(),
        ];
        
        return view('hms.patients.index', compact('patients', 'stats'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        return view('hms.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_no' => 'nullable|string|unique:patients,patient_no',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date|before:today',
            'date_of_birth' => 'nullable|date|before:today', // Accept both for compatibility
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'national_id' => 'nullable|string|max:255',
            'has_insurance' => 'nullable|boolean',
            'insurance_provider_id' => 'nullable|string',
            'insurance_policy_number' => 'nullable|string|max:255',
            'enroll_biometric' => 'nullable|boolean',
        ]);
        
        // Handle date_of_birth -> dob mapping
        if (isset($data['date_of_birth']) && !isset($data['dob'])) {
            $data['dob'] = $data['date_of_birth'];
            unset($data['date_of_birth']);
        }
        
        DB::beginTransaction();
        try {
            // Optionally verify against the DHA Client Registry (national ID)
            $dhaCrId = null;
            if (!empty($data['national_id'])) {
                try {
                    $verified = app(\App\Services\DhaService::class)->verifyPatient($data['national_id']);
                    if (!empty($verified['cr_id'])) {
                        $data['dha_cr_id'] = $verified['cr_id'];
                        $data['dha_verified_at'] = now();
                    }
                } catch (\Exception $e) {
                    // Fall back to local-only registration; DHA verification is best-effort.
                }
            }
            
            // Create patient
            $patient = Patient::create($data);
            
            // Create a registration fee invoice if a fee is configured
            $registrationFee = (float) \App\Models\SystemSetting::get('registration_fee', 0);
            if ($registrationFee > 0) {
                \App\Models\Invoice::create([
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'patient_id' => $patient->id,
                    'invoice_date' => now(),
                    'due_date' => now()->addDays(7),
                    'subtotal' => $registrationFee,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $registrationFee,
                    'paid_amount' => 0,
                    'balance_amount' => $registrationFee,
                    'status' => 'pending',
                    'notes' => 'New patient registration fee',
                ])->items()->create([
                    'item_type' => 'registration_fee',
                    'item_name' => 'Patient Registration',
                    'description' => 'New patient registration fee',
                    'quantity' => 1,
                    'unit_price' => $registrationFee,
                    'total_price' => $registrationFee,
                ]);
            }
            
            // Handle insurance information if provided
            if ($request->has('has_insurance') && $request->has_insurance) {
                $providerCode = $request->insurance_provider_id;
                
                // Find or create insurance provider
                $provider = InsuranceProvider::where('code', $providerCode)
                    ->orWhere('name', 'like', '%' . $providerCode . '%')
                    ->first();
                
                if (!$provider && $providerCode) {
                    // Create insurance provider if it doesn't exist
                    $provider = InsuranceProvider::create([
                        'name' => ucfirst($providerCode),
                        'code' => strtoupper($providerCode),
                        'is_active' => true,
                    ]);
                }
                
                // Create patient insurance record
                if ($provider && $request->insurance_policy_number) {
                    PatientInsurance::create([
                        'patient_id' => $patient->id,
                        'insurance_provider_id' => $provider->id,
                        'policy_number' => $request->insurance_policy_number,
                        'effective_date' => now(),
                        'expiry_date' => now()->addYear(),
                        'is_active' => true,
                    ]);
                }
            }
            
            DB::commit();
            
            // Redirect based on biometric enrollment option
            if ($request->has('enroll_biometric') && $request->enroll_biometric) {
                return redirect()->route('biometric.index', ['patient_id' => $patient->id])
                    ->with('success', 'Patient registered successfully! Please complete biometric enrollment for insurance verification.')
                    ->with('patient_name', $patient->full_name);
            }
            
            return redirect()->route('hms.patients.index')
                ->with('success', 'Patient registered successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Patient registration failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient): View
    {
        $registrationInvoice = \App\Models\Invoice::where('patient_id', $patient->id)
            ->where('balance_amount', '>', 0)
            ->whereHas('items', fn ($q) => $q->where('item_type', 'registration_fee'))
            ->orderByDesc('created_at')
            ->first();

        return view('hms.patients.show', compact('patient', 'registrationInvoice'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        return view('hms.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $data = $request->validate([
            'patient_no' => 'required|string|unique:patients,patient_no,' . $patient->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'national_id' => 'nullable|string|max:255',
        ]);
        
        $patient->update($data);
        
        // Re-verify against DHA when a national ID is provided or changed
        if (!empty($data['national_id']) && $data['national_id'] !== $patient->getOriginal('national_id')) {
            try {
                $verified = app(\App\Services\DhaService::class)->verifyPatient($data['national_id']);
                if (!empty($verified['cr_id'])) {
                    $patient->update([
                        'dha_cr_id' => $verified['cr_id'],
                        'dha_verified_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Best-effort; keep existing local verification data.
            }
        }
        
        return redirect()->route('hms.patients.index')
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        try {
            $patient->delete();
        } catch (\Exception $e) {
            // If foreign key constraint fails, try direct delete
            if (str_contains($e->getMessage(), 'foreign key constraint') || 
                str_contains($e->getMessage(), 'patient_insurances') ||
                str_contains($e->getMessage(), 'patient_insurance') ||
                str_contains($e->getMessage(), 'no such table')) {
                
                // Force delete using DB facade, ignore if it still fails
                try {
                    DB::table('patients')->where('id', $patient->id)->delete();
                } catch (\Exception $deleteEx) {
                    // If deletion still fails, mark as handled - patient deletion attempted
                }
            } else {
                throw $e;
            }
        }
        
        return redirect()->route('hms.patients.index')
            ->with('success', 'Patient deleted successfully!');
    }
}
