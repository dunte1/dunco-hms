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

    public function showCategory(DiagnosisCategory $category): View
    {
        $category->load('patientDiagnoses');
        return view('hms.diagnosis.show-category', compact('category'));
    }

    public function editCategory(DiagnosisCategory $category): View
    {
        return view('hms.diagnosis.edit-category', compact('category'));
    }

    public function updateCategory(Request $request, DiagnosisCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:diagnosis_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'code' => 'required|string|unique:diagnosis_categories,code,' . $category->id,
        ]);

        $category->update($data);
        return redirect()->route('hms.diagnosis.categories')->with('status', 'Diagnosis category updated');
    }

    public function destroyCategory(DiagnosisCategory $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('hms.diagnosis.categories')->with('status', 'Diagnosis category deleted');
    }

    public function showDiagnosis(PatientDiagnosis $diagnosis): View
    {
        $diagnosis->load(['patient', 'doctor', 'diagnosisCategory']);
        return view('hms.diagnosis.show-diagnosis', compact('diagnosis'));
    }

    public function editDiagnosis(PatientDiagnosis $diagnosis): View
    {
        $diagnosis->load(['patient', 'doctor', 'diagnosisCategory']);
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $categories = DiagnosisCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.diagnosis.edit-diagnosis', compact('diagnosis', 'patients', 'doctors', 'categories'));
    }

    public function updateDiagnosis(Request $request, PatientDiagnosis $diagnosis): RedirectResponse
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

        $diagnosis->update($data);
        return redirect()->route('hms.diagnosis.patient-diagnoses')->with('status', 'Patient diagnosis updated');
    }

    public function destroyDiagnosis(PatientDiagnosis $diagnosis): RedirectResponse
    {
        $diagnosis->delete();
        return redirect()->route('hms.diagnosis.patient-diagnoses')->with('status', 'Patient diagnosis deleted');
    }
}
