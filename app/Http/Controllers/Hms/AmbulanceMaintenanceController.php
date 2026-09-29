<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceMaintenanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceMaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = AmbulanceMaintenanceRecord::with('ambulance');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ambulance_id')) {
            $query->where('ambulance_id', $request->ambulance_id);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        $records = $query->latest('service_date')->paginate(15)->withQueryString();

        return view('hms.ambulance.maintenance', compact('records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'maintenance_type' => 'required|in:preventive,corrective,emergency',
            'description' => 'required|string',
            'service_date' => 'required|date',
            'next_service_date' => 'nullable|date|after:service_date',
            'cost' => 'nullable|numeric|min:0',
            'provider' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,in_progress,completed',
        ]);

        AmbulanceMaintenanceRecord::create($data);

        return redirect()->route('hms.ambulance.maintenance.index')->with('status', 'Maintenance record created');
    }
}
