<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ClaimRejection;
use App\Models\InsuranceClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaimRejectionController extends Controller
{
    public function store(Request $request, InsuranceClaim $claim): RedirectResponse
    {
        $data = $request->validate([
            'rejection_code' => 'required|string|max:255',
            'rejection_reason' => 'required|string',
            'amount_rejected' => 'required|numeric|min:0',
            'batch_id' => 'nullable|exists:claim_batches,id',
        ]);

        ClaimRejection::create(array_merge($data, [
            'insurance_claim_id' => $claim->id,
        ]));

        $claim->update(['status' => 'rejected']);

        return back()->with('status', 'Claim rejection recorded');
    }

    public function handle(Request $request, ClaimRejection $rejection): RedirectResponse
    {
        $data = $request->validate([
            'action_taken' => 'required|in:resubmit,withdraw,write_off,appeal',
        ]);

        $rejection->update([
            'action_taken' => $data['action_taken'],
            'action_by' => auth()->id(),
            'action_at' => now(),
        ]);

        $claim = $rejection->insuranceClaim;

        if ($data['action_taken'] === 'resubmit') {
            $claim->update(['status' => 'pending']);
        } elseif ($data['action_taken'] === 'write_off') {
            $claim->update([
                'status' => 'paid',
                'approved_amount' => $claim->paid_amount,
            ]);
        } elseif ($data['action_taken'] === 'withdraw') {
            $claim->update(['status' => 'rejected']);
        }

        return back()->with('status', 'Rejection handled: ' . $data['action_taken']);
    }
}
