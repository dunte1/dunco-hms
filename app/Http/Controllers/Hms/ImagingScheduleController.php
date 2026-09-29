<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ImagingSchedule;
use App\Models\RadiologyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ImagingScheduleController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'radiology_request_id' => 'required|exists:radiology_requests,id',
            'patient_id' => 'required|exists:patients,id',
            'radiology_test_id' => 'required|exists:radiology_tests,id',
            'modality' => 'required|in:xray,ultrasound,ct,mri,mammography,fluoroscopy',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled';
        $validated['scheduled_by'] = auth()->id();

        ImagingSchedule::create($validated);

        RadiologyRequest::where('id', $validated['radiology_request_id'])
            ->update(['status' => 'scheduled']);

        return redirect()->route('hms.radiology.requests.index')
            ->with('success', 'Imaging study scheduled successfully.');
    }

    public function complete(ImagingSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'completed']);

        return redirect()->route('hms.radiology.requests.index')
            ->with('success', 'Imaging study marked as completed.');
    }

    public function cancel(ImagingSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('hms.radiology.requests.index')
            ->with('success', 'Imaging study cancelled.');
    }
}
