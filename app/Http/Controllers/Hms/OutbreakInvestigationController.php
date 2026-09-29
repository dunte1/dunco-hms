<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OutbreakInvestigation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OutbreakInvestigationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'outbreak_event_id' => 'nullable|exists:outbreak_events,id',
            'disease_name' => 'required|string|max:255',
            'investigation_start_date' => 'required|date',
            'source_identified' => 'nullable|boolean',
            'source_description' => 'nullable|string',
            'control_measures' => 'required|string',
            'status' => 'required|in:active,contained,ended',
        ]);

        $data['investigated_by'] = auth()->id();
        OutbreakInvestigation::create($data);

        return back()->with('status', 'Outbreak investigation created');
    }

    public function update(Request $request, OutbreakInvestigation $investigation): RedirectResponse
    {
        $data = $request->validate([
            'investigation_end_date' => 'nullable|date',
            'source_identified' => 'nullable|boolean',
            'source_description' => 'nullable|string',
            'control_measures' => 'nullable|string',
            'status' => 'required|in:active,contained,ended',
        ]);

        $investigation->update($data);

        return back()->with('status', 'Outbreak investigation updated');
    }
}
