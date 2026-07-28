@extends('admin.layouts.app')

@section('title', 'Job Posting Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $jobPosting->title }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.job-postings.index') }}" class="text-white-50">Job Postings</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.job-postings.edit', $jobPosting) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.job-postings.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Job Details</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Department</label>
                            <p class="mb-0">
                                @if($jobPosting->department)
                                    <span class="badge bg-primary">{{ $jobPosting->department->name }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Designation</label>
                            <p class="mb-0">
                                @if($jobPosting->designation)
                                    <span class="badge bg-info">{{ $jobPosting->designation->name }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Employment Type</label>
                            <p class="mb-0"><span class="badge bg-secondary">{{ ucfirst($jobPosting->employment_type) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <span class="badge 
                                    @if($jobPosting->status === 'published') bg-success
                                    @elseif($jobPosting->status === 'draft') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($jobPosting->status) }}
                                </span>
                            </p>
                        </div>
                        @if($jobPosting->salary_min || $jobPosting->salary_max)
                        <div class="col-md-6">
                            <label class="text-muted small">Salary Range</label>
                            <p class="mb-0 fw-bold">
                                @if($jobPosting->salary_min && $jobPosting->salary_max)
                                    {{ number_format($jobPosting->salary_min) }} - {{ number_format($jobPosting->salary_max) }}
                                @elseif($jobPosting->salary_min)
                                    From {{ number_format($jobPosting->salary_min) }}
                                @elseif($jobPosting->salary_max)
                                    Up to {{ number_format($jobPosting->salary_max) }}
                                @endif
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="text-muted small">Vacancies</label>
                            <p class="mb-0 fw-bold">{{ $jobPosting->vacancies }}</p>
                        </div>
                        @if($jobPosting->location)
                        <div class="col-md-6">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0">{{ $jobPosting->location }}</p>
                        </div>
                        @endif
                        @if($jobPosting->application_deadline)
                        <div class="col-md-6">
                            <label class="text-muted small">Application Deadline</label>
                            <p class="mb-0">{{ $jobPosting->application_deadline->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-3">Description</h5>
                    <div class="mb-4">
                        {!! nl2br(e($jobPosting->description)) !!}
                    </div>

                    @if($jobPosting->requirements)
                    <h5 class="fw-bold mb-3">Requirements</h5>
                    <div class="mb-4">
                        {!! nl2br(e($jobPosting->requirements)) !!}
                    </div>
                    @endif

                    @if($jobPosting->responsibilities)
                    <h5 class="fw-bold mb-3">Responsibilities</h5>
                    <div>
                        {!! nl2br(e($jobPosting->responsibilities)) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if($jobPosting->status === 'draft')
                        <form action="{{ route('hms.hr.job-postings.publish', $jobPosting) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-paper-plane me-2"></i>Publish Job Posting
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('hms.hr.job-applications.index', ['job_posting_id' => $jobPosting->id]) }}" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-file-alt me-2"></i>View Applications ({{ $jobPosting->applications->count() }})
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Applications:</span>
                        <strong>{{ $jobPosting->applications->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Pending:</span>
                        <strong>{{ $jobPosting->applications->where('status', 'pending')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shortlisted:</span>
                        <strong>{{ $jobPosting->applications->where('status', 'shortlisted')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Hired:</span>
                        <strong>{{ $jobPosting->applications->where('status', 'hired')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

