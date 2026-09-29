<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\SurgicalWaitingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'procedure_name' => 'required|string|max:255',
            'urgency' => 'required|in:elective,urgent,emergency',
            'priority' => 'nullable|integer|min:0',
            'added_by' => 'nullable|exists:doctors,id',
            'target_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $data['added_date'] = now()->toDateString();
        $data['status'] = 'waiting';

        SurgicalWaitingList::create($data);

        return back()->with('status', 'Patient added to surgical waiting list');
    }

    public function remove(SurgicalWaitingList $item): RedirectResponse
    {
        $item->update(['status' => 'cancelled']);

        return back()->with('status', 'Removed from waiting list');
    }

    public function schedule(SurgicalWaitingList $item): RedirectResponse
    {
        $item->update(['status' => 'scheduled']);

        return back()->with('status', 'Item moved to OT scheduling');
    }
}
