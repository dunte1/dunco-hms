<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DrugInteraction;
use App\Models\Medicine;
use App\Models\PatientAllergy;
use App\Services\DrugInteractionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DrugInteractionController extends Controller
{
    public function __construct(private DrugInteractionService $service) {}

    public function index(): View
    {
        $interactions = DrugInteraction::with(['drugA', 'drugB'])
            ->where('is_active', true)
            ->orderByDesc('severity')
            ->paginate(20);

        $stats = [
            'total' => DrugInteraction::where('is_active', true)->count(),
            'critical' => DrugInteraction::where('is_active', true)->where('severity', 'critical')->count(),
            'severe' => DrugInteraction::where('is_active', true)->where('severity', 'severe')->count(),
            'moderate' => DrugInteraction::where('is_active', true)->where('severity', 'moderate')->count(),
            'mild' => DrugInteraction::where('is_active', true)->where('severity', 'mild')->count(),
        ];

        return view('hms.drug-interactions.index', compact('interactions', 'stats'));
    }

    public function create(): View
    {
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');
        return view('hms.drug-interactions.create', compact('medicines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'drug_a_id' => 'required|exists:medicines,id',
            'drug_b_id' => 'required|exists:medicines,id|different:drug_a_id',
            'severity' => 'required|in:critical,severe,moderate,mild',
            'description' => 'required|string',
            'clinical_effect' => 'nullable|string',
            'management_advice' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $exists = DrugInteraction::where(function ($q) use ($data) {
            $q->where(function ($q2) use ($data) {
                $q2->where('drug_a_id', $data['drug_a_id'])->where('drug_b_id', $data['drug_b_id']);
            })->orWhere(function ($q2) use ($data) {
                $q2->where('drug_a_id', $data['drug_b_id'])->where('drug_b_id', $data['drug_a_id']);
            });
        })->exists();

        if ($exists) {
            return back()->withErrors(['drug_b_id' => 'This interaction already exists'])->withInput();
        }

        DrugInteraction::create($data);

        return redirect()->route('hms.drug-interactions.index')->with('status', 'Drug interaction rule added successfully');
    }

    public function show(DrugInteraction $drugInteraction): View
    {
        $drugInteraction->load(['drugA', 'drugB']);
        return view('hms.drug-interactions.show', compact('drugInteraction'));
    }

    public function edit(DrugInteraction $drugInteraction): View
    {
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');
        return view('hms.drug-interactions.edit', compact('drugInteraction', 'medicines'));
    }

    public function update(Request $request, DrugInteraction $drugInteraction): RedirectResponse
    {
        $data = $request->validate([
            'severity' => 'required|in:critical,severe,moderate,mild',
            'description' => 'required|string',
            'clinical_effect' => 'nullable|string',
            'management_advice' => 'nullable|string',
            'source' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $drugInteraction->update($data);

        return redirect()->route('hms.drug-interactions.index')->with('status', 'Drug interaction updated successfully');
    }

    public function destroy(DrugInteraction $drugInteraction): RedirectResponse
    {
        $drugInteraction->update(['is_active' => false]);
        return redirect()->route('hms.drug-interactions.index')->with('status', 'Drug interaction deactivated');
    }

    public function patientAllergies(int $patientId): View
    {
        $allergies = PatientAllergy::where('patient_id', $patientId)->orderByDesc('created_at')->get();
        return view('hms.drug-interactions.patient-allergies', compact('allergies', 'patientId'));
    }

    public function storeAllergy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'allergen' => 'required|string',
            'allergen_type' => 'required|in:drug,food,environmental,other',
            'reaction' => 'nullable|string',
            'severity' => 'required|in:mild,moderate,severe,anaphylaxis',
            'onset_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        PatientAllergy::create($data);

        return back()->with('status', 'Allergy recorded successfully');
    }

    public function destroyAllergy(PatientAllergy $allergy): RedirectResponse
    {
        $allergy->update(['is_active' => false]);
        return back()->with('status', 'Allergy deactivated');
    }
}
