<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ChemoCycle;
use App\Models\OncologyTreatmentPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChemoCycleController extends Controller
{
    public function store(Request $request, OncologyTreatmentPlan $plan): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'cycle_number' => 'required|integer|min:1',
            'scheduled_date' => 'required|date',
            'prescribed_by' => 'required|exists:doctors,id',
        ]);

        $data['treatment_plan_id'] = $plan->id;
        $data['status'] = 'scheduled';

        ChemoCycle::create($data);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Chemo cycle scheduled.');
    }

    public function complete(Request $request, ChemoCycle $cycle): RedirectResponse
    {
        $data = $request->validate([
            'actual_date' => 'required|date',
            'delayed_reason' => 'nullable|string',
        ]);

        $cycle->update([
            'status' => 'completed',
            'actual_date' => $data['actual_date'],
            'delayed_reason' => $data['delayed_reason'] ?? null,
        ]);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Chemo cycle completed.');
    }
}
