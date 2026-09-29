<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PractitionerLicence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenceController extends Controller
{
    public function index(): View
    {
        $licences = PractitionerLicence::with('doctor')->latest()->paginate(15);
        return view('hms.credentialing.licences', compact('licences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'licence_type' => 'required|in:medical,specialist,practice',
            'licence_number' => 'required|string|max:255|unique:practitioner_licences,licence_number',
            'issuing_body' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'nullable|in:active,expired,suspended,revoked',
        ]);

        $data['status'] = $data['status'] ?? 'active';

        PractitionerLicence::create($data);

        return redirect()->route('hms.credentialing.licences.index')
            ->with('success', 'Licence recorded successfully!');
    }

    public function checkExpiry(): View
    {
        $expiring = PractitionerLicence::with('doctor')
            ->where('status', 'active')
            ->whereBetween('expiry_date', [now(), now()->addDays(90)])
            ->orderBy('expiry_date')
            ->get();

        $expired = PractitionerLicence::with('doctor')
            ->where('status', 'active')
            ->where('expiry_date', '<', now())
            ->get();

        return view('hms.credentialing.licence-expiry', compact('expiring', 'expired'));
    }
}
