<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Pregnancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PregnancyController extends Controller
{
    public function index(Request $request): View
    {
        $pregnancies = Pregnancy::with('patient')
            ->latest()
            ->paginate(15);

        return view('hms.maternity.pregnancies.index', compact('pregnancies'));
    }

    public function show(Pregnancy $pregnancy): View
    {
        $pregnancy->load([
            'patient', 'ancVisits.visitedBy', 'labourRecords',
            'deliveries.deliveredBy', 'postnatalVisits.visitedBy',
        ]);

        return view('hms.maternity.pregnancies.show', compact('pregnancy'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'gravida' => 'required|integer|min:1',
            'parity' => 'required|integer|min:0',
            'last_menstrual_date' => 'required|date',
            'estimated_due_date' => 'required|date|after:last_menstrual_date',
            'current_gestational_weeks' => 'required|integer|min:0|max:42',
            'blood_group' => 'required|string|max:10',
            'rh_factor' => 'required|string|max:10',
            'hiv_status' => 'required|in:known_negative,known_positive,unknown',
            'previous_complications' => 'nullable|string',
            'is_high_risk' => 'boolean',
            'high_risk_reason' => 'nullable|required_if:is_high_risk,true|string',
        ]);

        $data['status'] = 'active';

        Pregnancy::create($data);

        return back()->with('success', 'Pregnancy registered successfully!');
    }
}
