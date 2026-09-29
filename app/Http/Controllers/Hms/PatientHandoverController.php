<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceTrip;
use App\Models\PatientHandoverRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientHandoverController extends Controller
{
    public function store(Request $request, AmbulanceTrip $trip): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'receiving_facility' => 'nullable|string|max:255',
            'receiving_person' => 'nullable|string|max:255',
            'receiving_department' => 'nullable|string|max:255',
            'clinical_summary' => 'required|string',
            'handover_time' => 'required|date',
            'received_by_name' => 'nullable|string|max:255',
            'signature_path' => 'nullable|string|max:255',
        ]);

        $data['trip_id'] = $trip->id;
        $data['handed_over_by'] = $request->user()->id;
        $data['status'] = 'pending';

        PatientHandoverRecord::create($data);

        return redirect()->route('hms.ambulance.trips.index')->with('status', 'Patient handover recorded');
    }
}
