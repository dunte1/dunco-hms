<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DutyRoster;
use App\Models\DutyRosterEntry;
use App\Models\Ward;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DutyRosterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'roster_date' => 'required|date',
            'shift_type' => 'required|in:day,night',
            'roster_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'draft';

        DutyRoster::create($data);

        return back()->with('success', 'Duty roster created successfully!');
    }

    public function addEntry(Request $request, DutyRoster $roster): RedirectResponse
    {
        $data = $request->validate([
            'nurse_user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $data['roster_id'] = $roster->id;
        $data['status'] = 'scheduled';

        DutyRosterEntry::create($data);

        return back()->with('success', 'Nurse assigned to roster successfully!');
    }

    public function publish(DutyRoster $roster): RedirectResponse
    {
        $roster->update([
            'status' => 'published',
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        return back()->with('success', 'Duty roster published successfully!');
    }
}
