<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AdverseEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdverseEventController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'chemo_cycle_id' => 'nullable|exists:chemo_cycles,id',
            'treatment_plan_id' => 'nullable|exists:oncology_treatment_plans,id',
            'grade' => 'required|integer|min:1|max:5',
            'event_type' => 'required|in:nausea,vomiting,fatigue,alopecia,neutropenia,anemia,thrombocytopenia,mucositis,neuropathy,other',
            'description' => 'required|string',
            'onset_date' => 'required|date',
            'resolved_date' => 'nullable|date|after_or_equal:onset_date',
            'management' => 'required|string',
        ]);

        $data['reported_by'] = auth()->id();

        AdverseEvent::create($data);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Adverse event reported.');
    }
}
