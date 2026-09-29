<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAdmission;
use App\Models\TraumaAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TraumaController extends Controller
{
    public function store(Request $request, EmergencyAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'mechanism_of_injury' => 'required|string',
            'injury_type' => 'required|in:blunt,penetrating,burn,misc',
            'head_face_neck' => 'nullable|string',
            'chest' => 'nullable|string',
            'abdomen' => 'nullable|string',
            'pelvis' => 'nullable|string',
            'extremities' => 'nullable|string',
            'spinal' => 'nullable|string',
            'gcs_total' => 'required|integer|min:3|max:15',
            'pupils_left' => 'nullable|string|max:20',
            'pupils_right' => 'nullable|string|max:20',
            'vital_signs_snapshot' => 'nullable|array',
            'trauma_score' => 'required|integer|min:0|max:24',
        ]);

        $data['emergency_admission_id'] = $admission->id;
        $data['patient_id'] = $admission->patient_id;

        TraumaAssessment::create($data);

        return back()->with('success', 'Trauma assessment recorded successfully!');
    }
}
