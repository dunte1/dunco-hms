<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EPrescriptionTemplate;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EPrescriptionController extends Controller
{
    /**
     * Display all e-prescriptions
     */
    public function index(): View
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->latest('prescription_date')
            ->paginate(20);

        return view('hms.prescriptions.e-prescription.index', compact('prescriptions'));
    }

    /**
     * Display E-Prescription templates
     */
    public function templates(): View
    {
        $templates = EPrescriptionTemplate::where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->get();
        
        return view('hms.prescriptions.e-prescription.templates', compact('templates'));
    }

    /**
     * Create a new E-Prescription from template
     */
    public function create(Request $request): View
    {
        $templateId = $request->get('template_id');
        $template = $templateId ? EPrescriptionTemplate::findOrFail($templateId) : null;
        
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength']);
        $templates = EPrescriptionTemplate::where('is_active', true)->get();
        
        return view('hms.prescriptions.e-prescription.create', compact('template', 'patients', 'doctors', 'medicines', 'templates'));
    }

    /**
     * Store E-Prescription with digital signature
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'prescription_date' => 'required|date',
            'template_id' => 'nullable|exists:e_prescription_templates,id',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'digital_signature' => 'required|string', // Base64 encoded signature
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.duration_days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'prescription_date' => $validated['prescription_date'],
            'symptoms' => $validated['symptoms'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'digital_signature' => $validated['digital_signature'],
            'signed_at' => now(),
            'signed_by' => auth()->id(),
            'template_id' => $validated['template_id'] ?? null,
            'metadata' => [
                'created_via' => 'e-prescription',
                'template_used' => $validated['template_id'] ?? null,
            ],
        ]);

        // Create prescription items
        foreach ($validated['medicines'] as $medicine) {
            $prescription->items()->create([
                'medicine_id' => $medicine['medicine_id'],
                'dosage' => $medicine['dosage'],
                'frequency' => $medicine['frequency'],
                'quantity' => $medicine['quantity'],
                'duration_days' => $medicine['duration_days'],
                'instructions' => $medicine['instructions'] ?? null,
            ]);
        }

        // Increment template usage
        if ($validated['template_id']) {
            $template = EPrescriptionTemplate::find($validated['template_id']);
            if ($template) {
                $template->incrementUsage();
            }
        }

        return redirect()
            ->route('hms.prescriptions.e-prescription.show', $prescription)
            ->with('success', 'E-Prescription created and digitally signed successfully.');
    }

    /**
     * Display E-Prescription
     */
    public function show(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.medicine', 'signedBy']);
        $template = $prescription->template_id 
            ? EPrescriptionTemplate::find($prescription->template_id) 
            : null;
        
        return view('hms.prescriptions.e-prescription.show', compact('prescription', 'template'));
    }

    /**
     * Generate PDF of E-Prescription
     */
    public function pdf(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        $template = $prescription->template_id 
            ? EPrescriptionTemplate::find($prescription->template_id) 
            : null;
        
        // This would use DomPDF or similar
        // For now, return view
        return view('hms.prescriptions.e-prescription.pdf', compact('prescription', 'template'));
    }

    /**
     * Edit E-Prescription
     */
    public function edit(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength']);
        $templates = EPrescriptionTemplate::where('is_active', true)->get();
        return view('hms.prescriptions.e-prescription.edit', compact('prescription', 'patients', 'doctors', 'medicines', 'templates'));
    }

    /**
     * Update E-Prescription
     */
    public function update(Request $request, Prescription $prescription): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'prescription_date' => 'required|date',
            'template_id' => 'nullable|exists:e_prescription_templates,id',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'digital_signature' => 'required|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.frequency' => 'required|string',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.duration_days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $prescription->update([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'prescription_date' => $validated['prescription_date'],
            'symptoms' => $validated['symptoms'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'digital_signature' => $validated['digital_signature'],
            'signed_at' => now(),
            'signed_by' => auth()->id(),
            'template_id' => $validated['template_id'] ?? null,
        ]);

        // Sync prescription items
        $prescription->items()->delete();
        foreach ($validated['medicines'] as $medicine) {
            $prescription->items()->create([
                'medicine_id' => $medicine['medicine_id'],
                'dosage' => $medicine['dosage'],
                'frequency' => $medicine['frequency'],
                'quantity' => $medicine['quantity'],
                'duration_days' => $medicine['duration_days'],
                'instructions' => $medicine['instructions'] ?? null,
            ]);
        }

        return redirect()
            ->route('hms.prescriptions.e-prescription.show', $prescription)
            ->with('success', 'E-Prescription updated successfully.');
    }

    /**
     * Delete E-Prescription
     */
    public function destroy(Prescription $prescription): RedirectResponse
    {
        $prescription->items()->delete();
        $prescription->delete();
        return redirect()
            ->route('hms.prescriptions.e-prescription.index')
            ->with('success', 'E-Prescription deleted successfully.');
    }

    /**
     * Manage templates
     */
    public function manageTemplates(): View
    {
        $templates = EPrescriptionTemplate::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('hms.prescriptions.e-prescription.manage-templates', compact('templates'));
    }

    /**
     * Create template form
     */
    public function createTemplate(): View
    {
        return view('hms.prescriptions.e-prescription.create-template');
    }

    /**
     * Store template
     */
    public function storeTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'header_text' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'template_structure' => 'nullable|array',
            'default_fields' => 'nullable|array',
        ]);

        $template = EPrescriptionTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'header_text' => $validated['header_text'] ?? null,
            'footer_text' => $validated['footer_text'] ?? null,
            'template_structure' => $validated['template_structure'] ?? [],
            'default_fields' => $validated['default_fields'] ?? [],
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('hms.prescriptions.e-prescription.manage-templates')
            ->with('success', 'Template created successfully.');
    }
}
