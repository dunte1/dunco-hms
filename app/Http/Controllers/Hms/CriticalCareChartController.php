<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\CriticalCareChart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CriticalCareChartController extends Controller
{
    public function store(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'chart_date' => 'required|date',
            'hour' => 'required|integer|between:0,23',
            'heart_rate' => 'nullable|integer|min:0',
            'blood_pressure_sys' => 'nullable|integer|min:0',
            'blood_pressure_dia' => 'nullable|integer|min:0',
            'map' => 'nullable|integer|min:0',
            'respiratory_rate' => 'nullable|integer|min:0',
            'spo2' => 'nullable|numeric|min:0|max:100',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'gcs_eye' => 'nullable|integer|between:1,4',
            'gcs_verbal' => 'nullable|integer|between:1,5',
            'gcs_motor' => 'nullable|integer|between:1,6',
            'pupil_left' => 'nullable|string|max:50',
            'pupil_right' => 'nullable|string|max:50',
            'urine_output_ml' => 'nullable|numeric|min:0',
            'fluid_balance' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $data['icu_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;
        $data['recorded_by'] = auth()->id();

        if (!empty($data['gcs_eye']) && !empty($data['gcs_verbal']) && !empty($data['gcs_motor'])) {
            $data['gcs_total'] = $data['gcs_eye'] + $data['gcs_verbal'] + $data['gcs_motor'];
        }

        CriticalCareChart::create($data);

        return redirect()->back()->with('success', 'Hourly chart entry recorded.');
    }
}
