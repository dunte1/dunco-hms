<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceTrip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceTripController extends Controller
{
    public function index(Request $request): View
    {
        $query = AmbulanceTrip::with(['ambulance', 'patient']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('trip_type')) {
            $query->where('trip_type', $request->trip_type);
        }

        if ($request->filled('ambulance_id')) {
            $query->where('ambulance_id', $request->ambulance_id);
        }

        $trips = $query->latest()->paginate(15)->withQueryString();

        return view('hms.ambulance.trips', compact('trips'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'patient_id' => 'nullable|exists:patients,id',
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'trip_type' => 'required|in:emergency,transfer,referral,discharge',
            'departure_time' => 'nullable|date',
        ]);

        $data['status'] = 'dispatched';

        AmbulanceTrip::create($data);

        return redirect()->route('hms.ambulance.trips.index')->with('status', 'Trip created');
    }

    public function complete(Request $request, AmbulanceTrip $trip): RedirectResponse
    {
        $data = $request->validate([
            'arrival_time' => 'required|date',
            'distance_km' => 'nullable|numeric|min:0',
        ]);

        $trip->update([
            'status' => 'completed',
            'arrival_time' => $data['arrival_time'],
            'distance_km' => $data['distance_km'] ?? $trip->distance_km,
        ]);

        return redirect()->route('hms.ambulance.trips.index')->with('status', 'Trip completed');
    }
}
