<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\TheatreTeam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TheatreTeamController extends Controller
{
    public function store(Request $request, OtSchedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'role' => 'required|in:surgeon,anaesthetist,assistant_surgeon,theatre_nurse,scrub_nurse,circulator',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $data['ot_schedule_id'] = $schedule->id;
        $data['assigned_at'] = now();

        TheatreTeam::create($data);

        return back()->with('status', 'Team member assigned');
    }

    public function remove(TheatreTeam $member): RedirectResponse
    {
        $member->update(['released_at' => now()]);

        return back()->with('status', 'Team member released');
    }
}
