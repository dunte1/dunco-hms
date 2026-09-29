<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceRequestController extends Controller
{
    public function index(): View
    {
        $requests = MaintenanceRequest::with(['equipment', 'requester', 'assignee'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('hms.maintenance.requests.index', compact('requests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:medical_equipment,id',
            'request_type' => 'required|in:corrective,preventive,electrical,plumbing,HVAC',
            'priority' => 'required|in:low,medium,high,critical',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['requested_by'] = auth()->id();
        $data['status'] = isset($data['assigned_to']) ? 'assigned' : 'pending';

        MaintenanceRequest::create($data);

        return back()->with('status', 'Maintenance request created');
    }
}
