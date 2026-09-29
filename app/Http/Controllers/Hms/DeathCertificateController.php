<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DeathCertificate;
use App\Models\MortuaryRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeathCertificateController extends Controller
{
    public function store(Request $request, MortuaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'cause_of_death_primary' => 'required|string',
            'cause_of_death_secondary' => 'nullable|string',
            'contributing_conditions' => 'nullable|string',
            'issued_by' => 'required|exists:doctors,id',
        ]);

        $data['mortuary_record_id'] = $record->id;
        $data['certificate_number'] = 'DC-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
        $data['issued_at'] = now();

        DeathCertificate::create($data);

        return back()->with('status', 'Death certificate issued');
    }
}
