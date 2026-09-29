<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MhTreatmentPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MhTreatmentPlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = MhTreatmentPlan::with(['patient', 'mhAssessment', 'createdByUser'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($plans);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'mh_assessment_id' => 'nullable|exists:mh_assessments,id',
            'diagnosis' => 'required|string',
            'goals' => 'required|string',
            'interventions' => 'required|string',
            'medications' => 'nullable|string',
            'follow_up_frequency' => 'nullable|string',
            'status' => 'nullable|in:active,completed,discontinued',
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'active';

        MhTreatmentPlan::create($data);

        return back()->with('status', 'Treatment plan created');
    }
}
