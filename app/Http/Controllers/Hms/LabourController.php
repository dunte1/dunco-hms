<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabourRecord;
use App\Models\PartographEntry;
use App\Models\Pregnancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabourController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pregnancy_id' => 'required|exists:pregnancies,id',
            'patient_id' => 'required|exists:patients,id',
            'admission_time' => 'required|date',
            'labour_start_time' => 'required|date',
            'membrane_status' => 'required|in:intact,ruptured',
            'rupture_time' => 'nullable|date',
            'cervical_dilation' => 'nullable|integer|min:0|max:14',
            'liquor' => 'nullable|in:clear,meconium,blood',
            'presenting_part' => 'nullable|string|max:50',
            'labour_progress' => 'nullable|string',
            'complications' => 'nullable|string',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $data['status'] = 'active';

        LabourRecord::create($data);

        return back()->with('success', 'Labour admission recorded successfully!');
    }

    public function partograph(Request $request, LabourRecord $labour): RedirectResponse
    {
        $data = $request->validate([
            'time_recorded' => 'required|date',
            'cervical_dilation' => 'nullable|integer|min:0|max:14',
            'descent' => 'nullable|integer|min:0|max:5',
            'contractions_per_10' => 'nullable|integer|min:0|max:10',
            'fetal_heart_rate' => 'nullable|integer|min:60|max:200',
            'liquor' => 'nullable|string|max:20',
            'moulding' => 'nullable|string|max:20',
            'maternal_pulse' => 'nullable|integer|min:40|max:200',
            'maternal_bp' => 'nullable|string|max:20',
            'urine_output' => 'nullable|integer|min:0',
            'oxytocin_dose' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['labour_record_id'] = $labour->id;
        $data['recorded_by'] = auth()->id();

        PartographEntry::create($data);

        return back()->with('success', 'Partograph entry recorded successfully!');
    }
}
