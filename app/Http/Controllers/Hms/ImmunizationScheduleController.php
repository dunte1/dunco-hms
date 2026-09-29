<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ImmunizationSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImmunizationScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = ImmunizationSchedule::with(['patient', 'vaccine', 'administeredBy'])
            ->whereIn('status', ['due', 'scheduled', 'overdue'])
            ->orderBy('due_date')
            ->paginate(20);

        return view('hms.public-health.immunization-schedule', compact('schedules'));
    }

    public function complete(ImmunizationSchedule $schedule): RedirectResponse
    {
        $data = request()->validate([
            'batch_number' => 'nullable|string',
            'site' => 'nullable|in:left_arm,right_arm,thigh',
            'notes' => 'nullable|string',
        ]);

        $schedule->update([
            'status' => 'completed',
            'completed_date' => now()->toDateString(),
            'administered_by' => auth()->id(),
            'batch_number' => $data['batch_number'] ?? $schedule->batch_number,
            'site' => $data['site'] ?? $schedule->site,
            'notes' => $data['notes'] ?? $schedule->notes,
        ]);

        return back()->with('status', 'Immunization marked as completed');
    }
}
