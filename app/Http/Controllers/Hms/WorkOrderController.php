<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'request_id' => 'nullable|exists:maintenance_requests,id',
            'equipment_id' => 'nullable|exists:medical_equipment,id',
            'work_type' => 'required|in:corrective,preventive,emergency',
            'title' => 'required|string',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'parts_used' => 'nullable|string',
            'labor_hours' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $data['status'] = 'open';
        WorkOrder::create($data);

        return back()->with('status', 'Work order created');
    }

    public function complete(WorkOrder $order): RedirectResponse
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($order->request) {
            $order->request->update(['status' => 'completed']);
        }

        return back()->with('status', 'Work order completed');
    }
}
