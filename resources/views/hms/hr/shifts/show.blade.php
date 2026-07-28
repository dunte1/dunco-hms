@extends('admin.layouts.app')

@section('title', 'Shift Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $shift->name }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.shifts.index') }}" class="text-white-50">Shifts</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.shifts.edit', $shift) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.shifts.roster', $shift) }}" class="btn btn-light me-2">
                            <i class="fas fa-calendar-week me-2"></i>View Roster
                        </a>
                        <a href="{{ route('hms.hr.shifts.index') }}" class="btn btn-light">
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
                            <label class="text-muted small">Start Time</label>
                            <p class="mb-0">
                                <span class="badge bg-primary fs-6">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">End Time</label>
                            <p class="mb-0">
                                <span class="badge bg-danger fs-6">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</span>
                            </p>
                        </div>
                        @php
                            $start = \Carbon\Carbon::parse($shift->start_time);
                            $end = \Carbon\Carbon::parse($shift->end_time);
                            if ($end < $start) {
                                $end->addDay();
                            }
                            $duration = $start->diffInHours($end);
                        @endphp
                        <div class="col-md-6">
                            <label class="text-muted small">Duration</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $duration }} hours</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($shift->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        @if($shift->description)
                        <div class="col-12">
                            <label class="text-muted small">Description</label>
                            <p class="mb-0">{{ $shift->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Assigned Employees ({{ $shift->employeeShifts->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($shift->employeeShifts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">End Date</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shift->employeeShifts->take(10) as $employeeShift)
                                        <tr>
                                            <td class="px-4 py-3">{{ $employeeShift->employee->full_name }}</td>
                                            <td class="px-4 py-3">{{ $employeeShift->start_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">{{ $employeeShift->end_date ? $employeeShift->end_date->format('M d, Y') : 'Ongoing' }}</td>
                                            <td class="px-4 py-3">
                                                @if($employeeShift->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($shift->employeeShifts->count() > 10)
                            <div class="p-3 text-center">
                                <a href="{{ route('hms.hr.shifts.roster', $shift) }}" class="btn btn-sm btn-primary">
                                    View All ({{ $shift->employeeShifts->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">No employees assigned to this shift</p>
                            <a href="{{ route('hms.hr.shifts.roster', $shift) }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Assign Employees
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

