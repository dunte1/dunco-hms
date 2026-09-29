<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CssdBatch;
use App\Models\CssdCycleRecord;
use App\Models\InstrumentSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CssdCycleController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'batch_id' => 'nullable|exists:cssd_batches,id',
            'instrument_set_id' => 'nullable|exists:instrument_sets,id',
            'cycle_type' => 'required|in:decontamination,cleaning,packing,sterilization',
            'notes' => 'nullable|string',
        ]);
        $data['start_time'] = now();
        $data['performed_by'] = auth()->id();
        $data['status'] = 'in_progress';
        CssdCycleRecord::create($data);
        return back()->with('status', 'Cycle started successfully');
    }

    public function complete(CssdCycleRecord $cycle): RedirectResponse
    {
        $cycle->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);
        return back()->with('status', 'Cycle completed successfully');
    }
}
