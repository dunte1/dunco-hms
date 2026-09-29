<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaComplication;
use App\Models\AnaesthesiaRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnaesthesiaComplicationController extends Controller
{
    public function store(Request $request, AnaesthesiaRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'complication_type' => 'required|in:difficult_intubation,bronchospasm,laryngospasm,cardiac_arrest,hypotension,hypoxia,malignant_hyperthermia,awareness,nausea_vomiting,other',
            'severity' => 'required|in:mild,moderate,severe,life_threatening',
            'description' => 'required|string',
            'treatment' => 'required|string',
            'outcome' => 'nullable|string',
            'occurred_at' => 'required|date',
        ]);

        $data['anaesthesia_record_id'] = $record->id;
        $data['reported_by'] = auth()->id();

        AnaesthesiaComplication::create($data);

        return redirect()->route('hms.ot.show', $record->ot_schedule_id)
            ->with('status', 'Anaesthesia complication recorded successfully');
    }
}
