<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ClaimBatch;
use App\Models\ClaimRemittance;
use App\Models\InsuranceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaimRemittanceController extends Controller
{
    public function store(Request $request, ClaimBatch $batch): RedirectResponse
    {
        $data = $request->validate([
            'remittance_number' => 'required|string|max:255',
            'remittance_date' => 'required|date',
            'remitted_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $variance = $data['remitted_amount'] - $batch->total_amount;

        ClaimRemittance::create(array_merge($data, [
            'batch_id' => $batch->id,
            'insurance_provider_id' => $batch->insurance_provider_id,
            'variance' => $variance,
            'status' => 'received',
        ]));

        if ($batch->status === 'submitted') {
            $batch->update(['status' => 'accepted']);
        }

        return back()->with('status', 'Remittance ' . $data['remittance_number'] . ' recorded');
    }

    public function reconcile(ClaimRemittance $remittance): RedirectResponse
    {
        $remittance->reconcile(auth()->user());

        return back()->with('status', 'Remittance reconciled successfully');
    }
}
