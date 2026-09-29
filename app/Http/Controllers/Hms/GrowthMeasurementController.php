<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\GrowthMeasurement;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrowthMeasurementController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'recorded_date' => 'required|date',
            'weight_grams' => 'required|integer|min:1',
            'height_cm' => 'required|numeric|min:0',
            'head_circumference_cm' => 'required|numeric|min:0',
            'weight_for_age_zscore' => 'nullable|numeric',
            'height_for_age_zscore' => 'nullable|numeric',
            'weight_for_height_zscore' => 'nullable|numeric',
            'malnutrition_status' => 'nullable|in:none,mild,moderate,severe',
        ]);

        $data['recorded_by'] = auth()->id();

        $heightM = $data['height_cm'] / 100;
        $data['bmi'] = $heightM > 0 ? round($data['weight_grams'] / 1000 / ($heightM * $heightM), 2) : null;

        if (isset($data['weight_for_height_zscore'])) {
            $zscore = $data['weight_for_height_zscore'];
            $data['malnutrition_status'] = $data['malnutrition_status'] ?? match (true) {
                $zscore < -3 => 'severe',
                $zscore < -2 => 'moderate',
                $zscore < -1 => 'mild',
                default => 'none',
            };
        }

        GrowthMeasurement::create($data);

        return back()->with('status', 'Growth measurement recorded');
    }

    public function chart(Patient $patient): View
    {
        $measurements = GrowthMeasurement::where('patient_id', $patient->id)
            ->orderBy('recorded_date')
            ->get();

        return view('hms.paediatrics.growth-chart', compact('patient', 'measurements'));
    }
}
