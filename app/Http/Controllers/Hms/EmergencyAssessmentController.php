<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAdmission;
use Illuminate\View\View;

/**
 * Emergency department assessment workspace.
 * Links trauma / resuscitation / disposition forms to an emergency admission.
 */
class EmergencyAssessmentController extends Controller
{
    public function show(EmergencyAdmission $emergency): View
    {
        $emergency->load(['patient', 'ambulance']);

        return view('hms.ambulance.emergency-assessment', [
            'admission' => $emergency,
        ]);
    }
}
