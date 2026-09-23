<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(Request $request): View
    {
        $query = Referral::with(['patient', 'referringDoctor', 'receivingDoctor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('referral_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('referral_type')) {
            $query->where('referral_type', $request->referral_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $referrals = $query->latest('referred_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => Referral::count(),
            'in_referrals' => Referral::where('referral_type', 'in')->count(),
            'out_referrals' => Referral::where('referral_type', 'out')->count(),
            'pending' => Referral::where('status', 'pending')->count(),
            'today' => Referral::whereDate('referred_at', today())->count(),
        ];

        return view('hms.referrals.index', compact('referrals', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $opdVisits = OpdVisit::where('status', '!=', 'discharged')->with('patient')->latest()->get();
        $ipdAdmissions = IpdAdmission::where('status', 'admitted')->with('patient')->latest()->get();
        return view('hms.referrals.create', compact('patients', 'doctors', 'opdVisits', 'ipdAdmissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'referral_type' => 'required|in:in,out',
            'referring_facility' => 'nullable|string|max:200',
            'receiving_facility' => 'nullable|string|max:200',
            'referring_doctor_id' => 'nullable|exists:doctors,id',
            'receiving_doctor_id' => 'nullable|exists:doctors,id',
            'reason' => 'nullable|string',
            'clinical_summary' => 'nullable|string',
            'investigations_done' => 'nullable|string',
            'urgency' => 'required|in:emergency,urgent,routine',
        ]);

        $data['created_by'] = auth()->id();
        $referral = Referral::create($data);

        return redirect()->route('hms.referrals.index')->with('success', 'Referral created successfully!');
    }

    public function show(Referral $referral): View
    {
        $referral->load(['patient', 'opdVisit', 'ipdAdmission', 'referringDoctor', 'receivingDoctor', 'creator']);
        return view('hms.referrals.show', compact('referral'));
    }

    public function edit(Referral $referral): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.referrals.edit', compact('referral', 'patients', 'doctors'));
    }

    public function update(Request $request, Referral $referral): RedirectResponse
    {
        $data = $request->validate([
            'referring_facility' => 'nullable|string|max:200',
            'receiving_facility' => 'nullable|string|max:200',
            'referring_doctor_id' => 'nullable|exists:doctors,id',
            'receiving_doctor_id' => 'nullable|exists:doctors,id',
            'reason' => 'nullable|string',
            'clinical_summary' => 'nullable|string',
            'investigations_done' => 'nullable|string',
            'urgency' => 'required|in:emergency,urgent,routine',
            'status' => 'required|in:pending,accepted,completed,rejected',
            'outcome' => 'nullable|string',
        ]);

        if ($data['status'] === 'accepted' && !$referral->accepted_at) {
            $data['accepted_at'] = now();
        }
        if ($data['status'] === 'completed' && !$referral->completed_at) {
            $data['completed_at'] = now();
        }

        $referral->update($data);
        return redirect()->route('hms.referrals.show', $referral)->with('success', 'Referral updated successfully!');
    }

    public function destroy(Referral $referral): RedirectResponse
    {
        $referral->delete();
        return redirect()->route('hms.referrals.index')->with('success', 'Referral deleted!');
    }

    public function accept(Referral $referral): RedirectResponse
    {
        $referral->update(['status' => 'accepted', 'accepted_at' => now()]);
        return back()->with('success', 'Referral accepted.');
    }

    public function complete(Referral $referral): RedirectResponse
    {
        $referral->update(['status' => 'completed', 'completed_at' => now()]);
        return back()->with('success', 'Referral completed.');
    }

    public function reject(Referral $referral): RedirectResponse
    {
        $referral->update(['status' => 'rejected']);
        return back()->with('success', 'Referral rejected.');
    }
}
