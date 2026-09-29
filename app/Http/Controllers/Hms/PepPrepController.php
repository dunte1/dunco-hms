<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PepPrepRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PepPrepController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'record_type' => 'required|in:PEP,PrEP',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'indication' => 'nullable|string',
            'regimen' => 'nullable|string',
            'status' => 'in:active,completed,discontinued',
            'discontinuation_reason' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $data['prescribed_by'] = auth()->id();

        PepPrepRecord::create($data);

        return redirect()->back()->with('success', "{$data['record_type']} record created successfully!");
    }
}
