<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcdCodingRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IcdCodingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'primary_diagnosis_code' => 'required|string|max:20',
            'primary_diagnosis_desc' => 'required|string|max:500',
            'secondary_diagnosis_codes' => 'nullable|array',
            'secondary_diagnosis_codes.*' => 'string',
            'procedure_codes' => 'nullable|array',
            'procedure_codes.*' => 'string',
        ]);

        $data['coded_by'] = auth()->id();
        $data['coded_at'] = now();
        $data['coding_status'] = 'pending';

        IcdCodingRecord::create($data);

        return back()->with('status', 'ICD coding record created');
    }

    public function approve(IcdCodingRecord $coding): RedirectResponse
    {
        $coding->update([
            'coding_status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'ICD coding approved');
    }
}
