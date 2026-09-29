<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RecordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecordRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'mrd_file_id' => 'nullable|exists:mrd_files,id',
            'request_type' => 'required|in:access,copy,transfer,disclosure',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data['requested_by'] = auth()->id();
        $data['status'] = 'pending';

        RecordRequest::create($data);

        return back()->with('status', 'Record request submitted');
    }

    public function approve(RecordRequest $request): RedirectResponse
    {
        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Record request approved');
    }

    public function release(RecordRequest $request): RedirectResponse
    {
        $request->update([
            'status' => 'released',
            'released_at' => now(),
        ]);

        return back()->with('status', 'Record request released');
    }
}
