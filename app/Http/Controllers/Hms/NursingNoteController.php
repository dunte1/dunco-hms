<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\NursingNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NursingNoteController extends Controller
{
    public function index(Request $request, IpdAdmission $ipd): View
    {
        $nursingNotes = NursingNote::where('ipd_admission_id', $ipd->id)
            ->with(['nurse'])
            ->latest()
            ->paginate(15);

        return view('hms.ipd.nursing-notes', compact('ipd', 'nursingNotes'));
    }

    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'note_type' => 'required|in:assessment,intervention,observation,education',
            'content' => 'required|string',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;
        $data['nurse_id'] = auth()->id();

        NursingNote::create($data);

        return back()->with('success', 'Nursing note recorded successfully!');
    }
}
