<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\SedationScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SedationController extends Controller
{
    public function store(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'score_type' => 'required|in:rass,sas,ramsay',
            'score_value' => 'required|integer',
            'assessment_time' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data['icu_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;
        $data['assessed_by'] = auth()->id();

        SedationScore::create($data);

        return redirect()->back()->with('success', 'Sedation score recorded.');
    }
}
