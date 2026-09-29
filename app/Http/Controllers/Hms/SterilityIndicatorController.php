<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SterilizerRun;
use App\Models\SterilityIndicatorResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SterilityIndicatorController extends Controller
{
    public function store(Request $request, SterilizerRun $run): RedirectResponse
    {
        $data = $request->validate([
            'indicator_type' => 'required|in:biological,chemical,physical',
            'result' => 'required|in:pass,fail',
            'batch_number' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);
        $data['sterilizer_run_id'] = $run->id;
        $data['recorded_by'] = auth()->id();
        $data['recorded_at'] = now();
        SterilityIndicatorResult::create($data);
        return back()->with('status', 'Indicator result recorded successfully');
    }
}
