<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\NursingCarePlan;
use App\Models\Patient;
use App\Models\IpdAdmission;
use App\Models\Nurse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NursingCarePlanController extends Controller
{
    public function index(Request $request): View
    {
        $query = NursingCarePlan::with(['patient', 'ipdAdmission', 'assignedNurse', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('care_plan_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $carePlans = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => NursingCarePlan::count(),
            'active' => NursingCarePlan::where('status', 'active')->count(),
            'completed' => NursingCarePlan::where('status', 'completed')->count(),
        ];

        return view('hms.nursing-care-plans.index', compact('carePlans', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $ipdAdmissions = IpdAdmission::where('status', 'admitted')->with('patient')->latest()->get();
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.nursing-care-plans.create', compact('patients', 'ipdAdmissions', 'nurses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'nursing_diagnosis' => 'required|string',
            'goal' => 'nullable|string',
            'interventions' => 'nullable|string',
            'expected_outcome' => 'nullable|string',
            'assigned_nurse_id' => 'nullable|exists:nurses,id',
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = 'active';
        $carePlan = NursingCarePlan::create($data);

        return redirect()->route('hms.nursing-care-plans.index')->with('success', 'Nursing care plan created!');
    }

    public function show(NursingCarePlan $carePlan): View
    {
        $carePlan->load(['patient', 'ipdAdmission', 'opdVisit', 'assignedNurse', 'creator']);
        return view('hms.nursing-care-plans.show', compact('carePlan'));
    }

    public function edit(NursingCarePlan $carePlan): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.nursing-care-plans.edit', compact('carePlan', 'patients', 'nurses'));
    }

    public function update(Request $request, NursingCarePlan $carePlan): RedirectResponse
    {
        $data = $request->validate([
            'nursing_diagnosis' => 'required|string',
            'goal' => 'nullable|string',
            'interventions' => 'nullable|string',
            'expected_outcome' => 'nullable|string',
            'actual_outcome' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
            'assigned_nurse_id' => 'nullable|exists:nurses,id',
        ]);

        $carePlan->update($data);
        return redirect()->route('hms.nursing-care-plans.show', $carePlan)->with('success', 'Care plan updated!');
    }

    public function destroy(NursingCarePlan $carePlan): RedirectResponse
    {
        $carePlan->delete();
        return redirect()->route('hms.nursing-care-plans.index')->with('success', 'Care plan deleted!');
    }
}
