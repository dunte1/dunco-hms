<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\InfusionRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InfusionController extends Controller
{
    public function store(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'drug_name' => 'required|string|max:100',
            'concentration' => 'nullable|string|max:50',
            'rate_ml_hr' => 'required|numeric|min:0',
            'volume_infused_ml' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
        ]);

        $data['icu_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;
        $data['status'] = 'running';

        InfusionRecord::create($data);

        return redirect()->back()->with('success', 'Infusion record created.');
    }

    public function stop(Request $request, InfusionRecord $infusion): RedirectResponse
    {
        $data = $request->validate([
            'end_time' => 'required|date',
            'volume_infused_ml' => 'nullable|numeric|min:0',
            'reason_for_stop' => 'nullable|string',
        ]);

        $infusion->update([
            'end_time' => $data['end_time'],
            'volume_infused_ml' => $data['volume_infused_ml'] ?? $infusion->volume_infused_ml,
            'reason_for_stop' => $data['reason_for_stop'],
            'status' => 'stopped',
            'stopped_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Infusion stopped.');
    }
}
