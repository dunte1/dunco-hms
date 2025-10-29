<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Prescription::with(['patient', 'doctor', 'items.medicine']);
        
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
        
        // Filter by doctor
        if ($request->filled('doctor')) {
            $query->where('doctor_id', $request->doctor);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('prescription_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('prescription_date', '<=', $request->to_date);
        }
        
        $prescriptions = $query->latest('prescription_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Prescription::count(),
            'today' => Prescription::whereDate('prescription_date', today())->count(),
            'this_week' => Prescription::whereBetween('prescription_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Prescription::whereMonth('prescription_date', now()->month)
                ->whereYear('prescription_date', now()->year)->count(),
        ];
        
        $doctors = Doctor::orderBy('first_name')->get();
        
        return view('hms.pharmacy.prescriptions.index', compact('prescriptions', 'stats', 'doctors'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength']);
        return view('hms.pharmacy.prescriptions.create', compact('patients', 'doctors', 'medicines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'prescription_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.duration_days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'prescription_date' => $data['prescription_date'],
            'symptoms' => $data['symptoms'],
            'diagnosis' => $data['diagnosis'],
            'notes' => $data['notes'],
        ]);

        // Create prescription items
        foreach ($data['medicines'] as $medicine) {
            $prescription->items()->create([
                'medicine_id' => $medicine['medicine_id'],
                'dosage' => $medicine['dosage'],
                'frequency' => $medicine['frequency'],
                'quantity' => $medicine['quantity'],
                'duration_days' => $medicine['duration_days'],
                'instructions' => $medicine['instructions'],
            ]);
        }

        return redirect()->route('hms.pharmacy.prescriptions.index')->with('status', 'Prescription created');
    }

    public function show(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        return view('hms.pharmacy.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription): View
    {
        $prescription->load('items');
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength']);
        return view('hms.pharmacy.prescriptions.edit', compact('prescription', 'patients', 'doctors', 'medicines'));
    }

    public function update(Request $request, Prescription $prescription): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'prescription_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.duration_days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $prescription->update([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'prescription_date' => $data['prescription_date'],
            'symptoms' => $data['symptoms'],
            'diagnosis' => $data['diagnosis'],
            'notes' => $data['notes'],
        ]);

        // Delete existing items and recreate
        $prescription->items()->delete();
        
        foreach ($data['medicines'] as $medicine) {
            $prescription->items()->create([
                'medicine_id' => $medicine['medicine_id'],
                'dosage' => $medicine['dosage'],
                'frequency' => $medicine['frequency'],
                'quantity' => $medicine['quantity'],
                'duration_days' => $medicine['duration_days'],
                'instructions' => $medicine['instructions'],
            ]);
        }

        return redirect()->route('hms.pharmacy.prescriptions.index')->with('status', 'Prescription updated successfully');
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        $prescription->delete();
        return redirect()->route('hms.pharmacy.prescriptions.index')->with('status', 'Prescription deleted successfully');
    }
}
