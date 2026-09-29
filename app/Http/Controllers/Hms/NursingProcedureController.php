<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\NursingProcedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NursingProcedureController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'procedure_name' => 'required|string',
            'description' => 'nullable|string',
            'body_site' => 'nullable|string',
            'outcome' => 'nullable|string',
            'complications' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);

        $data['performed_by'] = auth()->id();

        NursingProcedure::create($data);

        return back()->with('success', 'Nursing procedure recorded successfully!');
    }
}
