<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\PreopAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreopAssessmentController extends Controller
{
    public function store(Request $request, OtSchedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'asa_classification' => 'required|integer|min:1|max:6',
            'airway_assessment' => 'required|in:easy,difficult,unknown',
            'comorbidities' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
            'npo_status' => 'required|boolean',
            'fasting_hours' => 'nullable|integer|min:0',
            'airway_teeth_prosthesis' => 'nullable|boolean',
            'weight_kg' => 'nullable|numeric|min:0',
            'allergies_confirmed' => 'nullable|boolean',
            'risks_identified' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        $data['patient_id'] = $schedule->patient_id;
        $data['ot_schedule_id'] = $schedule->id;
        $data['assessor_id'] = $schedule->surgeon_id;
        $data['assessed_at'] = now();

        PreopAssessment::create($data);

        return back()->with('status', 'Pre-operative assessment completed');
    }
}
