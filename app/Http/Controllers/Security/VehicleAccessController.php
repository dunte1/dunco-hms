<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\VehicleAccessRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleAccessController extends Controller
{
    public function index(): View
    {
        $vehicles = VehicleAccessRecord::with('recordedBy')
            ->latest('arrival_time')
            ->paginate(20);

        return view('hms.security.vehicles.index', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_registration' => 'required|string|max:50',
            'driver_name' => 'required|string|max:255',
            'driver_id_number' => 'nullable|string|max:50',
            'purpose' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'gate_pass_number' => 'nullable|string|max:50',
        ]);

        $data['recorded_by'] = auth()->id();
        $data['arrival_time'] = now();

        VehicleAccessRecord::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle access recorded successfully.');
    }

    public function recordDeparture(Request $request, VehicleAccessRecord $vehicle): RedirectResponse
    {
        $vehicle->update([
            'departure_time' => now(),
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle departure recorded successfully.');
    }
}
