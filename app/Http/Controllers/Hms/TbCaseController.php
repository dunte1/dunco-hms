<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TbCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TbCaseController extends Controller
{
    public function index(): View
    {
        $cases = TbCase::with('patient')->orderByDesc('created_at')->paginate(20);
        return view('hms.tb.cases.index', compact('cases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis_date' => 'required|date',
            'specimen_type' => 'required|in:sputum,urine,tissue,csf,other',
            'test_method' => 'required|in:microscopy,gene_xpert,culture,xpert_mdr',
            'test_result' => 'required|in:positive,negative',
            'pulmonary' => 'boolean',
            'drug_susceptible' => 'boolean',
            'mdr_tb' => 'boolean',
        ]);

        $data['registered_by'] = auth()->id();
        $data['status'] = 'active';

        TbCase::create($data);

        return back()->with('status', 'TB case registered');
    }

    public function update(Request $request, TbCase $case): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'sometimes|in:active,treatment_completed,lost_to_followup,died,transferred,treatment_failed',
            'treatment_start_date' => 'nullable|date',
            'treatment_end_date' => 'nullable|date',
            'outcome_date' => 'nullable|date',
            'outcome' => 'nullable|string',
            'pulmonary' => 'boolean',
            'drug_susceptible' => 'boolean',
            'mdr_tb' => 'boolean',
        ]);

        $case->update($data);

        return back()->with('status', 'TB case updated');
    }
}
