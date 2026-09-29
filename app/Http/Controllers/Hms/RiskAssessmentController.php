<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessmentRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'area_or_activity' => 'required|string|max:255',
            'hazard_identified' => 'required|string',
            'risk_level' => 'required|in:low,moderate,high,critical',
            'existing_controls' => 'required|string',
            'additional_controls' => 'nullable|string',
            'likelihood' => 'required|in:unlikely,possible,likely,almost_certain',
            'consequence' => 'required|in:insignificant,minor,moderate,major,catastrophic',
            'residual_risk_level' => 'required|in:low,moderate,high,critical',
            'assessment_date' => 'required|date',
            'next_review_date' => 'nullable|date|after_or_equal:assessment_date',
        ]);

        $data['assessed_by'] = auth()->id();
        $data['status'] = 'active';

        RiskAssessmentRecord::create($data);

        return back()->with('status', 'Risk assessment record created.');
    }

    public function index(Request $request): View
    {
        $query = RiskAssessmentRecord::with('assessedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        $assessments = $query->latest('assessment_date')->paginate(20);

        return view('hms.ohs.risk-assessments.index', compact('assessments'));
    }
}
