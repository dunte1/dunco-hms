<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use App\Models\TrainingEnrollment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingProgramsController extends Controller
{
    /**
     * Display a listing of training programs.
     */
    public function index(Request $request): View
    {
        $query = TrainingProgram::query();
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $trainingPrograms = $query->latest()->paginate(15);
        
        return view('hms.hr.training-programs.index', compact('trainingPrograms'));
    }

    /**
     * Show the form for creating a new training program.
     */
    public function create(): View
    {
        return view('hms.hr.training-programs.create');
    }

    /**
     * Store a newly created training program.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_hours' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'instructor' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'certificate_available' => 'boolean',
        ]);

        TrainingProgram::create($validated);

        return redirect()->route('hms.hr.training-programs.index')
            ->with('success', 'Training program created successfully.');
    }

    /**
     * Display the specified training program.
     */
    public function show(TrainingProgram $trainingProgram): View
    {
        $trainingProgram->load('enrollments.employee');
        $availableEmployees = Employee::where('status', 'active')
            ->whereNotIn('id', $trainingProgram->enrollments->pluck('employee_id'))
            ->orderBy('first_name')
            ->get();
        
        return view('hms.hr.training-programs.show', compact('trainingProgram', 'availableEmployees'));
    }

    /**
     * Show the form for editing the specified training program.
     */
    public function edit(TrainingProgram $trainingProgram): View
    {
        return view('hms.hr.training-programs.edit', compact('trainingProgram'));
    }

    /**
     * Update the specified training program.
     */
    public function update(Request $request, TrainingProgram $trainingProgram): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_hours' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'instructor' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'certificate_available' => 'boolean',
        ]);

        $trainingProgram->update($validated);

        return redirect()->route('hms.hr.training-programs.index')
            ->with('success', 'Training program updated successfully.');
    }

    /**
     * Remove the specified training program.
     */
    public function destroy(TrainingProgram $trainingProgram): RedirectResponse
    {
        $trainingProgram->delete();

        return redirect()->route('hms.hr.training-programs.index')
            ->with('success', 'Training program deleted successfully.');
    }

    /**
     * Enroll employees in a training program.
     */
    public function enroll(Request $request, TrainingProgram $trainingProgram): RedirectResponse
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        foreach ($request->employee_ids as $employeeId) {
            TrainingEnrollment::firstOrCreate([
                'training_program_id' => $trainingProgram->id,
                'employee_id' => $employeeId,
            ], [
                'status' => 'registered',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Employees enrolled successfully.');
    }

    /**
     * Display enrollments for a training program.
     */
    public function enrollments(TrainingProgram $trainingProgram): View
    {
        $enrollments = $trainingProgram->enrollments()->with('employee')->latest()->paginate(15);
        
        return view('hms.hr.training-programs.enrollments', compact('trainingProgram', 'enrollments'));
    }

    /**
     * Mark training enrollment as complete.
     */
    public function markComplete(TrainingEnrollment $enrollment, Request $request): RedirectResponse
    {
        $request->validate([
            'attendance_hours' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $enrollment->update([
            'status' => 'completed',
            'attendance_hours' => $request->attendance_hours ?? $enrollment->trainingProgram->duration_hours,
            'notes' => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Training marked as completed.');
    }

    /**
     * Issue certificate for training completion.
     */
    public function issueCertificate(TrainingEnrollment $enrollment)
    {
        if ($enrollment->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Training must be completed before issuing certificate.');
        }

        if (!$enrollment->trainingProgram->certificate_available) {
            return redirect()->back()
                ->with('error', 'Certificates are not available for this training program.');
        }

        $data = [
            'enrollment' => $enrollment->load('employee', 'trainingProgram'),
            'employee' => $enrollment->employee,
            'program' => $enrollment->trainingProgram,
            'completion_date' => $enrollment->updated_at,
        ];

        $pdf = Pdf::loadView('hms.hr.training-programs.certificate', $data);
        $pdf->setPaper('a4', 'landscape');
        
        $certificatePath = 'certificates/training_' . $enrollment->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($certificatePath, $pdf->output());
        
        $enrollment->update([
            'certificate_issued' => true,
            'certificate_path' => $certificatePath,
        ]);

        return $pdf->download('Training_Certificate_' . $enrollment->employee->employee_id . '.pdf');
    }
}
