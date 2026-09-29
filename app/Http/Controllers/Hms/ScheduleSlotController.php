<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleSlotController extends Controller
{
    public function index(Schedule $schedule)
    {
        $slots = $schedule->slots()->orderBy('day_of_week')->orderBy('start_time')->get();

        return response()->json(['schedule' => $schedule, 'slots' => $slots]);
    }

    public function store(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration_minutes' => 'nullable|integer|min:5|max:480',
            'max_appointments' => 'nullable|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        $data['schedule_id'] = $schedule->id;
        $data['slot_duration_minutes'] = $data['slot_duration_minutes'] ?? 30;
        $data['max_appointments'] = $data['max_appointments'] ?? 1;

        ScheduleSlot::create($data);

        return back()->with('success', 'Schedule slot created successfully!');
    }
}
