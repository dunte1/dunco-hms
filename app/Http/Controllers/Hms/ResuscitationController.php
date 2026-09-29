<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAdmission;
use App\Models\ResuscitationRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResuscitationController extends Controller
{
    public function store(Request $request, EmergencyAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'presenting_complaint' => 'required|string',
            'initial_assessment' => 'required|string',
            'airway_status' => 'required|string|max:20',
            'breathing_status' => 'required|string|max:20',
            'circulation_status' => 'required|string|max:20',
            'disability_neurological' => 'required|string|max:20',
            'exposure' => 'required|string',
            'interventions' => 'required|string',
            'outcome' => 'required|in:survived,died,in_transit',
            'time_of_arrest' => 'nullable|date',
            'resuscitation_duration_minutes' => 'nullable|integer|min:0',
            'performed_by' => 'nullable|exists:doctors,id',
        ]);

        $data['emergency_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;

        ResuscitationRecord::create($data);

        return back()->with('success', 'Resuscitation record created successfully!');
    }
}
