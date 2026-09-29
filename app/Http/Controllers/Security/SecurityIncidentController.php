<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\SecurityIncident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityIncidentController extends Controller
{
    public function index(Request $request): View
    {
        $query = SecurityIncident::with('reportedBy', 'resolvedBy');

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

        return view('hms.security.incidents.index', compact('incidents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'incident_type' => 'required|in:theft,assault,trespass,vandalism,unauthorized_access,drug_related,other',
            'severity' => 'required|in:low,medium,high,critical',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'witnesses' => 'nullable|string',
        ]);

        $todayCount = SecurityIncident::whereDate('reported_at', today())->count();
        $data['incident_number'] = 'SI-' . date('Ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        $data['reported_by'] = auth()->id();
        $data['reported_at'] = now();
        $data['status'] = 'reported';

        SecurityIncident::create($data);

        return redirect()->route('incidents.index')
            ->with('success', "Security incident {$data['incident_number']} reported successfully.");
    }

    public function resolve(Request $request, SecurityIncident $incident): RedirectResponse
    {
        $data = $request->validate([
            'actions_taken' => 'required|string',
        ]);

        $incident->update([
            'actions_taken' => $data['actions_taken'],
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return redirect()->route('incidents.index')
            ->with('success', "Incident {$incident->incident_number} resolved successfully.");
    }
}
