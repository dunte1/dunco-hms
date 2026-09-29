<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ClinicalNote;
use App\Models\OpdVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalNoteController extends Controller
{
    public function index(OpdVisit $opd): View
    {
        $notes = ClinicalNote::where('opd_visit_id', $opd->id)
            ->with(['doctor'])
            ->latest()
            ->get();

        return view('hms.clinical-notes.index', compact('opd', 'notes'));
    }

    public function store(Request $request, OpdVisit $opd): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'note_type' => 'required|in:history,exam,assessment,plan,procedure,progress',
            'content' => 'required|string',
        ]);

        ClinicalNote::create([
            'patient_id' => $opd->patient_id,
            'opd_visit_id' => $opd->id,
            'doctor_id' => $data['doctor_id'],
            'note_type' => $data['note_type'],
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Clinical note recorded.');
    }
}
