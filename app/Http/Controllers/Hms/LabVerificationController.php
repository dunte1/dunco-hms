<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabRequestItem;
use App\Models\LabResultVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LabVerificationController extends Controller
{
    public function verify(Request $request, LabRequestItem $item): RedirectResponse
    {
        if ($item->result_value === null) {
            return back()->withErrors(['item' => 'Cannot verify an item without results.']);
        }

        $verification = LabResultVerification::create([
            'lab_request_item_id' => $item->id,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'status' => 'verified',
            'notes' => $request->input('notes'),
        ]);

        $item->update(['status' => 'completed']);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result verified successfully');
    }

    public function approve(Request $request, LabRequestItem $item): RedirectResponse
    {
        $verification = LabResultVerification::where('lab_request_item_id', $item->id)
            ->where('status', 'verified')
            ->latest()
            ->first();

        if (!$verification) {
            return back()->withErrors(['item' => 'No verified result found for this item.']);
        }

        $verification->update([
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'status' => 'approved',
        ]);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result approved successfully');
    }

    public function reject(Request $request, LabRequestItem $item): RedirectResponse
    {
        $data = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $verification = LabResultVerification::where('lab_request_item_id', $item->id)
            ->whereIn('status', ['pending', 'verified'])
            ->latest()
            ->first();

        if (!$verification) {
            return back()->withErrors(['item' => 'No pending or verified result found for this item.']);
        }

        $verification->update([
            'status' => 'rejected',
            'notes' => $data['notes'],
        ]);

        $item->update(['status' => 'pending']);

        return redirect()->route('hms.laboratory.requests.show', $item->lab_request_id)
            ->with('status', 'Result rejected');
    }
}
