<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\WelfareWaiverRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaiverRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
            'supporting_documents' => 'nullable|string',
        ]);

        $data['requested_by'] = auth()->id();
        $data['status'] = 'pending';

        WelfareWaiverRequest::create($data);

        return back()->with('status', 'Welfare waiver request submitted');
    }

    public function approve(WelfareWaiverRequest $waiver): RedirectResponse
    {
        if ($waiver->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending waivers can be approved.']);
        }

        DB::transaction(fn () => $waiver->approve(auth()->id()));

        return back()->with('status', 'Welfare waiver approved');
    }

    public function reject(WelfareWaiverRequest $waiver): RedirectResponse
    {
        if ($waiver->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending waivers can be rejected.']);
        }

        $waiver->reject(auth()->id());

        return back()->with('status', 'Welfare waiver rejected');
    }
}
