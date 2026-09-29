<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Newborn;
use App\Models\PhototherapySession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PhototherapyController extends Controller
{
    public function store(Request $request, Newborn $newborn): RedirectResponse
    {
        $data = $request->validate([
            'nicu_admission_id' => 'nullable|exists:nicu_admissions,id',
            'start_time' => 'required|date',
            'light_type' => 'required|in:LED,fiber_optic',
            'bilirubin_before' => 'nullable|numeric|min:0',
            'eye_protection' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $data['newborn_id'] = $newborn->id;

        if (!isset($data['eye_protection'])) {
            $data['eye_protection'] = true;
        }

        PhototherapySession::create($data);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'Phototherapy session started.');
    }

    public function stop(Request $request, PhototherapySession $session): RedirectResponse
    {
        $data = $request->validate([
            'end_time' => 'required|date',
            'bilirubin_after' => 'nullable|numeric|min:0',
        ]);

        $endTime = \Carbon\Carbon::parse($data['end_time']);
        $startTime = $session->start_time;

        $data['duration_hours'] = $startTime->diffInHours($endTime, true);

        $session->update($data);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'Phototherapy session ended.');
    }
}
