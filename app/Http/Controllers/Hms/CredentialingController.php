<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\PractitionerQualification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CredentialingController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::with(['qualifications', 'practitionerLicences'])->get();

        $stats = [
            'total_doctors' => Doctor::count(),
            'verified_qualifications' => PractitionerQualification::where('verified', true)->count(),
            'unverified_qualifications' => PractitionerQualification::where('verified', false)->count(),
            'expiring_licences' => \App\Models\PractitionerLicence::where('status', 'active')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count(),
        ];

        return view('hms.credentialing.index', compact('doctors', 'stats'));
    }

    public function qualifications(): View
    {
        $qualifications = PractitionerQualification::with('doctor', 'verifier')->latest()->paginate(15);
        return view('hms.credentialing.qualifications', compact('qualifications'));
    }

    public function addQualification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'qualification_name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'year_obtained' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'certificate_number' => 'nullable|string|max:255',
        ]);

        PractitionerQualification::create($data);

        return redirect()->route('hms.credentialing.qualifications')
            ->with('success', 'Qualification recorded successfully!');
    }
}
