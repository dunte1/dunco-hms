<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodIssue;
use App\Models\BloodUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodUnitController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'blood_inventory_id' => 'required|exists:blood_inventory,id',
            'donation_id' => 'nullable|exists:blood_donations,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'volume_ml' => 'required|integer|min:100|max:1000',
            'expiry_date' => 'required|date|after:today',
        ]);

        $data['unit_number'] = BloodUnit::generateUnitNumber();
        $data['status'] = 'available';

        BloodUnit::create($data);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Blood unit created: ' . $data['unit_number']);
    }

    public function reserve(Request $request, BloodUnit $unit): RedirectResponse
    {
        if ($unit->status !== 'available') {
            return back()->withErrors(['status' => 'Unit is not available for reservation']);
        }

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $unit->update([
            'status' => 'reserved',
            'reserved_for_patient_id' => $data['patient_id'],
            'reserved_at' => now(),
        ]);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Blood unit reserved');
    }

    public function issue(Request $request, BloodUnit $unit): RedirectResponse
    {
        if ($unit->status !== 'reserved' && $unit->status !== 'available') {
            return back()->withErrors(['status' => 'Unit cannot be issued in current status']);
        }

        $data = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'patient_id' => 'required|exists:patients,id',
        ]);

        BloodIssue::create([
            'blood_unit_id' => $unit->id,
            'blood_request_id' => $data['blood_request_id'],
            'patient_id' => $data['patient_id'],
            'issued_by' => Auth::id(),
            'issued_at' => now(),
        ]);

        $unit->update(['status' => 'issued']);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Blood unit issued');
    }
}
