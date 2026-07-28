@extends('admin.layouts.app')

@section('title', 'Training Enrollments')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $trainingProgram->title }} - Enrollments</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.training-programs.index') }}" class="text-white-50">Training Programs</a></li>
                                <li class="breadcrumb-item text-white active">Enrollments</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.training-programs.show', $trainingProgram) }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Employee ID</th>
                                        <th class="px-4 py-3">Department</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Hours Attended</th>
                                        <th class="px-4 py-3">Certificate</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $enrollment->employee->full_name }}</h6>
                                            </td>
                                            <td class="px-4 py-3">{{ $enrollment->employee->employee_id }}</td>
                                            <td class="px-4 py-3">
                                                @if($enrollment->employee->department)
                                                    <span class="badge bg-primary">{{ $enrollment->employee->department->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
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
                                                <div class="btn-group btn-group-sm">
                                                    @if($enrollment->status !== 'completed')
                                                        <form action="{{ route('hms.hr.training-enrollments.complete', $enrollment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success" title="Mark Complete">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($enrollment->status === 'completed' && $trainingProgram->certificate_available && !$enrollment->certificate_issued)
                                                        <form action="{{ route('hms.hr.training-enrollments.certificate', $enrollment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-primary" title="Issue Certificate">
                                                                <i class="fas fa-certificate"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($enrollment->certificate_path)
                                                        <a href="{{ Storage::url($enrollment->certificate_path) }}" target="_blank" class="btn btn-outline-info" title="Download Certificate">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-4 border-top">
                            {{ $enrollments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                            <h4>No Enrollments Found</h4>
                            <p class="text-muted">No employees have been enrolled in this training program yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

