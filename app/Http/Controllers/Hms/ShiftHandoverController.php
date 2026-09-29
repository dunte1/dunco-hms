<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ShiftHandover;
use App\Models\Ward;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftHandoverController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'shift_type' => 'required|in:day_to_night,night_to_day',
            'handover_date' => 'required|date',
            'handover_from_user_id' => 'required|exists:users,id',
            'handover_to_user_id' => 'required|exists:users,id|different:handover_from_user_id',
            'patient_count' => 'nullable|integer|min:0',
            'critical_patients' => 'nullable|integer|min:0',
            'pending_tasks' => 'nullable|string',
            'completed_tasks' => 'nullable|string',
            'pending_medications' => 'nullable|string',
            'equipment_issues' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['patient_count'] = $data['patient_count'] ?? 0;
        $data['critical_patients'] = $data['critical_patients'] ?? 0;
        $data['status'] = 'pending';

        ShiftHandover::create($data);

        return back()->with('success', 'Shift handover recorded successfully!');
    }

    public function acknowledge(ShiftHandover $handover): RedirectResponse
    {
        $handover->update([
            'status' => 'completed',
            'acknowledged_at' => now(),
        ]);

        return back()->with('success', 'Shift handover acknowledged successfully!');
    }
}
