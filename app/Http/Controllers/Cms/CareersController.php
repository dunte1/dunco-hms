<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobCategory;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareersController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobPosting::with('category')
            ->where('status', 'active')
            ->where('application_deadline', '>=', now());

        if ($request->filled('category')) {
            $query->where('job_category_id', $request->category);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        $jobs = $query->latest()->paginate(10);
        $categories = JobCategory::where('is_active', true)->get();
        $departments = JobPosting::distinct()->pluck('department');
        $employmentTypes = JobPosting::distinct()->pluck('employment_type');

        return view('site.careers.index', compact('jobs', 'categories', 'departments', 'employmentTypes'));
    }

    public function show(JobPosting $job): View
    {
        $relatedJobs = JobPosting::where('status', 'active')
            ->where('id', '!=', $job->id)
            ->where('job_category_id', $job->job_category_id)
            ->latest()
            ->limit(3)
            ->get();

        return view('site.careers.show', compact('job', 'relatedJobs'));
    }

    public function apply(JobPosting $job): View
    {
        return view('site.careers.apply', compact('job'));
    }

    public function storeApplication(Request $request, JobPosting $job): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'cover_letter_text' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle file uploads
        $resumePath = $request->file('resume')->store('job-applications/resumes');
        $coverLetterPath = $request->file('cover_letter') 
            ? $request->file('cover_letter')->store('job-applications/cover-letters')
            : null;

        JobApplication::create([
            'job_posting_id' => $job->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'resume_path' => $resumePath,
            'cover_letter_path' => $coverLetterPath,
            'cover_letter_text' => $data['cover_letter_text'],
        ]);

        return redirect()->route('careers.index')->with('status', 'Application submitted successfully!');
    }

    // Admin CRUD Methods for Job Postings
    public function adminIndex(Request $request): View
    {
        $query = JobPosting::with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('job_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('department', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(15);
        $categories = JobCategory::all();

        return view('cms.careers.index', compact('jobs', 'categories'));
    }

    public function create(): View
    {
        $categories = JobCategory::where('is_active', true)->get();
        return view('cms.careers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'department' => 'required|string',
            'employment_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'experience_level' => 'required|in:entry,junior,mid,senior,executive',
            'location' => 'required|string',
            'salary_range_min' => 'nullable|numeric',
            'salary_range_max' => 'nullable|numeric',
            'vacancies' => 'required|integer|min:1',
            'description' => 'required|string',
            'responsibilities' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'required|date|after:today',
            'status' => 'required|in:draft,active,closed',
            'is_featured' => 'boolean',
        ]);

        // Map field names to model fields
        $validated['title'] = $validated['job_title'];
        unset($validated['job_title']);
        
        $validated['salary_min'] = $validated['salary_range_min'] ?? null;
        $validated['salary_max'] = $validated['salary_range_max'] ?? null;
        unset($validated['salary_range_min'], $validated['salary_range_max']);
        
        $validated['is_featured'] = $request->has('is_featured');

        JobPosting::create($validated);

        return redirect()->route('cms.careers.index')
            ->with('success', 'Job posting created successfully!');
    }

    public function edit(JobPosting $job): View
    {
        $categories = JobCategory::where('is_active', true)->get();
        return view('cms.careers.edit', compact('job', 'categories'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'department' => 'required|string',
            'employment_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'experience_level' => 'required|in:entry,junior,mid,senior,executive',
            'location' => 'required|string',
            'salary_range_min' => 'nullable|numeric',
            'salary_range_max' => 'nullable|numeric',
            'vacancies' => 'required|integer|min:1',
            'description' => 'required|string',
            'responsibilities' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'required|date',
            'status' => 'required|in:draft,active,closed',
            'is_featured' => 'boolean',
        ]);

        // Map field names to model fields
        $validated['title'] = $validated['job_title'];
        unset($validated['job_title']);
        
        $validated['salary_min'] = $validated['salary_range_min'] ?? null;
        $validated['salary_max'] = $validated['salary_range_max'] ?? null;
        unset($validated['salary_range_min'], $validated['salary_range_max']);
        
        $validated['is_featured'] = $request->has('is_featured');

        $job->update($validated);

        return redirect()->route('cms.careers.index')
            ->with('success', 'Job posting updated successfully!');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();

        return redirect()->route('cms.careers.index')
            ->with('success', 'Job posting deleted successfully!');
    }

    // Applications Management
    public function applications(Request $request): View
    {
        $query = JobApplication::with('jobPosting');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job')) {
            $query->where('job_posting_id', $request->job);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->latest()->paginate(15);
        $jobs = JobPosting::all();

        return view('cms.careers.applications', compact('applications', 'jobs'));
    }
}
