<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SocialAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SocialAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'assessment_date' => 'required|date',
            'living_situation' => 'required|in:alone,family,institutional,homeless',
            'income_source' => 'nullable|string',
            'financial_status' => 'required|in:stable,unstable,crisis',
            'family_support' => 'required|in:strong,limited,none',
            'transport_needs' => 'nullable|boolean',
            'housing_needs' => 'nullable|boolean',
            'legal_needs' => 'nullable|boolean',
        ]);

        $data['assessed_by'] = auth()->id();

        SocialAssessment::create($data);

        return back()->with('status', 'Social assessment recorded');
    }
}
