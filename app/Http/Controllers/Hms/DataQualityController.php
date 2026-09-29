<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DataQualityIssue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DataQualityController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'issue_type' => 'required|in:missing_field,inconsistent,duplicate,incomplete',
            'patient_id' => 'nullable|exists:patients,id',
            'table_name' => 'nullable|string|max:100',
            'field_name' => 'nullable|string|max:100',
            'issue_description' => 'required|string',
            'severity' => 'required|in:low,medium,high',
        ]);

        $data['status'] = 'open';

        DataQualityIssue::create($data);

        return back()->with('status', 'Data quality issue logged');
    }

    public function resolve(DataQualityIssue $issue, Request $request): RedirectResponse
    {
        $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $issue->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        return back()->with('status', 'Data quality issue resolved');
    }
}
