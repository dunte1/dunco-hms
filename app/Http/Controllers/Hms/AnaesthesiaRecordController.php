<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnaesthesiaRecordController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ot_schedule_id' => 'required|exists:ot_schedules,id',
            'patient_id' => 'required|exists:patients,id',
            'anaesthetist_id' => 'required|exists:doctors,id',
            'anaesthesia_type' => 'required|in:general,regional,epidural,spinal,local,sedation',
            'induction_time' => 'nullable|date',
            'intubation_time' => 'nullable|date',
            'start_time' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'in_progress';
        $data['start_time'] = $data['start_time'] ?? now();

        AnaesthesiaRecord::create($data);

        return redirect()->route('hms.ot.show', $data['ot_schedule_id'])
            ->with('status', 'Anaesthesia record created successfully');
    }

    public function complete(Request $request, AnaesthesiaRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'end_time' => 'required|date',
            'extubation_time' => 'nullable|date',
            'total_duration_minutes' => 'nullable|integer|min:0',
            'ebl_ml' => 'nullable|integer|min:0',
            'urine_output_ml' => 'nullable|integer|min:0',
            'fluids_given_ml' => 'nullable|integer|min:0',
            'blood_products' => 'nullable|string',
            'complications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'completed';

        if (!isset($data['total_duration_minutes']) && $record->start_time && isset($data['end_time'])) {
            $data['total_duration_minutes'] = $record->start_time->diffInMinutes(now());
        }

        $record->update($data);

        return redirect()->route('hms.ot.show', $record->ot_schedule_id)
            ->with('status', 'Anaesthesia record completed successfully');
    }
}
