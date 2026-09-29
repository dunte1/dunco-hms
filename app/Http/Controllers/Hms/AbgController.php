<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\AbgResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AbgController extends Controller
{
    public function store(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'ph' => 'required|numeric|between:6,8',
            'pco2' => 'required|numeric|min:0',
            'po2' => 'required|numeric|min:0',
            'hco3' => 'required|numeric|min:0',
            'be' => 'required|numeric',
            'sao2' => 'required|numeric|min:0|max:100',
            'lactate' => 'nullable|numeric|min:0',
            'interpretation' => 'nullable|string',
            'collected_at' => 'required|date',
        ]);

        $data['icu_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;

        AbgResult::create($data);

        return redirect()->back()->with('success', 'ABG result recorded.');
    }
}
