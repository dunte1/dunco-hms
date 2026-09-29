<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaRecord;
use App\Models\IntraopVital;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IntraopVitalController extends Controller
{
    public function store(Request $request, AnaesthesiaRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'time_recorded' => 'required|date',
            'heart_rate' => 'nullable|integer|min:0',
            'blood_pressure_sys' => 'nullable|integer|min:0',
            'blood_pressure_dia' => 'nullable|integer|min:0',
            'map' => 'nullable|integer|min:0',
            'spo2' => 'nullable|numeric|min:0|max:100',
            'etco2' => 'nullable|numeric|min:0|max:200',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'respiratory_rate' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['anaesthesia_record_id'] = $record->id;
        $data['recorded_by'] = auth()->id();

        IntraopVital::create($data);

        return redirect()->route('hms.ot.show', $record->ot_schedule_id)
            ->with('status', 'Intra-operative vital recorded successfully');
    }
}
