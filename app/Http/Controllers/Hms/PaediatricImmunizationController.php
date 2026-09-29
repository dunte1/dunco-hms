<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ImmunizationSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaediatricImmunizationController extends Controller
{
    public function index(Request $request): View
    {
        $query = ImmunizationSchedule::with(['patient', 'vaccine'])
            ->whereIn('status', ['due', 'overdue']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $schedules = $query->orderBy('due_date')->paginate(20);

        return view('hms.paediatrics.immunizations', compact('schedules'));
    }

    public function complete(ImmunizationSchedule $schedule): RedirectResponse
    {
        $data = request()->validate([
            'batch_number' => 'nullable|string',
            'site' => 'nullable|string',
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

        return redirect()->route('hms.paediatrics.immunizations.index')
            ->with('status', 'Immunization marked as completed');
    }
}
