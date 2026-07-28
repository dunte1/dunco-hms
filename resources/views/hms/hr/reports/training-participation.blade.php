@extends('admin.layouts.app')

@section('title', 'Training Participation Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-graduation-cap me-3"></i>Training Participation Report</h2>
                    <form method="GET" class="row g-2">
                        <div class="col-auto">
                            <select name="training_program_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All Programs</option>
                                @foreach($trainingPrograms as $program)
                                    <option value="{{ $program->id }}" {{ request('training_program_id') == $program->id ? 'selected' : '' }}>{{ $program->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Registered</option>
                                <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Attended</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Employee</th>
                                    <th class="px-4 py-3">Training Program</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Hours Attended</th>
                                    <th class="px-4 py-3">Certificate</th>
                                    <th class="px-4 py-3">Enrolled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td class="px-4 py-3">{{ $enrollment->employee->full_name }}</td>
                                        <td class="px-4 py-3">{{ $enrollment->trainingProgram->title }}</td>
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
                                        <td class="px-4 py-3">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-muted">No training enrollments found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

