<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Vital;
use App\Models\Patient;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VitalsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vital::with(['patient', 'opdVisit', 'ipdAdmission', 'recorder']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('vitals_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $vitals = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Vital::count(),
            'today' => Vital::whereDate('created_at', today())->count(),
        ];

        return view('hms.vitals.index', compact('vitals', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $opdVisits = OpdVisit::where('status', '!=', 'discharged')->with('patient')->latest()->get();
        $ipdAdmissions = IpdAdmission::where('status', 'admitted')->with('patient')->latest()->get();
        return view('hms.vitals.create', compact('patients', 'opdVisits', 'ipdAdmissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse_rate' => 'nullable|integer|min:30|max:250',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'blood_glucose' => 'nullable|numeric|min:0|max:50',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'notes' => 'nullable|string',
        ]);

        $data['recorded_by'] = auth()->id();
        $vital = Vital::create($data);

        return redirect()->route('hms.vitals.index')->with('success', 'Vitals recorded successfully!');
    }

    public function show(Vital $vital): View
    {
        $vital->load(['patient', 'opdVisit', 'ipdAdmission', 'recorder']);
        return view('hms.vitals.show', compact('vital'));
    }

    public function edit(Vital $vital): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        return view('hms.vitals.edit', compact('vital', 'patients'));
    }

    public function update(Request $request, Vital $vital): RedirectResponse
    {
        $data = $request->validate([
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse_rate' => 'nullable|integer|min:30|max:250',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'blood_glucose' => 'nullable|numeric|min:0|max:50',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'notes' => 'nullable|string',
        ]);

        $vital->update($data);
        return redirect()->route('hms.vitals.show', $vital)->with('success', 'Vitals updated successfully!');
    }

    public function destroy(Vital $vital): RedirectResponse
    {
        $vital->delete();
        return redirect()->route('hms.vitals.index')->with('success', 'Vitals record deleted!');
    }

    public function patientHistory(Patient $patient): View
    {
        $vitals = Vital::where('patient_id', $patient->id)
            ->with(['opdVisit', 'ipdAdmission'])
            ->latest()
            ->paginate(20);

        return view('hms.vitals.patient-history', compact('patient', 'vitals'));
    }
}
