<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\RecoveryRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecoveryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ot_schedule_id' => 'required|exists:ot_schedules,id',
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'nullable|exists:beds,id',
            'gcs' => 'nullable|integer|min:3|max:15',
            'vital_signs_snapshot' => 'nullable|array',
            'pain_score' => 'nullable|integer|min:0|max:10',
            'nausea_vomiting' => 'nullable|boolean',
            'temperature' => 'nullable|numeric|min:30|max:42',
            'nurse_id' => 'nullable|exists:users,id',
        ]);

        $data['admission_time'] = now();
        $data['status'] = 'monitoring';

        RecoveryRecord::create($data);

        return back()->with('status', 'Patient admitted to recovery');
    }

    public function discharge(Request $request, RecoveryRecord $recovery): RedirectResponse
    {
        $data = $request->validate([
            'discharge_criteria_met' => 'required|boolean',
            'discharge_notes' => 'nullable|string',
        ]);

        $recovery->update(array_merge($data, [
            'discharge_time' => now(),
            'status' => 'recovered',
        ]));

        return back()->with('status', 'Patient discharged from recovery');
    }
}
