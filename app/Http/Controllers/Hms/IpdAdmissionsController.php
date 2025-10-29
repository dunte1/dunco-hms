<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Bed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IpdAdmissionsController extends Controller
{
    public function index(Request $request): View
    {
        $query = IpdAdmission::with(['patient', 'doctor', 'bed']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($patientQuery) use ($search) {
                    $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('patient_no', 'like', "%{$search}%");
                })
                ->orWhere('admission_number', 'like', "%{$search}%")
                ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('admission_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('admission_date', '<=', $request->date_to);
        }
        
        $admissions = $query->latest('admission_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => IpdAdmission::count(),
            'active' => IpdAdmission::where('status', 'admitted')->count(),
            'discharged_today' => IpdAdmission::where('status', 'discharged')
                ->whereDate('discharge_date', today())->count(),
            'admitted_today' => IpdAdmission::whereDate('admission_date', today())->count(),
        ];
        
        return view('hms.ipd.index', compact('admissions', 'stats'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $beds = Bed::where('is_available', true)->with('bedType')->get(['id', 'bed_number', 'ward_name', 'bed_type_id']);
        return view('hms.ipd.create', compact('patients', 'doctors', 'beds'));
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
        
        // Generate admission number
        $data['admission_number'] = 'IPD-' . date('Y') . '-' . str_pad(IpdAdmission::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['status'] = 'admitted';
        
        $admission = IpdAdmission::create($data);
        
        // Mark bed as unavailable if assigned
        if ($data['bed_id']) {
            Bed::where('id', $data['bed_id'])->update(['is_available' => false]);
        }
        
        return redirect()->route('hms.ipd.index')->with('success', 'Patient admitted successfully!');
    }
    
    public function show(IpdAdmission $ipd): View
    {
        $ipd->load(['patient', 'doctor', 'bed.bedType']);
        return view('hms.ipd.show', compact('ipd'));
    }
    
    public function edit(IpdAdmission $ipd): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $beds = Bed::where(function($query) use ($ipd) {
            $query->where('is_available', true)
                  ->orWhere('id', $ipd->bed_id);
        })->with('bedType')->get(['id', 'bed_number', 'ward_name', 'bed_type_id']);
        
        return view('hms.ipd.edit', compact('ipd', 'patients', 'doctors', 'beds'));
    }
    
    public function update(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bed_id' => 'nullable|exists:beds,id',
            'admission_date' => 'required|date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'status' => 'required|in:admitted,discharged,transferred',
        ]);
        
        // Handle bed changes
        if ($data['bed_id'] != $ipd->bed_id) {
            // Release old bed
            if ($ipd->bed_id) {
                Bed::where('id', $ipd->bed_id)->update(['is_available' => true]);
            }
            // Occupy new bed
            if ($data['bed_id']) {
                Bed::where('id', $data['bed_id'])->update(['is_available' => false]);
            }
        }
        
        // If discharged, release bed
        if ($data['status'] == 'discharged' && $ipd->bed_id) {
            Bed::where('id', $ipd->bed_id)->update(['is_available' => true]);
        }
        
        $ipd->update($data);
        
        return redirect()->route('hms.ipd.show', $ipd)->with('success', 'Admission updated successfully!');
    }
    
    public function destroy(IpdAdmission $ipd): RedirectResponse
    {
        // Release bed if assigned
        if ($ipd->bed_id) {
            Bed::where('id', $ipd->bed_id)->update(['is_available' => true]);
        }
        
        $ipd->delete();
        
        return redirect()->route('hms.ipd.index')->with('success', 'Admission record deleted successfully!');
    }
}