<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CrossmatchRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrossmatchController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'patient_id' => 'required|exists:patients,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'sample_date' => 'required|date',
            'requested_by' => 'required|exists:doctors,id',
        ]);

        $data['result'] = 'pending';

        CrossmatchRequest::create($data);

        return redirect()->route('hms.bloodbank.requests')->with('status', 'Crossmatch request created');
    }

    public function result(Request $request, CrossmatchRequest $crossmatch): RedirectResponse
    {
        $data = $request->validate([
            'result' => 'required|in:compatible,incompatible',
            'notes' => 'nullable|string',
        ]);

        $crossmatch->update([
            'result' => $data['result'],
            'tested_by' => Auth::id(),
            'tested_at' => now(),
        ]);

        return redirect()->route('hms.bloodbank.requests')->with('status', 'Crossmatch result recorded');
    }
}
