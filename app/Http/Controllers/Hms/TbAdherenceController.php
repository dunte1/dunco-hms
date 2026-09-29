<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TbAdherenceLog;
use App\Models\TbTreatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TbAdherenceController extends Controller
{
    public function store(Request $request, TbTreatment $treatment): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'log_date' => 'required|date',
            'doses_expected' => 'required|integer|min:1',
            'doses_taken' => 'required|integer|min:0',
            'missed_reason' => 'nullable|string',
            'counselling_done' => 'boolean',
        ]);

        $data['tb_treatment_id'] = $treatment->id;
        $data['logged_by'] = auth()->id();
        $data['adherence_percentage'] = round(($data['doses_taken'] / $data['doses_expected']) * 100, 2);

        TbAdherenceLog::create($data);

        return back()->with('status', 'Adherence log recorded');
    }
}
