<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(): View
    {
        $complaints = Complaint::with(['patient', 'assignedTo', 'receivedByUser'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('hms.quality.complaints', compact('complaints'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'complainant_name' => 'nullable|string|max:255',
            'complainant_phone' => 'nullable|string|max:50',
            'complainant_email' => 'nullable|email|max:255',
            'department' => 'nullable|string|max:100',
            'complaint_type' => 'required|in:service_quality,waiting_time,billing,staff_conduct,facility,other',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['received_by'] = auth()->id();
        $data['received_at'] = now();
        $data['status'] = 'received';

        Complaint::create($data);

        return back()->with('status', 'Complaint recorded');
    }

    public function resolve(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'resolution' => 'required|string',
            'satisfaction_score' => 'nullable|integer|min:1|max:5',
        ]);

        $complaint->resolve($data['resolution'], $data['satisfaction_score'] ?? null);

        return back()->with('status', 'Complaint resolved');
    }
}
