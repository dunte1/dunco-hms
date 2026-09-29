<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MedicalCertificate;
use App\Models\NumberSequence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalCertificateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'certificate_type' => 'required|in:fitness,fitness_to_drive,insurance,travel,school',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'valid_until' => 'nullable|date|after:now',
        ]);

        $data['certificate_number'] = NumberSequence::next('medical_certificate');
        $data['issued_at'] = now();

        MedicalCertificate::create($data);

        return back()->with('success', 'Medical certificate issued (' . $data['certificate_number'] . ').');
    }
}
