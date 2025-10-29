<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BirthDeathReportsController extends Controller
{
    public function birthReports(): View
    {
        $birthReports = BirthReport::with(['attendingDoctor', 'attendingNurse'])
            ->latest('birth_date')
            ->paginate(10);
        return view('hms.reports.birth', compact('birthReports'));
    }

    public function deathReports(): View
    {
        $deathReports = DeathReport::with(['patient', 'attendingDoctor', 'attendingNurse'])
            ->latest('death_date')
            ->paginate(10);
        return view('hms.reports.death', compact('deathReports'));
    }

    public function createBirthReport(): View
    {
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.reports.create-birth', compact('doctors', 'nurses'));
    }

    public function storeBirthReport(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'baby_name' => 'required|string',
            'mother_name' => 'required|string',
            'father_name' => 'required|string',
            'mother_phone' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'birth_date' => 'required|date',
            'birth_time' => 'required',
            'gender' => 'required|in:male,female',
            'birth_weight' => 'required|numeric|min:0',
            'birth_length' => 'required|numeric|min:0',
            'delivery_type' => 'required|in:normal,cesarean,assisted',
            'attending_doctor_id' => 'required|exists:doctors,id',
            'attending_nurse_id' => 'nullable|exists:nurses,id',
            'complications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['report_number'] = 'BR-' . date('Y') . '-' . str_pad(BirthReport::count() + 1, 6, '0', STR_PAD_LEFT);

        BirthReport::create($data);
        return redirect()->route('hms.reports.birth')->with('status', 'Birth report created');
    }

    public function createDeathReport(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.reports.create-death', compact('patients', 'doctors', 'nurses'));
    }

    public function storeDeathReport(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'deceased_name' => 'required|string',
            'deceased_phone' => 'nullable|string',
            'death_date' => 'required|date',
            'death_time' => 'required',
            'age_at_death' => 'required|integer|min:0',
            'gender' => 'required|in:male,female,other',
            'cause_of_death' => 'required|string',
            'place_of_death' => 'required|string',
            'attending_doctor_id' => 'required|exists:doctors,id',
            'attending_nurse_id' => 'nullable|exists:nurses,id',
            'circumstances' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['report_number'] = 'DR-' . date('Y') . '-' . str_pad(DeathReport::count() + 1, 6, '0', STR_PAD_LEFT);

        DeathReport::create($data);
        return redirect()->route('hms.reports.death')->with('status', 'Death report created');
    }
}
