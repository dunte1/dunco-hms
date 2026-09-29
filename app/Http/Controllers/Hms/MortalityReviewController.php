<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MortalityReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MortalityReviewController extends Controller
{
    public function index(): View
    {
        $reviews = MortalityReview::with(['patient', 'deathReport', 'reviewedByUser'])
            ->orderByDesc('review_date')
            ->paginate(20);

        return view('hms.quality.mortality-reviews', compact('reviews'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'death_report_id' => 'nullable|exists:death_reports,id',
            'patient_id' => 'required|exists:patients,id',
            'review_date' => 'required|date',
            'review_type' => 'required|in:peer_review,morbidity_mortality,clinical_audit',
            'diagnosis' => 'required|string',
            'contributing_factors' => 'required|string',
            'preventability' => 'required|in:preventable,potentially_preventable,not_preventable,under_review',
            'recommendations' => 'required|string',
        ]);

        $data['reviewed_by'] = auth()->id();
        $data['status'] = 'pending';

        MortalityReview::create($data);

        return back()->with('status', 'Mortality review recorded');
    }
}
