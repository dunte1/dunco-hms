<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceFuelLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AmbulanceFuelController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'fill_date' => 'required|date',
            'liters' => 'required|numeric|min:0.01',
            'cost' => 'required|numeric|min:0',
            'odometer_km' => 'nullable|integer|min:0',
            'station' => 'nullable|string|max:255',
        ]);

        $data['recorded_by'] = $request->user()->id;

        AmbulanceFuelLog::create($data);

        return redirect()->route('hms.ambulance.index')->with('status', 'Fuel log recorded');
    }
}
