<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\FamilyPlanningVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FamilyPlanningController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'method' => 'required|in:pills,injectable,implant,IUD,condom,sterilization,withdrawal,none',
            'previous_method' => 'nullable|string|max:50',
            'side_effects' => 'nullable|string',
            'visit_date' => 'required|date',
            'next_visit_date' => 'nullable|date|after_or_equal:visit_date',
        ]);

        $data['counselled_by'] = auth()->id();

        FamilyPlanningVisit::create($data);

        return back()->with('success', 'Family planning visit recorded successfully!');
    }
}
