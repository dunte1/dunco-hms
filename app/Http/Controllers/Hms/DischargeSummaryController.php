<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DischargeSummaryController extends Controller
{
    public function index(Request $request): View
    {
        $query = IpdAdmission::with(['patient', 'doctor', 'bed'])
            ->where('status', 'discharged')
            ->whereNotNull('discharge_date');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($patientQuery) use ($search) {
                    $patientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('patient_no', 'like', "%{$search}%");
                })
                ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('discharge_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('discharge_date', '<=', $request->to_date);
        }
        
        // Filter by doctor
        if ($request->filled('doctor')) {
            $query->where('doctor_id', $request->doctor);
        }
        
        $discharges = $query->latest('discharge_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => IpdAdmission::where('status', 'discharged')->count(),
            'today' => IpdAdmission::where('status', 'discharged')
                ->whereDate('discharge_date', today())->count(),
            'this_week' => IpdAdmission::where('status', 'discharged')
                ->whereBetween('discharge_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => IpdAdmission::where('status', 'discharged')
                ->whereMonth('discharge_date', now()->month)
                ->whereYear('discharge_date', now()->year)->count(),
        ];
        
        $doctors = Doctor::orderBy('first_name')->get();
        
        return view('hms.discharge-summary.index', compact('discharges', 'stats', 'doctors'));
    }
    
    public function show(IpdAdmission $discharge): View
    {
        $discharge->load(['patient', 'doctor', 'bed']);
        
        return view('hms.discharge-summary.show', compact('discharge'));
    }
    
    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::orderBy('first_name')->get();
        
        return view('hms.discharge-summary.create', compact('patients', 'doctors'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        // This would typically update an existing IPD admission to discharged status
        $data = $request->validate([
            'ipd_admission_id' => 'required|exists:ipd_admissions,id',
            'discharge_date' => 'required|date',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'nullable|string',
        ]);
        
        $admission = IpdAdmission::findOrFail($data['ipd_admission_id']);
        $admission->update([
            'discharge_date' => $data['discharge_date'],
            'status' => 'discharged',
            'diagnosis' => $data['diagnosis'],
            'treatment_plan' => $data['treatment_plan'],
        ]);
        
        // Release the bed
        if ($admission->bed_id) {
            $admission->bed->update(['is_occupied' => false]);
        }
        
        return redirect()->route('hms.discharge-summary.index')
            ->with('success', 'Patient discharged successfully!');
    }

    public function edit(IpdAdmission $discharge): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::orderBy('first_name')->get();
        return view('hms.discharge-summary.edit', compact('discharge', 'patients', 'doctors'));
    }

    public function update(Request $request, IpdAdmission $discharge): RedirectResponse
    {
        $data = $request->validate([
            'ipd_admission_id' => 'required|exists:ipd_admissions,id',
            'discharge_date' => 'required|date',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'nullable|string',
        ]);

        $admission = IpdAdmission::findOrFail($data['ipd_admission_id']);
        $admission->update([
            'discharge_date' => $data['discharge_date'],
            'status' => 'discharged',
            'diagnosis' => $data['diagnosis'],
            'treatment_plan' => $data['treatment_plan'],
        ]);

        if ($admission->bed_id) {
            $admission->bed->update(['is_occupied' => false]);
        }

        return redirect()->route('hms.discharge-summary.index')->with('success', 'Discharge summary updated!');
    }

    public function destroy(IpdAdmission $discharge): RedirectResponse
    {
        // If patient is discharged, release the bed
        if ($discharge->status === 'discharged' && $discharge->bed_id) {
            $discharge->bed->update(['is_occupied' => false]);
        }

        $discharge->delete();
        return redirect()->route('hms.discharge-summary.index')->with('success', 'Discharge summary deleted!');
    }
}
