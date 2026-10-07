<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\View\View;

class PatientReceiptController extends Controller
{
    /**
     * Printable registration receipt for reception handoff to triage/clinical.
     */
    public function show(Patient $patient): View
    {
        $this->authorize('view', $patient);

        $opd = \App\Models\OpdVisit::where('patient_id', $patient->id)
            ->latest('id')
            ->first();

        $queue = \App\Models\QueueManagement::where('patient_id', $patient->id)
            ->latest('id')
            ->first();

        $hospital = \App\Models\SystemSetting::get('hospital_name', config('app.name', 'Dunco HMS'));
        $address = \App\Models\SystemSetting::get('hospital_address', '');
        $phone = \App\Models\SystemSetting::get('hospital_phone', '');

        return view('hms.patients.receipt', compact(
            'patient', 'opd', 'queue', 'hospital', 'address', 'phone'
        ));
    }
}
