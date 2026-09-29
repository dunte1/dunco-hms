<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\WhoSafetyChecklist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WhoChecklistController extends Controller
{
    public function complete(Request $request, OtSchedule $schedule, string $type): RedirectResponse
    {
        $validTypes = ['sign_in', 'time_out', 'sign_out'];
        if (!in_array($type, $validTypes)) {
            return back()->with('error', 'Invalid checklist type');
        }

        $data = $request->validate([
            'site_marked' => 'nullable|boolean',
            'consent_confirmed' => 'nullable|boolean',
            'anaesthesia_safety_confirmed' => 'nullable|boolean',
            'instruments_counted' => 'nullable|boolean',
            'equipment_checked' => 'nullable|boolean',
            'key_concerns_communicated' => 'nullable|boolean',
            'prophylactic_antibiotics_given' => 'nullable|boolean',
            'essential_imaging_displayed' => 'nullable|boolean',
            'patient_identity_confirmed' => 'nullable|boolean',
            'surgical_site_confirmed' => 'nullable|boolean',
            'allergies_confirmed' => 'nullable|boolean',
            'blood_loss_risk_assessed' => 'nullable|boolean',
            'team_introduced' => 'nullable|boolean',
            'recovery_plan_discussed' => 'nullable|boolean',
        ]);

        $checklist = WhoSafetyChecklist::updateOrCreate(
            ['ot_schedule_id' => $schedule->id, 'checklist_type' => $type],
            array_merge($data, [
                'patient_id' => $schedule->patient_id,
                'completed' => true,
                'completed_by' => auth()->id(),
                'completed_at' => now(),
            ])
        );

        return back()->with('status', ucfirst(str_replace('_', ' ', $type)) . ' checklist completed');
    }
}
