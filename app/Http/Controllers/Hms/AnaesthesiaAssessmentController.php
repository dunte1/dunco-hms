<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnaesthesiaAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ot_schedule_id' => 'required|exists:ot_schedules,id',
            'assessor_id' => 'required|exists:doctors,id',
            'asa_classification' => 'required|integer|in:1,2,3,4,5,6',
            'airway_assessment' => 'required|in:easy,difficult,unknown',
            'mallampati_score' => 'nullable|integer|in:1,2,3,4',
            'mouth_opening_cm' => 'nullable|numeric|min:0',
            'neck_mobility' => 'required|in:full,restricted',
            'previous_anaesthesia_experience' => 'nullable|string',
            'airway_teeth_prosthesis' => 'nullable|boolean',
            'airway_plan' => 'required|string',
            'anaesthesia_plan' => 'required|string',
            'risk_assessment' => 'nullable|string',
            'assessed_at' => 'required|date',
        ]);

        AnaesthesiaAssessment::create($data);

        return redirect()->route('hms.ot.show', $data['ot_schedule_id'])
            ->with('status', 'Pre-anaesthetic assessment recorded successfully');
    }
}
