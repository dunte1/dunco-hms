<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ArtRegimen;
use App\Models\HivCareEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArtController extends Controller
{
    public function startRegimen(Request $request, HivCareEnrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'regimen_code' => 'required|string',
            'start_date' => 'required|date',
            'regimen_line' => 'required|in:first,second,third',
        ]);

        $data['care_enrollment_id'] = $enrollment->id;
        $data['prescribed_by'] = auth()->id();

        ArtRegimen::create($data);

        return redirect()->route('hms.hiv.care.index')
            ->with('success', "ART regimen {$data['regimen_code']} started successfully!");
    }

    public function changeRegimen(Request $request, HivCareEnrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'regimen_code' => 'required|string',
            'start_date' => 'required|date',
            'regimen_line' => 'required|in:first,second,third',
            'reason_for_change' => 'required|in:switch_to_second_line,toxicity,pregnancy,other',
        ]);

        $currentRegimen = $enrollment->artRegimens()->whereNull('end_date')->latest('start_date')->first();

        if ($currentRegimen) {
            $currentRegimen->update([
                'end_date' => $data['start_date'],
                'reason_for_change' => $data['reason_for_change'],
            ]);
        }

        $data['care_enrollment_id'] = $enrollment->id;
        $data['prescribed_by'] = auth()->id();

        ArtRegimen::create($data);

        return redirect()->route('hms.hiv.care.index')
            ->with('success', "ART regimen changed to {$data['regimen_code']} successfully!");
    }
}
