<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OpdVisit;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpdVisitsController extends Controller
{
    public function index(Request $request): View
    {
        $query = OpdVisit::with(['patient', 'doctor']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($patientQuery) use ($search) {
                    $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('patient_no', 'like', "%{$search}%");
                })
                ->orWhere('chief_complaint', 'like', "%{$search}%")
                ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }
        
        // Filter by visit type
        if ($request->filled('visit_type')) {
            $query->where('visit_type', $request->visit_type);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }
        
        $visits = $query->latest('visit_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => OpdVisit::count(),
            'today' => OpdVisit::whereDate('visit_date', today())->count(),
            'this_week' => OpdVisit::whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_revenue' => OpdVisit::sum('consultation_fee') ?? 0,
        ];
        
        return view('hms.opd.index', compact('visits', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no', 'phone']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'consultation_fee']);
        $defaultConsultationFee = (float) \App\Models\SystemSetting::get('consultation_fee', 0);
        return view('hms.opd.create', compact('patients', 'doctors', 'defaultConsultationFee'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'visit_date' => 'required|date',
            'visit_type' => 'required|string',
            'chief_complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'billing_mode' => 'required|in:sha,mpesa,cash,none',
            'payment_id' => 'nullable|integer|exists:payments,id',
        ]);

        $fee = (float) ($data['consultation_fee'] ?? 0);
        $mpesa = app(\App\Services\MpesaService::class);

        if ($fee > 0) {
            if ($data['billing_mode'] === 'mpesa') {
                if (empty($data['payment_id']) || !$mpesa->hasConfirmedPayment($data['patient_id'], $data['payment_id'], $fee)) {
                    return back()->withErrors(['payment_id' => 'A completed M-Pesa payment for the consultation fee is required before recording the visit.'])
                        ->withInput();
                }
            } elseif ($data['billing_mode'] === 'sha') {
                $coverage = $mpesa->coverage(Patient::findOrFail($data['patient_id']), 'consultation_fee');
                if (!$coverage['covered']) {
                    return back()->withErrors(['billing_mode' => 'This patient is not covered under SHA for consultation. Please collect M-Pesa or cash payment instead.'])
                        ->withInput();
                }
            }
        } else {
            $data['billing_mode'] = 'none';
        }

        $visit = OpdVisit::create($data);

        if ($data['billing_mode'] === 'mpesa') {
            $mpesa->attachSource($data['payment_id'], 'opd_visit', $visit->id);
        }

        return redirect()->route('hms.opd.index')->with('success', 'OPD visit recorded successfully!');
    }
    
    public function show(OpdVisit $opd): View
    {
        $opd->load(['patient', 'doctor']);
        return view('hms.opd.show', compact('opd'));
    }
    
    public function edit(OpdVisit $opd): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.opd.edit', compact('opd', 'patients', 'doctors'));
    }
    
    public function update(Request $request, OpdVisit $opd): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'visit_date' => 'required|date',
            'visit_type' => 'required|string',
            'chief_complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);
        
        $opd->update($data);
        return redirect()->route('hms.opd.show', $opd)->with('success', 'OPD visit updated successfully!');
    }
    
    public function destroy(OpdVisit $opd): RedirectResponse
    {
        $opd->delete();
        return redirect()->route('hms.opd.index')->with('success', 'OPD visit deleted successfully!');
    }
}