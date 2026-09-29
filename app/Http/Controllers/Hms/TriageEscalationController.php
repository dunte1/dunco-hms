<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Triage;
use App\Models\TriageEscalation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TriageEscalationController extends Controller
{
    public function index(Request $request): View
    {
        $query = TriageEscalation::with(['triage', 'patient', 'escalatedBy', 'escalatedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $escalations = $query->latest()->paginate(15)->withQueryString();

        return view('hms.triage.escalations.index', compact('escalations'));
    }

    public function store(Request $request, Triage $triage): RedirectResponse
    {
        $data = $request->validate([
            'reason' => 'required|string',
            'severity' => 'required|in:moderate,severe,critical',
            'escalated_to' => 'nullable|exists:users,id',
        ]);

        $escalation = TriageEscalation::create([
            'triage_id' => $triage->id,
            'patient_id' => $triage->patient_id,
            'escalated_by' => auth()->id(),
            'escalated_to' => $data['escalated_to'] ?? null,
            'reason' => $data['reason'],
            'severity' => $data['severity'],
            'status' => 'pending',
        ]);

        return redirect()->route('hms.triage.show', $triage)
            ->with('success', 'Triage escalation created successfully!');
    }

    public function acknowledge(TriageEscalation $escalation): RedirectResponse
    {
        $escalation->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'escalated_to' => $escalation->escalated_to ?? auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Escalation acknowledged!');
    }

    public function resolve(Request $request, TriageEscalation $escalation): RedirectResponse
    {
        $data = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $escalation->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $data['resolution_notes'],
        ]);

        return redirect()->back()->with('success', 'Escalation resolved!');
    }
}
