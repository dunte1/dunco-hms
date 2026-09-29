<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MhAssessment;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MhAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'assessment_date' => 'required|date',
            'presenting_complaint' => 'required|string',
            'mental_status_examination' => 'required|string',
            'risk_assessment' => 'required|in:low,moderate,high,critical',
            'suicidal_ideation' => 'nullable|boolean',
            'homicidal_ideation' => 'nullable|boolean',
            'self_harm_risk' => 'nullable|boolean',
            'substance_use' => 'nullable|string',
            'functioning_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $data['assessed_by'] = auth()->id();

        MhAssessment::create($data);

        return back()->with('status', 'Mental health assessment recorded');
    }
}
