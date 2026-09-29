<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AntibioticUsageRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AntibioticUsageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'antibiotic_name' => 'required|string|max:255',
            'indication' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'ddd' => 'nullable|numeric|min:0',
            'route' => 'required|in:iv,oral',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $data['prescriber_id'] = auth()->id();
        AntibioticUsageRecord::create($data);

        return back()->with('status', 'Antibiotic usage recorded');
    }
}
