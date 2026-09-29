<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\FpVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FpVisitController extends Controller
{
    public function index(): View
    {
        $visits = FpVisit::with(['patient', 'counseledBy'])
            ->orderByDesc('visit_date')
            ->paginate(20);

        return view('hms.public-health.fp-visits', compact('visits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'method' => 'required|in:pills,injectable,implant,IUD,condom,sterilization,withdrawal,none',
            'previous_method' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'satisfaction_score' => 'nullable|integer|min:1|max:5',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ]);

        $data['counseled_by'] = auth()->id();
        FpVisit::create($data);

        return back()->with('status', 'Family planning visit recorded');
    }
}
