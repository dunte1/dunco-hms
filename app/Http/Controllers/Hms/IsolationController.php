<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IsolationOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IsolationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ward_id' => 'required|exists:wards,id',
            'isolation_type' => 'required|in:contact,droplet,airborne',
            'reason' => 'required|string',
            'ordered_by' => 'required|exists:doctors,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'compliance_notes' => 'nullable|string',
        ]);

        $data['status'] = 'active';
        IsolationOrder::create($data);

        return back()->with('status', 'Isolation order created');
    }

    public function discharge(Request $request, IsolationOrder $isolation): RedirectResponse
    {
        $isolation->update([
            'status' => 'completed',
            'end_date' => $request->input('end_date', now()->toDateString()),
        ]);

        return back()->with('status', 'Patient discharged from isolation');
    }
}
