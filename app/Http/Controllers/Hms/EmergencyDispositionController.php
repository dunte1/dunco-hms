<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAdmission;
use App\Models\EmergencyDisposition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmergencyDispositionController extends Controller
{
    public function store(Request $request, EmergencyAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'disposition_type' => 'required|in:discharged,admitted,theatre,icu,referred,transferred,deceased',
            'destination_ward' => 'nullable|string',
            'discharge_notes' => 'nullable|string',
            'discharge_instructions' => 'nullable|string',
            'discharged_by' => 'nullable|exists:doctors,id',
            'discharged_at' => 'required|date',
            'billing_deferred' => 'boolean',
        ]);

        $data['emergency_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;

        EmergencyDisposition::create($data);

        return back()->with('success', 'Emergency disposition recorded successfully!');
    }
}
