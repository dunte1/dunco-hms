<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HivCareEnrollment;
use App\Models\ViralLoadResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ViralLoadController extends Controller
{
    public function store(Request $request, HivCareEnrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'result_date' => 'required|date',
            'viral_load_copies' => 'nullable|integer|min:0',
            'detection_limit' => 'nullable|string',
            'suppression_status' => 'required|in:suppressed,unsuppressed,not_tested',
        ]);

        $data['care_enrollment_id'] = $enrollment->id;
        $data['ordered_by'] = auth()->id();
        $data['created_at'] = now();

        ViralLoadResult::create($data);

        return redirect()->route('hms.hiv.care.index')
            ->with('success', 'Viral load result recorded successfully!');
    }
}
