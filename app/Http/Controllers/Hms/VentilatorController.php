<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\VentilatorSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VentilatorController extends Controller
{
    public function store(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'mode' => 'required|in:volume,pressure,simv,bipap,cpap,niv',
            'set_rate' => 'nullable|integer|min:0',
            'tidal_volume' => 'nullable|integer|min:0',
            'peep' => 'nullable|integer|min:0',
            'fio2' => 'nullable|integer|min:0|max:100',
            'pressure_support' => 'nullable|integer|min:0',
            'start_time' => 'required|date',
            'reason_for_change' => 'nullable|string',
        ]);

        $data['icu_admission_id'] = $admission->id;
        $data['status'] = 'active';
        $data['recorded_by'] = auth()->id();

        VentilatorSetting::create($data);

        return redirect()->back()->with('success', 'Ventilator settings recorded.');
    }

    public function stop(Request $request, VentilatorSetting $ventilator): RedirectResponse
    {
        $data = $request->validate([
            'end_time' => 'required|date',
            'reason_for_change' => 'nullable|string',
        ]);

        $ventilator->update([
            'end_time' => $data['end_time'],
            'reason_for_change' => $data['reason_for_change'] ?? $ventilator->reason_for_change,
            'status' => 'stopped',
        ]);

        return redirect()->back()->with('success', 'Ventilator stopped.');
    }
}
