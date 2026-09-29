<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaryRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisciplinaryRecordController extends Controller
{
    public function index(): View
    {
        $records = DisciplinaryRecord::with(['employee', 'actionByUser'])
            ->latest()
            ->paginate(10);

        return view('hms.hr.disciplinary.index', compact('records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'incident_date' => 'required|date',
            'description' => 'required|string',
            'category' => 'required|in:warning,verbal_written,suspension,termination,other',
            'severity' => 'required|in:minor,moderate,major,critical',
            'action_taken' => 'required|string',
        ]);

        $data['action_by'] = auth()->id();
        $data['status'] = 'open';

        DisciplinaryRecord::create($data);

        return redirect()->route('hms.hr.disciplinary.index')
            ->with('success', 'Disciplinary record created.');
    }

    public function resolve(Request $request, DisciplinaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $record->update([
            'status' => 'resolved',
            'resolution_date' => now()->toDateString(),
            'resolution_notes' => $data['resolution_notes'],
        ]);

        return redirect()->route('hms.hr.disciplinary.index')
            ->with('success', 'Disciplinary record resolved.');
    }
}
