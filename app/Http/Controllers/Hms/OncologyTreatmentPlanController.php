<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CancerRegistration;
use App\Models\OncologyTreatmentPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OncologyTreatmentPlanController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cancer_registration_id' => 'required|exists:cancer_registrations,id',
            'patient_id' => 'required|exists:patients,id',
            'plan_name' => 'required|string|max:255',
            'treatment_intent' => 'required|in:curative,palliative,adjuvant,neoadjuvant',
            'modalities' => 'required|array|min:1',
            'modalities.*' => 'in:chemotherapy,radiation,surgery,immunotherapy',
            'start_date' => 'required|date',
            'expected_end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date',
            'status' => 'nullable|in:proposed,active,completed,discontinued',
            'created_by' => 'required|exists:doctors,id',
        ]);

        $data['status'] = $data['status'] ?? 'proposed';

        OncologyTreatmentPlan::create($data);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Treatment plan created.');
    }

    public function index(Request $request): JsonResponse
    {
        $plans = OncologyTreatmentPlan::with(['patient', 'cancerRegistration'])
            ->when($request->patient_id, fn ($q) => $q->where('patient_id', $request->patient_id))
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($plans);
    }
}
