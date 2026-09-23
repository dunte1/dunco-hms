<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternshipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Internship::with(['department', 'supervisor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('intern_name', 'like', "%{$search}%")
                    ->orWhere('internship_number', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $internships = $query->latest()->paginate(15)->withQueryString();

        $departments = EmployeeDepartment::orderBy('name')->get();
        $stats = [
            'total' => Internship::count(),
            'active' => Internship::where('status', 'active')->count(),
            'completed' => Internship::where('status', 'completed')->count(),
            'pending' => Internship::where('status', 'pending')->count(),
        ];

        return view('hms.hr.internships.index', compact('internships', 'departments', 'stats'));
    }

    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $supervisors = User::orderBy('name')->get(['id', 'name']);

        return view('hms.hr.internships.create', compact('departments', 'supervisors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'intern_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'institution' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'department_id' => 'required|exists:employee_departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $data['intern_email'] = $data['email'];
        $data['intern_phone'] = $data['phone'] ?? null;
        unset($data['email'], $data['phone']);

        $data['status'] = 'active';

        Internship::create($data);

        return redirect()->route('hms.hr.internships.index')
            ->with('success', 'Internship created successfully.');
    }

    public function show(Internship $internship): View
    {
        $internship->load(['department', 'supervisor']);

        return view('hms.hr.internships.show', compact('internship'));
    }

    public function update(Request $request, Internship $internship): RedirectResponse
    {
        $data = $request->validate([
            'intern_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'institution' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'department_id' => 'required|exists:employee_departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $data['intern_email'] = $data['email'];
        $data['intern_phone'] = $data['phone'] ?? null;
        unset($data['email'], $data['phone']);

        $internship->update($data);

        return redirect()->route('hms.hr.internships.show', $internship)
            ->with('success', 'Internship updated successfully.');
    }

    public function complete(Request $request, Internship $internship): RedirectResponse
    {
        $data = $request->validate([
            'performance_score' => 'required|integer|min:1|max:100',
            'performance_rating' => 'required|in:excellent,good,satisfactory,needs_improvement,poor',
            'supervisor_notes' => 'nullable|string',
            'evaluation' => 'nullable|string',
        ]);

        $internship->update([
            'status' => 'completed',
            'performance_score' => $data['performance_score'],
            'performance_rating' => $data['performance_rating'],
            'supervisor_notes' => $data['supervisor_notes'] ?? null,
            'evaluation' => $data['evaluation'] ?? null,
        ]);

        return redirect()->route('hms.hr.internships.show', $internship)
            ->with('success', 'Internship marked as completed.');
    }

    public function destroy(Internship $internship): RedirectResponse
    {
        $internship->delete();

        return redirect()->route('hms.hr.internships.index')
            ->with('success', 'Internship deleted successfully.');
    }
}
