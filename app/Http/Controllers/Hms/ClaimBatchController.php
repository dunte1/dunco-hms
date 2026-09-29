<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ClaimBatch;
use App\Models\InsuranceClaim;
use App\Models\InsuranceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaimBatchController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'insurance_provider_id' => 'required|exists:insurance_providers,id',
            'claim_ids' => 'required|array|min:1',
            'claim_ids.*' => 'exists:insurance_claims,id',
            'notes' => 'nullable|string',
        ]);

        $provider = InsuranceProvider::findOrFail($data['insurance_provider_id']);

        $batch = ClaimBatch::create([
            'batch_number' => 'BATCH-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'insurance_provider_id' => $provider->id,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
        ]);

        $claims = InsuranceClaim::whereIn('id', $data['claim_ids'])->get();

        $totalAmount = 0;
        foreach ($claims as $claim) {
            $claim->update(['batch_id' => $batch->id]);
            $totalAmount += $claim->claimed_amount;
        }

        $batch->update([
            'claim_count' => $claims->count(),
            'total_amount' => $totalAmount,
        ]);

        return back()->with('status', 'Claim batch ' . $batch->batch_number . ' created with ' . $claims->count() . ' claims');
    }

    public function submit(ClaimBatch $batch): RedirectResponse
    {
        if ($batch->status !== 'draft') {
            return back()->with('error', 'Only draft batches can be submitted');
        }

        $batch->submit(auth()->user());

        return back()->with('status', 'Batch ' . $batch->batch_number . ' submitted successfully');
    }
}
