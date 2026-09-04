<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdmissionsController extends Controller
{
    public function index(Request $request): View
    {
        $query = IpdAdmission::with(['patient', 'doctor', 'bed']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $admissions = $query->latest()->paginate(20);

        return view('hms.admissions.index', compact('admissions'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::orderBy('first_name')->get();
        $beds = Bed::where('is_available', true)->get();

        return view('hms.admissions.create', compact('patients', 'doctors', 'beds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bed_id' => 'nullable|exists:beds,id',
            'admission_date' => 'required|date',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
        ]);

        $data['status'] = 'admitted';

        $admission = IpdAdmission::create($data);

        if (!empty($data['bed_id'])) {
            Bed::where('id', $data['bed_id'])->update(['is_available' => false]);
        }

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission created successfully.');
    }

    public function show(IpdAdmission $admission): View
    {
        $admission->load(['patient', 'doctor', 'bed']);

        return view('hms.admissions.show', compact('admission'));
    }

    public function edit(IpdAdmission $admission): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::orderBy('first_name')->get();
        $beds = Bed::where('is_available', true)
            ->orWhere('id', $admission->bed_id)
            ->get();

        return view('hms.admissions.edit', compact('admission', 'patients', 'doctors', 'beds'));
    }

    public function update(Request $request, IpdAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bed_id' => 'nullable|exists:beds,id',
            'admission_date' => 'required|date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'status' => 'required|in:admitted,discharged,transferred',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
        ]);

        $oldBedId = $admission->bed_id;
        $admission->update($data);

        if ($admission->status === 'discharged' && $oldBedId) {
            Bed::where('id', $oldBedId)->update(['is_available' => true]);
        }

        if (!empty($data['bed_id']) && $data['bed_id'] !== $oldBedId) {
            if ($oldBedId) {
                Bed::where('id', $oldBedId)->update(['is_available' => true]);
            }
            Bed::where('id', $data['bed_id'])->update(['is_available' => false]);
        }

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission updated successfully.');
    }

    public function destroy(IpdAdmission $admission): RedirectResponse
    {
        if ($admission->bed_id && $admission->status === 'admitted') {
            Bed::where('id', $admission->bed_id)->update(['is_available' => true]);
        }

        $admission->delete();

        return redirect()->route('admissions.index')
            ->with('success', 'Admission deleted successfully.');
    }
}
