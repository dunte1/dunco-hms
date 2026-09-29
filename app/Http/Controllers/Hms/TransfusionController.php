<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\Transfusion;
use App\Models\TransfusionReaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransfusionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'blood_issue_id' => 'required|exists:blood_issues,id',
            'patient_id' => 'required|exists:patients,id',
            'blood_unit_id' => 'required|exists:blood_units,id',
        ]);

        $data['started_at'] = now();
        $data['performed_by'] = Auth::id();
        $data['status'] = 'in_progress';

        Transfusion::create($data);

        BloodUnit::where('id', $data['blood_unit_id'])->update(['status' => 'issued']);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Transfusion started');
    }

    public function complete(Request $request, Transfusion $transfusion): RedirectResponse
    {
        if ($transfusion->status !== 'in_progress') {
            return back()->withErrors(['status' => 'Transfusion is not in progress']);
        }

        $data = $request->validate([
            'volume_transfused_ml' => 'required|integer|min:1',
        ]);

        $endedAt = now();
        $durationMinutes = (int) $transfusion->started_at->diffInMinutes($endedAt);

        $transfusion->update([
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
            'volume_transfused_ml' => $data['volume_transfused_ml'],
            'status' => 'completed',
        ]);

        BloodUnit::where('id', $transfusion->blood_unit_id)->update(['status' => 'issued']);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Transfusion completed');
    }

    public function recordReaction(Request $request, Transfusion $transfusion): RedirectResponse
    {
        $data = $request->validate([
            'reaction_type' => 'required|in:febrile,allergic,hemolytic,trx,TRALI,TACO,other',
            'severity' => 'required|in:mild,moderate,severe,life_threatening',
            'symptoms' => 'required|string',
            'onset_time' => 'required|date',
            'treatment_given' => 'nullable|string',
        ]);

        $data['transfusion_id'] = $transfusion->id;
        $data['patient_id'] = $transfusion->patient_id;
        $data['reported_by'] = Auth::id();
        $data['reported_at'] = now();

        if ($data['severity'] === 'severe' || $data['severity'] === 'life_threatening') {
            $transfusion->update(['status' => 'stopped', 'stop_reason' => 'Reaction: ' . $data['reaction_type']]);
        }

        TransfusionReaction::create($data);

        return redirect()->route('hms.bloodbank.index')->with('status', 'Transfusion reaction reported');
    }
}
