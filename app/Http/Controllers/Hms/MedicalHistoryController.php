<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicalHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Patient::with(['medicalHistories' => function($q) {
            $q->latest('recorded_date');
        }]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('patient_no', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('first_name')->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_patients' => Patient::count(),
            'with_history' => Patient::has('medicalHistories')->count(),
            'chronic_conditions' => MedicalHistory::where('is_chronic', true)->distinct('patient_id')->count('patient_id'),
            'recent_updates' => MedicalHistory::whereBetween('recorded_date', [now()->subDays(7), now()])->count(),
        ];

        return view('hms.medical-history.index', compact('patients', 'stats'));
    }

    public function show(Patient $patient): View
    {
        $patient->load(['medicalHistories' => function($q) {
            $q->latest('recorded_date');
        }]);

        return view('hms.medical-history.show', compact('patient'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'condition' => 'required|string',
            'diagnosis_date' => 'nullable|date',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_chronic' => 'boolean',
            'recorded_date' => 'required|date',
        ]);

        MedicalHistory::create($data);

        return back()->with('success', 'Medical history record added successfully!');
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.medical-history.create', compact('patients'));
    }

    public function edit(MedicalHistory $history): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.medical-history.edit', compact('history', 'patients'));
    }

    public function update(Request $request, MedicalHistory $history): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'condition' => 'required|string',
            'diagnosis_date' => 'nullable|date',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_chronic' => 'boolean',
            'recorded_date' => 'required|date',
        ]);

        $history->update($data);

        return back()->with('success', 'Medical history record updated successfully!');
    }

    public function destroy(MedicalHistory $history): RedirectResponse
    {
        $history->delete();
        return back()->with('success', 'Medical history record deleted successfully!');
    }
}
