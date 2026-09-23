<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\MortuaryRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'mother_patient_id' => 'nullable|exists:patients,id',
            'baby_patient_id' => 'nullable|exists:patients,id',
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
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'complications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['report_number'] = 'BR-' . date('Y') . '-' . str_pad(BirthReport::count() + 1, 6, '0', STR_PAD_LEFT);

        // Auto-register newborn as patient if not linked
        if (empty($data['baby_patient_id'])) {
            $baby = Patient::create([
                'first_name' => $data['baby_name'],
                'last_name' => '',
                'date_of_birth' => $data['birth_date'],
                'gender' => $data['gender'],
                'phone' => '',
            ]);
            $data['baby_patient_id'] = $baby->id;
        }

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

    public function showBirthReport(BirthReport $report): View
    {
        $report->load(['attendingDoctor', 'attendingNurse', 'motherPatient', 'babyPatient', 'ipdAdmission']);
        return view('hms.reports.show-birth', compact('report'));
    }

    public function editBirthReport(BirthReport $report): View
    {
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.reports.edit-birth', compact('report', 'doctors', 'nurses'));
    }

    public function updateBirthReport(Request $request, BirthReport $report): RedirectResponse
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

        $report->update($data);
        return redirect()->route('hms.reports.birth')->with('status', 'Birth report updated');
    }

    public function destroyBirthReport(BirthReport $report): RedirectResponse
    {
        $report->delete();
        return redirect()->route('hms.reports.birth')->with('status', 'Birth report deleted');
    }

    public function showDeathReport(DeathReport $report): View
    {
        $report->load(['patient', 'attendingDoctor', 'attendingNurse', 'mortuaryRecord']);
        return view('hms.reports.show-death', compact('report'));
    }

    public function editDeathReport(DeathReport $report): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $nurses = Nurse::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.reports.edit-death', compact('report', 'patients', 'doctors', 'nurses'));
    }

    public function updateDeathReport(Request $request, DeathReport $report): RedirectResponse
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

        $report->update($data);
        return redirect()->route('hms.reports.death')->with('status', 'Death report updated');
    }

    public function destroyDeathReport(DeathReport $report): RedirectResponse
    {
        $report->delete();
        return redirect()->route('hms.reports.death')->with('status', 'Death report deleted');
    }

    public function birthCertificate(BirthReport $report)
    {
        $report->load('patient');
        $pdf = PDF::loadView('hms.birth-death.birth-certificate', compact('report'));
        return $pdf->download("Birth-Certificate-{$report->id}.pdf");
    }

    public function deathCertificate(DeathReport $report)
    {
        $report->load('patient');
        $pdf = PDF::loadView('hms.birth-death.death-certificate', compact('report'));
        return $pdf->download("Death-Certificate-{$report->id}.pdf");
    }

    public function transferToMortuary(DeathReport $report)
    {
        if ($report->mortuaryRecord) {
            return back()->with('error', 'This death report already has a mortuary record.');
        }

        $mortuary = MortuaryRecord::create([
            'death_report_id' => $report->id,
            'body_id' => 'MORT-' . date('Y') . '-' . str_pad(MortuaryRecord::count() + 1, 6, '0', STR_PAD_LEFT),
            'received_at' => now(),
            'received_by' => auth()->id(),
            'cause_of_death' => $report->cause_of_death,
            'family_contact_name' => $report->deceased_name,
            'status' => 'stored',
        ]);

        return redirect()->route('hms.mortuary.show', $mortuary)->with('success', 'Body transferred to mortuary. Please update storage details.');
    }
}
