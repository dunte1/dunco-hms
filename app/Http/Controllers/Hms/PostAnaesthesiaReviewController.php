<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaRecord;
use App\Models\PostAnaesthesiaReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostAnaesthesiaReviewController extends Controller
{
    public function store(Request $request, AnaesthesiaRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'review_time' => 'required|date',
            'consciousness_level' => 'nullable|integer|min:3|max:15',
            'airway_patent' => 'required|boolean',
            'breathing_spontaneous' => 'required|boolean',
            'heart_rate' => 'nullable|integer|min:0',
            'blood_pressure_sys' => 'nullable|integer|min:0',
            'blood_pressure_dia' => 'nullable|integer|min:0',
            'spo2' => 'nullable|numeric|min:0|max:100',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pain_score' => 'nullable|integer|min:0|max:10',
            'nausea_vomiting' => 'nullable|boolean',
            'aldrete_score' => 'nullable|integer|min:0|max:10',
            'fit_for_discharge' => 'nullable|boolean',
            'reviewer_id' => 'required|exists:doctors,id',
            'notes' => 'nullable|string',
        ]);

        $data['anaesthesia_record_id'] = $record->id;

        PostAnaesthesiaReview::create($data);

        return redirect()->route('hms.ot.show', $record->ot_schedule_id)
            ->with('status', 'Post-anaesthesia review recorded successfully');
    }
}
