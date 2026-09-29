<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\WardRound;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WardRoundController extends Controller
{
    public function index(Request $request, IpdAdmission $ipd): View
    {
        $wardRounds = WardRound::where('ipd_admission_id', $ipd->id)
            ->with(['doctor', 'creator'])
            ->latest('round_date')
            ->paginate(15);

        return view('hms.ipd.ward-rounds', compact('ipd', 'wardRounds'));
    }

    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'round_date' => 'required|date',
            'findings' => 'nullable|string',
            'orders' => 'nullable|string',
            'status' => 'required|in:active,completed',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;
        $data['created_by'] = auth()->id();

        WardRound::create($data);

        return back()->with('success', 'Ward round recorded successfully!');
    }
}
