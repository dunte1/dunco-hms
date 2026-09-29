<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\WardDischargePlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DischargePlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = WardDischargePlan::with(['patient', 'ipdAdmission', 'createdByUser'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($plans);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'discharge_date' => 'nullable|date',
            'home_care_needs' => 'required|string',
            'follow_up_appointments' => 'required|string',
            'equipment_needs' => 'nullable|string',
            'community_services' => 'nullable|string',
            'caregiver_involvement' => 'nullable|string',
            'status' => 'nullable|in:draft,active,completed',
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';

        WardDischargePlan::create($data);

        return back()->with('status', 'Discharge plan created');
    }
}
