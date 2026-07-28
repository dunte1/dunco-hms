@extends('admin.layouts.app')

@section('title', 'Announcement Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $announcement->title }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.announcements.index') }}" class="text-white-50">Announcements</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.announcements.edit', $announcement) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.announcements.index') }}" class="btn btn-light">
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
                            <label class="text-muted small">Target Audience</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ ucfirst($announcement->target_audience) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        @if($announcement->department)
                        <div class="col-md-6">
                            <label class="text-muted small">Department</label>
                            <p class="mb-0"><span class="badge bg-primary">{{ $announcement->department->name }}</span></p>
                        </div>
                        @endif
                        @if($announcement->designation)
                        <div class="col-md-6">
                            <label class="text-muted small">Designation</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $announcement->designation->name }}</span></p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="text-muted small">Start Date</label>
                            <p class="mb-0 fw-bold">{{ $announcement->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">End Date</label>
                            <p class="mb-0 fw-bold">{{ $announcement->end_date ? $announcement->end_date->format('M d, Y') : 'No end date' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Created By</label>
                            <p class="mb-0">{{ $announcement->creator->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Created On</label>
                            <p class="mb-0">{{ $announcement->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Content</h5>
                    <div class="mb-4">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    @if($targetEmployees && $targetEmployees->count() > 0)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Target Employees ({{ $targetEmployees->count() }})</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($targetEmployees as $employee)
                                    <tr>
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->full_name }}</td>
                                        <td>{{ $employee->department->name ?? '-' }}</td>
                                        <td>{{ $employee->position }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($announcement->attachment_path)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Attachment</h5>
                    <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download Attachment
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

