@extends('admin.layouts.app')

@section('title', 'Training Program Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $trainingProgram->title }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.training-programs.index') }}" class="text-white-50">Training Programs</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.training-programs.edit', $trainingProgram) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.training-programs.index') }}" class="btn btn-light">
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
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Category</label>
                            <p class="mb-0">
                                @if($trainingProgram->category)
                                    <span class="badge bg-secondary">{{ $trainingProgram->category }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <span class="badge 
                                    @if($trainingProgram->status === 'upcoming') bg-info
                                    @elseif($trainingProgram->status === 'ongoing') bg-primary
                                    @elseif($trainingProgram->status === 'completed') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($trainingProgram->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Start Date</label>
                            <p class="mb-0 fw-bold">{{ $trainingProgram->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">End Date</label>
                            <p class="mb-0 fw-bold">{{ $trainingProgram->end_date ? $trainingProgram->end_date->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Duration</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $trainingProgram->duration_hours }} hours</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0">{{ $trainingProgram->location ?? 'Not specified' }}</p>
                        </div>
                        @if($trainingProgram->instructor)
                        <div class="col-md-6">
                            <label class="text-muted small">Instructor</label>
                            <p class="mb-0">{{ $trainingProgram->instructor }}</p>
                        </div>
                        @endif
                        @if($trainingProgram->max_participants)
                        <div class="col-md-6">
                            <label class="text-muted small">Max Participants</label>
                            <p class="mb-0">{{ $trainingProgram->max_participants }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="text-muted small">Certificate Available</label>
                            <p class="mb-0">
                                @if($trainingProgram->certificate_available)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($trainingProgram->description)
                    <h5 class="fw-bold mb-3">Description</h5>
                    <div>
                        {!! nl2br(e($trainingProgram->description)) !!}
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Enrolled Employees ({{ $trainingProgram->enrollments->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($trainingProgram->enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Hours Attended</th>
                                        <th class="px-4 py-3">Certificate</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainingProgram->enrollments as $enrollment)
                                        <tr>
                                            <td class="px-4 py-3">{{ $enrollment->employee->full_name }}</td>
                                            <td class="px-4 py-3">
                                                <span class="badge 
                                                    @if($enrollment->status === 'completed') bg-success
                                                    @elseif($enrollment->status === 'attended') bg-primary
                                                    @elseif($enrollment->status === 'registered') bg-info
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">{{ $enrollment->attendance_hours }} hours</td>
                                            <td class="px-4 py-3">
                                                @if($enrollment->certificate_issued)
                                                    <span class="badge bg-success">Issued</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if($enrollment->status !== 'completed')
                                                    <form action="{{ route('hms.hr.training-enrollments.complete', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check"></i> Mark Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($enrollment->status === 'completed' && $trainingProgram->certificate_available && !$enrollment->certificate_issued)
                                                    <form action="{{ route('hms.hr.training-enrollments.certificate', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-certificate"></i> Issue Certificate
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">No employees enrolled yet</p>
                            @if($availableEmployees->count() > 0)
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
                                    <i class="fas fa-user-plus me-2"></i>Enroll Employees
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    @if($availableEmployees->count() > 0)
                        <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#enrollModal">
                            <i class="fas fa-user-plus me-2"></i>Enroll Employees
                        </button>
                    @endif
                    <a href="{{ route('hms.hr.training-programs.enrollments', $trainingProgram) }}" class="btn btn-info w-100 mb-3">
                        <i class="fas fa-list me-2"></i>View All Enrollments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($availableEmployees->count() > 0)
<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hms.hr.training-programs.enroll', $trainingProgram) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Enroll Employees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employees</label>
                        <select name="employee_ids[]" class="form-select" multiple size="10" required>
                            @foreach($availableEmployees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }} - {{ $employee->position }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

