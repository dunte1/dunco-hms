<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StudentRotation;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRotationController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentRotation::with(['department', 'supervisor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('rotation_number', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $rotations = $query->latest()->paginate(15)->withQueryString();

        $departments = EmployeeDepartment::orderBy('name')->get();
        $stats = [
            'total' => StudentRotation::count(),
            'active' => StudentRotation::where('status', 'active')->count(),
            'completed' => StudentRotation::where('status', 'completed')->count(),
            'scheduled' => StudentRotation::where('status', 'scheduled')->count(),
        ];

        return view('hms.hr.student-rotations.index', compact('rotations', 'departments', 'stats'));
    }

    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $supervisors = User::orderBy('name')->get(['id', 'name']);

        return view('hms.hr.student-rotations.create', compact('departments', 'supervisors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'institution' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'department_id' => 'required|exists:employee_departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'objectives' => 'nullable|string',
        ]);

        $data['student_email'] = $data['email'];
        $data['student_phone'] = $data['phone'] ?? null;
        unset($data['email'], $data['phone']);

        $data['status'] = 'scheduled';

        StudentRotation::create($data);

        return redirect()->route('hms.hr.student-rotations.index')
            ->with('success', 'Student rotation created successfully.');
    }

    public function show(StudentRotation $studentRotation): View
    {
        $studentRotation->load(['department', 'supervisor', 'evaluator']);

        return view('hms.hr.student-rotations.show', compact('studentRotation'));
    }

    public function update(Request $request, StudentRotation $studentRotation): RedirectResponse
    {
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'institution' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'department_id' => 'required|exists:employee_departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'objectives' => 'nullable|string',
            'status' => 'required|in:scheduled,active,completed,cancelled',
        ]);

        $data['student_email'] = $data['email'];
        $data['student_phone'] = $data['phone'] ?? null;
        unset($data['email'], $data['phone']);

        $studentRotation->update($data);

        return redirect()->route('hms.hr.student-rotations.show', $studentRotation)
            ->with('success', 'Student rotation updated successfully.');
    }

    public function evaluate(Request $request, StudentRotation $studentRotation): RedirectResponse
    {
        $data = $request->validate([
            'performance_score' => 'required|integer|min:1|max:100',
            'performance_rating' => 'required|in:excellent,good,satisfactory,needs_improvement,poor',
            'supervisor_comments' => 'nullable|string',
            'student_feedback' => 'nullable|string',
            'evaluation_date' => 'required|date',
        ]);

        $studentRotation->update([
            'performance_score' => $data['performance_score'],
            'performance_rating' => $data['performance_rating'],
            'supervisor_comments' => $data['supervisor_comments'] ?? null,
            'student_feedback' => $data['student_feedback'] ?? null,
            'evaluation_date' => $data['evaluation_date'],
            'evaluated_by' => auth()->id(),
            'status' => 'completed',
        ]);

        return redirect()->route('hms.hr.student-rotations.show', $studentRotation)
            ->with('success', 'Evaluation submitted and rotation marked as completed.');
    }

    public function destroy(StudentRotation $studentRotation): RedirectResponse
    {
        $studentRotation->delete();

        return redirect()->route('hms.hr.student-rotations.index')
            ->with('success', 'Student rotation deleted successfully.');
    }
}
