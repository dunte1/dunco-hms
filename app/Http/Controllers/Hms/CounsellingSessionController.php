<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CounsellingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CounsellingSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'session_date' => 'required|date',
            'session_type' => 'required|in:individual,group,family,crisis',
            'presenting_issue' => 'required|string',
            'interventions_used' => 'required|string',
            'patient_response' => 'nullable|string',
            'risk_level' => 'required|in:low,moderate,high',
            'next_session_date' => 'nullable|date',
        ]);

        $data['counsellor_id'] = auth()->id();

        CounsellingSession::create($data);

        return back()->with('status', 'Counselling session recorded');
    }
}
