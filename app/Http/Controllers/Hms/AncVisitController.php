<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AncVisit;
use App\Models\Pregnancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AncVisitController extends Controller
{
    public function store(Request $request, Pregnancy $pregnancy): RedirectResponse
    {
        $data = $request->validate([
            'visit_number' => 'required|integer|min:1',
            'visit_date' => 'required|date',
            'gestational_age_weeks' => 'required|integer|min:0|max:42',
            'weight_kg' => 'nullable|numeric|min:0',
            'blood_pressure_sys' => 'nullable|integer|min:50|max:300',
            'blood_pressure_dia' => 'nullable|integer|min:30|max:200',
            'hemoglobin' => 'nullable|numeric|min:0|max:25',
            'urine_protein' => 'nullable|string|max:20',
            'urine_glucose' => 'nullable|string|max:20',
            'fundal_height' => 'nullable|numeric|min:0',
            'fetal_heart_rate' => 'nullable|integer|min:60|max:200',
            'presentation' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['pregnancy_id'] = $pregnancy->id;
        $data['visited_by'] = auth()->id();

        AncVisit::create($data);

        return back()->with('success', 'ANC visit recorded successfully!');
    }
}
