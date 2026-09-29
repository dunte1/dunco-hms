<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidentController extends Controller
{
    public function index(): View
    {
        $incidents = Incident::with(['patient', 'reportedByUser', 'resolvedByUser'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('hms.quality.incidents', compact('incidents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'incident_type' => 'required|in:medication_error,near_miss,patient_fall,pressure_injury,device_failure,other',
            'severity' => 'required|in:low,moderate,high,critical',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        $data['reported_by'] = auth()->id();
        $data['reported_at'] = now();
        $data['status'] = 'reported';

        Incident::create($data);

        return back()->with('status', 'Incident reported');
    }

    public function investigate(Request $request, Incident $incident): RedirectResponse
    {
        $data = $request->validate([
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'resolved_by' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['resolved_by']) && !empty($data['root_cause'])) {
            $incident->resolve($data['resolved_by'], $data['root_cause'], $data['corrective_action'] ?? '');
        } else {
            $incident->investigate();
        }

        return back()->with('status', 'Incident updated');
    }
}
