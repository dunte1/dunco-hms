@extends('admin.layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $application->first_name }} {{ $application->last_name }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.job-applications.index') }}" class="text-white-50">Applications</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.job-applications.index') }}" class="btn btn-light">
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
                    <h4 class="fw-bold mb-4">Application Information</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Job Position</label>
                            <p class="mb-0 fw-bold">{{ $application->jobPosting->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <span class="badge 
                                    @if($application->status === 'pending') bg-warning
                                    @elseif($application->status === 'shortlisted') bg-info
                                    @elseif($application->status === 'hired') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">{{ $application->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0">{{ $application->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Applied On</label>
                            <p class="mb-0">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($application->interview_date)
                        <div class="col-md-6">
                            <label class="text-muted small">Interview Date</label>
                            <p class="mb-0">{{ $application->interview_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($application->reviewedBy)
                        <div class="col-md-6">
                            <label class="text-muted small">Reviewed By</label>
                            <p class="mb-0">{{ $application->reviewedBy->name }}</p>
                            <small class="text-muted">{{ $application->reviewed_at->format('M d, Y') }}</small>
                        </div>
                        @endif
                    </div>

                    @if($application->cover_letter)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Cover Letter</h5>
                    <div>
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                    @endif

                    @if($application->interview_notes)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Interview Notes</h5>
                    <div>
                        {!! nl2br(e($application->interview_notes)) !!}
                    </div>
                    @endif

                    @if($application->notes)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Notes</h5>
                    <div>
                        {!! nl2br(e($application->notes)) !!}
                    </div>
                    @endif
                </div>
            </div>

            @if($application->resume_path)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Resume</h5>
                    <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download Resume
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Actions</h5>
                </div>
                <div class="card-body">
                    @if($application->status === 'pending')
                        <form action="{{ route('hms.hr.job-applications.shortlist', $application) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-check me-2"></i>Shortlist
                            </button>
                        </form>
                        <form action="{{ route('hms.hr.job-applications.reject', $application) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Rejection Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times me-2"></i>Reject
                            </button>
                        </form>
                    @endif

                    @if($application->status === 'shortlisted' && !$application->employee_id)
                        <a href="#" class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#convertModal">
                            <i class="fas fa-user-plus me-2"></i>Convert to Employee
                        </a>
                    @endif

                    @if($application->employee)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Converted to Employee</strong><br>
                            <a href="{{ route('hms.hr.employees.show', $application->employee) }}">View Employee Profile</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($application->status === 'shortlisted' && !$application->employee_id)
<!-- Convert to Employee Modal -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hms.hr.job-applications.convert', $application) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Convert to Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-select" required>
                            @foreach(\App\Models\EmployeeDepartment::all() as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control" value="{{ $application->jobPosting->designation->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="number" name="salary" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="create_user_account" id="create_account" value="1">
                        <label class="form-check-label" for="create_account">Create User Account</label>
                    </div>
                    <div id="passwordFields" style="display: none;">
                        <div class="mb-3 mt-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Convert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('create_account').addEventListener('change', function() {
    document.getElementById('passwordFields').style.display = this.checked ? 'block' : 'none';
});
</script>
@endif
@endsection

