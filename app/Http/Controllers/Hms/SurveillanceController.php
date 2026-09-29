<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SurveillanceCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveillanceController extends Controller
{
    public function index(): View
    {
        $cases = SurveillanceCase::with(['patient', 'facility', 'reportedBy'])
            ->orderByDesc('case_date')
            ->paginate(20);

        return view('hms.public-health.surveillance', compact('cases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'disease_name' => 'required|string|max:255',
            'icd_code' => 'nullable|string|max:20',
            'notification_type' => 'required|in:case,outbreak,cluster',
            'case_date' => 'required|date',
            'facility_id' => 'nullable|exists:hospital_branches,id',
            'county' => 'nullable|string|max:100',
            'sub_county' => 'nullable|string|max:100',
        ]);

        $data['reported_by'] = auth()->id();
        SurveillanceCase::create($data);

        return back()->with('status', 'Surveillance case reported');
    }
}
