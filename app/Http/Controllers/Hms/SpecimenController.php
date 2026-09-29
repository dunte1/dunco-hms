<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\Specimen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SpecimenController extends Controller
{
    public function store(Request $request, OtSchedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'specimen_type' => 'required|in:tissue,blood,fluid,other',
            'description' => 'required|string|max:255',
            'collection_site' => 'required|string|max:255',
            'container_type' => 'required|string|max:255',
            'sent_to_lab' => 'nullable|boolean',
            'lab_request_id' => 'nullable|exists:lab_requests,id',
        ]);

        $data['ot_schedule_id'] = $schedule->id;
        $data['patient_id'] = $schedule->patient_id;
        $data['collected_by'] = auth()->id();
        $data['collected_at'] = now();

        Specimen::create($data);

        return back()->with('status', 'Specimen collected and recorded');
    }
}
