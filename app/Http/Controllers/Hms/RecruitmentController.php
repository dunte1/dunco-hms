<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of job postings.
     */
    public function index(): View
    {
        $jobPostings = JobPosting::with(['department', 'designation'])
            ->latest()
            ->paginate(15);
        
        return view('hms.hr.job-postings.index', compact('jobPostings'));
    }

    /**
     * Show the form for creating a new job posting.
     */
    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        
        return view('hms.hr.job-postings.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created job posting.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:employee_departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after:today',
            'vacancies' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed',
        ]);

        $validated['posted_by'] = auth()->id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        JobPosting::create($validated);

        return redirect()->route('hms.hr.job-postings.index')
            ->with('success', 'Job posting created successfully.');
    }

    /**
     * Display the specified job posting.
     */
    public function show(JobPosting $jobPosting): View
    {
        $jobPosting->load(['department', 'designation', 'applications']);
        
        return view('hms.hr.job-postings.show', compact('jobPosting'));
    }

    /**
     * Show the form for editing the specified job posting.
     */
    public function edit(JobPosting $jobPosting): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        
        return view('hms.hr.job-postings.edit', compact('jobPosting', 'departments', 'designations'));
    }

    /**
     * Update the specified job posting.
     */
    public function update(Request $request, JobPosting $jobPosting): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:employee_departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after:today',
            'vacancies' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed',
        ]);

        if ($jobPosting->status !== 'published' && $validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $jobPosting->update($validated);

        return redirect()->route('hms.hr.job-postings.index')
            ->with('success', 'Job posting updated successfully.');
    }

    /**
     * Remove the specified job posting.
     */
    public function destroy(JobPosting $jobPosting): RedirectResponse
    {
        $jobPosting->delete();

        return redirect()->route('hms.hr.job-postings.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    /**
     * Publish a job posting.
     */
    public function publish(JobPosting $jobPosting): RedirectResponse
    {
        $jobPosting->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Job posting published successfully.');
    }

    /**
     * Display all job applications.
     */
    public function applications(Request $request): View
    {
        $query = JobApplication::with(['jobPosting', 'reviewedBy']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }
        
        $applications = $query->latest()->paginate(15);
        $jobPostings = JobPosting::where('status', 'published')->get();
        
        return view('hms.hr.job-applications.index', compact('applications', 'jobPostings'));
    }

    /**
     * Show a specific application.
     */
    public function showApplication(JobApplication $application): View
    {
        $application->load(['jobPosting', 'reviewedBy', 'employee']);
        
        return view('hms.hr.job-applications.show', compact('application'));
    }

    /**
     * Shortlist an application.
     */
    public function shortlist(JobApplication $application): RedirectResponse
    {
        $application->update([
            'status' => 'shortlisted',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Application shortlisted successfully.');
    }

    /**
     * Reject an application.
     */
    public function reject(Request $request, JobApplication $application): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Application rejected.');
    }

    /**
     * Convert application to employee.
     */
    public function convertToEmployee(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'department_id' => 'required|exists:employee_departments,id',
            'position' => 'required|string',
            'create_user_account' => 'nullable|boolean',
            'password' => 'required_if:create_user_account,1|nullable|string|min:8|confirmed',
        ]);

        // Create employee
        $employeeData = [
            'employee_id' => 'EMP-' . date('Y') . '-' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT),
            'first_name' => $application->first_name,
            'last_name' => $application->last_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'department_id' => $validated['department_id'],
            'position' => $validated['position'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
            'employment_type' => $application->jobPosting->employment_type,
            'status' => 'active',
        ];

        // Create user account if requested
        if ($request->has('create_user_account') && $request->create_user_account) {
            $user = User::create([
                'name' => $application->first_name . ' ' . $application->last_name,
                'email' => $application->email,
                'password' => Hash::make($request->password),
                'phone' => $application->phone,
                'email_verified_at' => now(),
            ]);
            
            $employeeData['user_id'] = $user->id;
        }

        $employee = Employee::create($employeeData);

        // Update application
        $application->update([
            'status' => 'hired',
            'employee_id' => $employee->id,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('hms.hr.employees.index')
            ->with('success', 'Application converted to employee successfully. Employee ID: ' . $employee->employee_id);
    }
}
