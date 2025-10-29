<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisCategory;
use App\Models\PatientDiagnosis;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    public function categories(): View
    {
        $categories = DiagnosisCategory::withCount('patientDiagnoses')->orderBy('name')->paginate(10);
        return view('hms.diagnosis.categories', compact('categories'));
    }

    public function createCategory(): View
    {
        return view('hms.diagnosis.create-category');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:diagnosis_categories,name',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:diagnosis_categories,code',
        ]);

        DiagnosisCategory::create($data);
        return redirect()->route('hms.diagnosis.categories')->with('status', 'Diagnosis category created');
    }

    public function patientDiagnoses(Request $request): View
    {
        $query = PatientDiagnosis::with(['patient', 'doctor', 'diagnosisCategory']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhere('symptoms', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('diagnosis_category_id', $request->category);
        }
        
        $diagnoses = $query->latest('diagnosis_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => PatientDiagnosis::count(),
            'active' => PatientDiagnosis::where('status', 'active')->count(),
            'resolved' => PatientDiagnosis::where('status', 'resolved')->count(),
            'chronic' => PatientDiagnosis::where('status', 'chronic')->count(),
        ];
        
        $categories = DiagnosisCategory::orderBy('name')->get();
        
        return view('hms.diagnosis.patient-diagnoses', compact('diagnoses', 'stats', 'categories'));
    }

    public function createDiagnosis(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $categories = DiagnosisCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.diagnosis.create-diagnosis', compact('patients', 'doctors', 'categories'));
    }

    public function storeDiagnosis(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'diagnosis_category_id' => 'required|exists:diagnosis_categories,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'diagnosis_date' => 'required|date',
            'status' => 'required|in:active,resolved,chronic',
        ]);

        PatientDiagnosis::create($data);
        return redirect()->route('hms.diagnosis.patient-diagnoses')->with('status', 'Patient diagnosis recorded');
    }
}
