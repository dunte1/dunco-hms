<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ConsentForm;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsentController extends Controller
{
    public function index(): View
    {
        $consents = ConsentForm::with(['patient', 'doctor'])->orderByDesc('created_at')->paginate(20);
        return view('hms.consent.index', compact('consents'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->pluck(function ($p) { return $p->first_name . ' ' . $p->last_name; }, 'id');
        $doctors = Doctor::orderBy('first_name')->pluck(function ($d) { return $d->first_name . ' ' . $d->last_name; }, 'id');
        return view('hms.consent.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'consent_type' => 'required|in:procedure,anesthesia,blood_transfusion,data_sharing,research',
            'procedure_name' => 'nullable|string',
            'description' => 'nullable|string',
            'risks_disclosed' => 'nullable|string',
            'alternatives_disclosed' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $data['status'] = 'pending';
        ConsentForm::create($data);
        return redirect()->route('hms.consent.index')->with('status', 'Consent form created');
    }

    public function show(ConsentForm $consent): View
    {
        $consent->load(['patient', 'doctor']);
        return view('hms.consent.show', compact('consent'));
    }

    public function sign(ConsentForm $consent): RedirectResponse
    {
        $consent->update([
            'status' => 'signed',
            'signed_at' => now(),
            'ip_address' => request()->ip(),
        ]);
        return back()->with('status', 'Consent form signed');
    }

    public function destroy(ConsentForm $consent): RedirectResponse
    {
        $consent->update(['status' => 'revoked']);
        return back()->with('status', 'Consent form revoked');
    }
}
