<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceCrew;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceCrewController extends Controller
{
    public function index(Request $request): View
    {
        $query = AmbulanceCrew::with(['ambulance', 'user']);

        if ($request->filled('ambulance_id')) {
            $query->where('ambulance_id', $request->ambulance_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }

        $crews = $query->latest()->paginate(15)->withQueryString();

        return view('hms.ambulance.crews', compact('crews'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:driver,paramedic,attendant',
            'is_primary' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        AmbulanceCrew::create($data);

        return redirect()->route('hms.ambulance.crews.index')->with('status', 'Crew member assigned');
    }
}
