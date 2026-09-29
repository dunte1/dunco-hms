<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HaiSurveillanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HaiSurveillanceController extends Controller
{
    public function index(): View
    {
        $records = HaiSurveillanceRecord::with(['patient', 'ward', 'reportedBy'])
            ->orderByDesc('reported_date')
            ->paginate(20);

        return view('hms.ipc.hai-surveillance', compact('records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'infection_type' => 'required|in:surgical_site,uti,pneumonia,bloodstream,other',
            'organism' => 'nullable|string|max:255',
            'ward_id' => 'nullable|exists:wards,id',
            'onset_date' => 'required|date',
            'reported_date' => 'required|date',
            'status' => 'required|in:suspected,confirmed,ruled_out',
        ]);

        $data['reported_by'] = auth()->id();
        HaiSurveillanceRecord::create($data);

        return back()->with('status', 'HAI surveillance record created');
    }
}
