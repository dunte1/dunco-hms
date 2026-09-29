<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IcuAdmission;
use App\Models\Patient;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IcuAdmissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = IcuAdmission::with(['patient', 'ward', 'bed', 'admittingDoctor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('patient_no', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }

        $admissions = $query->latest('admission_datetime')->paginate(15)->withQueryString();

        $stats = [
            'total' => IcuAdmission::count(),
            'active' => IcuAdmission::where('status', 'active')->count(),
            'hdu' => IcuAdmission::where('status', 'active')->where('unit_type', 'HDU')->count(),
            'icu' => IcuAdmission::where('status', 'active')->where('unit_type', 'ICU')->count(),
        ];

        return view('hms.icu.index', compact('admissions', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'ward_id' => 'required|exists:wards,id',
            'bed_id' => 'nullable|exists:beds,id',
            'unit_type' => 'required|in:ICU,HDU',
            'admission_from' => 'required|in:emergency,ward,theatre,external',
            'admission_datetime' => 'required|date',
            'admission_diagnosis' => 'nullable|string',
            'admitting_doctor_id' => 'required|exists:doctors,id',
        ]);

        $data['status'] = 'active';

        $admission = IcuAdmission::create($data);

        if (!empty($data['bed_id'])) {
            Bed::where('id', $data['bed_id'])->update(['is_available' => false]);
        }

        return redirect()->route('hms.icu.index')->with('success', 'ICU/HDU admission created successfully.');
    }

    public function discharge(Request $request, IcuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'discharge_datetime' => 'required|date',
            'discharge_destination' => 'required|string|max:100',
            'discharge_condition' => 'required|string|max:100',
            'status' => 'required|in:discharged,step_down,deceased',
        ]);

        $admission->update($data);

        if ($admission->bed_id) {
            Bed::where('id', $admission->bed_id)->update(['is_available' => true]);
        }

        return redirect()->route('hms.icu.index')->with('success', 'Patient discharged from ICU/HDU.');
    }
}
