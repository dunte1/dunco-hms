<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PerformanceAppraisal;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PerformanceAppraisalsController extends Controller
{
    /**
     * Display a listing of performance appraisals.
     */
    public function index(Request $request): View
    {
        $query = PerformanceAppraisal::with(['employee', 'appraiser']);
        
        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by employee name
        if ($request->filled('search')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }
        
        $appraisals = $query->orderBy('appraisal_date', 'desc')->paginate(15);
        $employees = Employee::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        
        return view('hms.hr.appraisals.index', compact('appraisals', 'employees'));
    }

    /**
     * Show the form for creating a new appraisal.
     */
    public function create(): View
    {
        $employees = Employee::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'position']);
        return view('hms.hr.appraisals.create', compact('employees'));
    }

    /**
     * Store a newly created appraisal.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string|max:255',
            'appraisal_date' => 'required|date',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'overall_rating' => 'nullable|in:excellent,good,satisfactory,needs_improvement,poor',
            'skill_ratings' => 'nullable|string',
            'behavioral_ratings' => 'nullable|string',
            'kpi_ratings' => 'nullable|string',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals_for_next_period' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'appraiser_comments' => 'nullable|string',
            'hr_comments' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,reviewed,approved,archived',
            'promotion_recommended' => 'nullable|boolean',
            'promotion_notes' => 'nullable|string',
        ]);

        // Parse JSON fields
        if (isset($validated['skill_ratings'])) {
            $validated['skill_ratings'] = json_decode($validated['skill_ratings'], true);
        }
        if (isset($validated['behavioral_ratings'])) {
            $validated['behavioral_ratings'] = json_decode($validated['behavioral_ratings'], true);
        }
        if (isset($validated['kpi_ratings'])) {
            $validated['kpi_ratings'] = json_decode($validated['kpi_ratings'], true);
        }

        $validated['appraised_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'draft';

        PerformanceAppraisal::create($validated);

        return redirect()->route('hms.hr.appraisals.index')
            ->with('success', 'Performance appraisal created successfully.');
    }

    /**
     * Display the specified appraisal.
     */
    public function show(PerformanceAppraisal $appraisal): View
    {
        $appraisal->load(['employee', 'appraiser']);
        return view('hms.hr.appraisals.show', compact('appraisal'));
    }

    /**
     * Show the form for editing the specified appraisal.
     */
    public function edit(PerformanceAppraisal $appraisal): View
    {
        $employees = Employee::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'position']);
        return view('hms.hr.appraisals.edit', compact('appraisal', 'employees'));
    }

    /**
     * Update the specified appraisal.
     */
    public function update(Request $request, PerformanceAppraisal $appraisal): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string|max:255',
            'appraisal_date' => 'required|date',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'overall_rating' => 'nullable|in:excellent,good,satisfactory,needs_improvement,poor',
            'skill_ratings' => 'nullable|string',
            'behavioral_ratings' => 'nullable|string',
            'kpi_ratings' => 'nullable|string',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals_for_next_period' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'appraiser_comments' => 'nullable|string',
            'hr_comments' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,reviewed,approved,archived',
            'promotion_recommended' => 'nullable|boolean',
            'promotion_notes' => 'nullable|string',
        ]);

        // Parse JSON fields
        if (isset($validated['skill_ratings'])) {
            $validated['skill_ratings'] = json_decode($validated['skill_ratings'], true);
        }
        if (isset($validated['behavioral_ratings'])) {
            $validated['behavioral_ratings'] = json_decode($validated['behavioral_ratings'], true);
        }
        if (isset($validated['kpi_ratings'])) {
            $validated['kpi_ratings'] = json_decode($validated['kpi_ratings'], true);
        }

        $appraisal->update($validated);

        return redirect()->route('hms.hr.appraisals.index')
            ->with('success', 'Performance appraisal updated successfully.');
    }

    /**
     * Remove the specified appraisal.
     */
    public function destroy(PerformanceAppraisal $appraisal): RedirectResponse
    {
        $appraisal->delete();

        return redirect()->route('hms.hr.appraisals.index')
            ->with('success', 'Performance appraisal deleted successfully.');
    }
}
