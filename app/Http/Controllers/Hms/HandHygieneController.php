<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HandHygieneObservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HandHygieneController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'observation_date' => 'required|date',
            'opportunities_observed' => 'required|integer|min:0',
            'hand_washes' => 'required|integer|min:0',
            'technique_score' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['observer_id'] = auth()->id();

        if ($data['opportunities_observed'] > 0) {
            $data['compliance_rate'] = round(
                ($data['hand_washes'] / $data['opportunities_observed']) * 100,
                2
            );
        }

        HandHygieneObservation::create($data);

        return back()->with('status', 'Hand hygiene observation recorded');
    }
}
