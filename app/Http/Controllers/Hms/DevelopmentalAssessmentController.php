<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentalAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DevelopmentalAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'assessment_date' => 'required|date',
            'age_months' => 'required|integer|min:0',
            'motor_skills' => 'required|string',
            'language_skills' => 'required|string',
            'social_skills' => 'required|string',
            'cognitive_skills' => 'required|string',
            'red_flags' => 'nullable|string',
            'overall_status' => 'required|in:on_track,delayed,critical',
        ]);

        $data['assessed_by'] = auth()->id();

        DevelopmentalAssessment::create($data);

        return back()->with('status', 'Developmental assessment recorded');
    }
}
