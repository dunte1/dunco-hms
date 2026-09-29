<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TraineeRecord;
use App\Models\TraineeAssessment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TraineeAssessmentController extends Controller
{
    public function store(Request $request, TraineeRecord $trainee): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_date' => 'required|date',
            'assessment_type' => 'required|in:midterm,final,clinical,skills',
            'score' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:10',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $validated['trainee_id'] = $trainee->id;
        $validated['assessed_by'] = $request->user()->id;

        TraineeAssessment::create($validated);

        return redirect()->route('hms.training.trainees.index')
            ->with('success', 'Assessment recorded successfully.');
    }
}
