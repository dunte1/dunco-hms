<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\InpatientDischargeSummary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InpatientDischargeSummaryController extends Controller
{
    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'admission_diagnosis' => 'nullable|string',
            'discharge_diagnosis' => 'nullable|string',
            'procedure_performed' => 'nullable|string',
            'treatment_summary' => 'nullable|string',
            'discharge_condition' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'medications_on_discharge' => 'nullable|string',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;

        $summary = InpatientDischargeSummary::updateOrCreate(
            ['ipd_admission_id' => $ipd->id],
            $data
        );

        return back()->with('success', 'Discharge summary saved successfully!');
    }

    public function sign(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $summary = InpatientDischargeSummary::where('ipd_admission_id', $ipd->id)->firstOrFail();

        $summary->update([
            'signed_by' => auth()->id(),
            'signed_at' => now(),
        ]);

        return back()->with('success', 'Discharge summary signed successfully!');
    }
}
