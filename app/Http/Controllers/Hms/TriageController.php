<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Triage;
use App\Models\Patient;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TriageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Triage::with(['patient', 'opdVisit', 'ipdAdmission', 'triager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('triage_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('priority_level')) {
            $query->where('priority_level', $request->priority_level);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('triaged_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('triaged_at', '<=', $request->date_to);
        }

        $triages = $query->latest('triaged_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => Triage::count(),
            'today' => Triage::whereDate('triaged_at', today())->count(),
            'emergency' => Triage::where('priority_level', 'emergency')->whereDate('triaged_at', today())->count(),
            'urgent' => Triage::where('priority_level', 'urgent')->whereDate('triaged_at', today())->count(),
        ];

        return view('hms.triage.index', compact('triages', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $opdVisits = OpdVisit::where('status', '!=', 'discharged')->with('patient')->latest()->get();
        return view('hms.triage.create', compact('patients', 'opdVisits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'priority_level' => 'required|in:emergency,urgent,semi_urgent,non_urgent',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse_rate' => 'nullable|integer|min:30|max:250',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'blood_glucose' => 'nullable|numeric|min:0|max:50',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'chief_complaint' => 'nullable|string',
            'triage_notes' => 'nullable|string',
        ]);

        $data['triage_number'] = 'TRI-' . date('Y') . '-' . str_pad(Triage::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['triaged_by'] = auth()->id();
        $data['triaged_at'] = now();

        $triage = Triage::create($data);

        // Update OPD visit status if linked
        if (!empty($data['opd_visit_id'])) {
            OpdVisit::where('id', $data['opd_visit_id'])->update([
                'status' => 'triaged',
                'triage_notes' => $data['triage_notes'] ?? null,
            ]);
        }

        return redirect()->route('hms.triage.index')->with('success', 'Triage assessment recorded successfully!');
    }

    public function show(Triage $triage): View
    {
        $triage->load(['patient', 'opdVisit', 'ipdAdmission', 'triager']);
        return view('hms.triage.show', compact('triage'));
    }

    public function edit(Triage $triage): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        return view('hms.triage.edit', compact('triage', 'patients'));
    }

    public function update(Request $request, Triage $triage): RedirectResponse
    {
        $data = $request->validate([
            'priority_level' => 'required|in:emergency,urgent,semi_urgent,non_urgent',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse_rate' => 'nullable|integer|min:30|max:250',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'blood_glucose' => 'nullable|numeric|min:0|max:50',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'chief_complaint' => 'nullable|string',
            'triage_notes' => 'nullable|string',
        ]);

        $triage->update($data);
        return redirect()->route('hms.triage.show', $triage)->with('success', 'Triage updated successfully!');
    }

    public function destroy(Triage $triage): RedirectResponse
    {
        $triage->delete();
        return redirect()->route('hms.triage.index')->with('success', 'Triage record deleted!');
    }
}
