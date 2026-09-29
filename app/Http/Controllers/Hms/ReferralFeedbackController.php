<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralFeedback;
use App\Models\ReferralStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralFeedbackController extends Controller
{
    public function store(Request $request, Referral $referral): RedirectResponse
    {
        $data = $request->validate([
            'treatment_provided' => 'required|string',
            'outcome' => 'required|string',
            'feedback_date' => 'required|date',
        ]);

        $feedback = $referral->feedback()->create([
            'treatment_provided' => $data['treatment_provided'],
            'outcome' => $data['outcome'],
            'feedback_date' => $data['feedback_date'],
            'feedback_by' => auth()->id(),
        ]);

        ReferralStatusHistory::create([
            'referral_id' => $referral->id,
            'from_status' => $referral->status,
            'to_status' => 'completed',
            'changed_by' => auth()->id(),
            'changed_at' => now(),
            'notes' => 'Feedback recorded. Outcome: ' . $data['outcome'],
        ]);

        $referral->update(['status' => 'completed', 'completed_at' => now()]);

        return back()->with('success', 'Referral feedback recorded successfully!');
    }
}
