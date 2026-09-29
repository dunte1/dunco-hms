<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SafetyIncident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SafetyIncidentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'incident_type' => 'required|in:fire,near_miss,injury,exposure,equipment_failure,chemical,other',
            'severity' => 'required|in:low,moderate,high,critical',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'body_area_affected' => 'nullable|string',
            'injury_type' => 'nullable|string',
            'first_aid_given' => 'boolean',
            'hospital_visit' => 'boolean',
        ]);

        $todayCount = SafetyIncident::whereDate('reported_at', today())->count();
        $data['incident_number'] = 'FSI-' . date('Ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        $data['reported_by'] = auth()->id();
        $data['reported_at'] = now();
        $data['status'] = 'reported';

        SafetyIncident::create($data);

        return back()->with('status', "Safety incident {$data['incident_number']} reported.");
    }

    public function index(Request $request): View
    {
        $query = SafetyIncident::with('reportedBy', 'resolvedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('incident_type')) {
            $query->where('incident_type', $request->incident_type);
        }

        $incidents = $query->latest('reported_at')->paginate(20);

        return view('hms.ohs.safety-incidents.index', compact('incidents'));
    }

    public function resolve(Request $request, SafetyIncident $incident): RedirectResponse
    {
        $data = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $incident->update([
            'resolution_notes' => $data['resolution_notes'],
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('status', "Incident {$incident->incident_number} resolved.");
    }
}
