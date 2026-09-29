<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SickNote;
use App\Models\NumberSequence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SickNoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'diagnosis' => 'required|string',
            'restrictions' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);
        $data['days_off'] = $start->diffInDays($end) + 1;
        $data['sick_note_number'] = NumberSequence::next('sick_note');
        $data['issued_at'] = now();

        SickNote::create($data);

        return back()->with('success', 'Sick note issued (' . $data['sick_note_number'] . ').');
    }
}
