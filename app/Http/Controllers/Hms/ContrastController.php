<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ContrastRecord;
use App\Models\RadiologyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ContrastController extends Controller
{
    public function store(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'contrast_type' => 'required|in:iodine,gadolinium,barium,none',
            'contrast_agent' => 'nullable|string',
            'volume_ml' => 'nullable|numeric|min:0',
            'route' => 'required|in:iv,oral,rectal',
            'reaction_notes' => 'nullable|string',
            'administered_at' => 'nullable|date',
        ]);

        $validated['radiology_request_id'] = $radiologyRequest->id;
        $validated['administered_by'] = auth()->id();
        $validated['administered_at'] = $validated['administered_at'] ?? now();

        ContrastRecord::create($validated);

        return redirect()->route('hms.radiology.requests.show', $radiologyRequest)
            ->with('success', 'Contrast administration recorded.');
    }
}
