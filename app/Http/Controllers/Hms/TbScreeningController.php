<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TbScreening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TbScreeningController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'screening_date' => 'required|date',
            'symptoms_cough' => 'boolean',
            'symptoms_fever' => 'boolean',
            'symptoms_night_sweats' => 'boolean',
            'symptoms_weight_loss' => 'boolean',
            'symptoms_other' => 'nullable|string',
            'contact_history' => 'boolean',
            'hiv_status' => 'required|in:positive,negative,unknown',
            'chest_xray_result' => 'nullable|string',
            'screen_result' => 'required|in:positive,negative,suspected',
        ]);

        $data['screened_by'] = auth()->id();

        TbScreening::create($data);

        return back()->with('status', 'TB screening recorded');
    }
}
