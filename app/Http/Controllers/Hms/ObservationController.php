<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAdmission;
use App\Models\ObservationStay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    public function store(Request $request, EmergencyAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'bed_id' => 'nullable|exists:beds,id',
            'observation_duration_hours' => 'required|integer|min:1',
            'observation_purpose' => 'required|string',
            'initial_assessment' => 'required|string',
            'started_at' => 'required|date',
        ]);

        $data['emergency_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;
        $data['status'] = 'active';

        ObservationStay::create($data);

        return back()->with('success', 'Observation stay created successfully!');
    }

    public function discharge(Request $request, ObservationStay $observation): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:discharged,admitted,transferred',
            'disposition' => 'required|string',
            'ended_at' => 'required|date',
        ]);

        $observation->update($data);

        return back()->with('success', 'Observation stay discharged successfully!');
    }
}
