<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HtsEncounter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HtsEncounterController extends Controller
{
    public function index(Request $request): View
    {
        $query = HtsEncounter::with(['patient', 'tester']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('hts_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $encounters = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => HtsEncounter::count(),
            'positive' => HtsEncounter::where('test_result', 'positive')->count(),
            'today' => HtsEncounter::whereDate('encounter_date', today())->count(),
        ];

        return view('hms.hiv.hts-index', compact('encounters', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'encounter_date' => 'required|date',
            'risk_assessment_done' => 'boolean',
            'consent_given' => 'required|boolean',
            'test_type' => 'required|in:hts,confirmatory',
            'test_result' => 'required|in:positive,negative,inconclusive',
            'test_date' => 'nullable|date',
            'counselled_before' => 'boolean',
            'counselled_after' => 'boolean',
            'referral_offered' => 'boolean',
            'status' => 'in:completed,referred,declined',
        ]);

        $data['tested_by'] = auth()->id();

        $encounter = HtsEncounter::create($data);

        return redirect()->route('hms.hiv.hts.index')
            ->with('success', "HTS encounter {$encounter->hts_number} recorded successfully!");
    }
}
