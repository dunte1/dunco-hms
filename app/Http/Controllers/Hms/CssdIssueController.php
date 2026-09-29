<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CssdIssueRecord;
use App\Models\CssdReturnRecord;
use App\Models\InstrumentSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CssdIssueController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'instrument_set_id' => 'required|exists:instrument_sets,id',
            'issued_to_user_id' => 'required|exists:users,id',
            'theatre_schedule_id' => 'nullable|exists:ot_schedules,id',
            'expected_return_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $data['issued_at'] = now();
        $data['status'] = 'issued';
        CssdIssueRecord::create($data);
        return back()->with('status', 'Instrument set issued successfully');
    }

    public function returnSet(Request $request, CssdIssueRecord $issue): RedirectResponse
    {
        $data = $request->validate([
            'condition' => 'required|in:complete,damaged,missing',
            'missing_items' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $return = CssdReturnRecord::create([
            'issue_record_id' => $issue->id,
            'returned_at' => now(),
            'condition' => $data['condition'],
            'missing_items' => $data['missing_items'] ?? null,
            'inspected_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);

        $issue->update(['status' => 'returned']);

        return back()->with('status', 'Instrument set returned successfully');
    }
}
