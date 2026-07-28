@extends('admin.layouts.app')

@section('title', 'Shift Roster')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $shift->name }} - Roster</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.shifts.index') }}" class="text-white-50">Shifts</a></li>
                                <li class="breadcrumb-item text-white active">Roster</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="fas fa-user-plus me-2"></i>Assign Employees
                        </button>
                        <a href="{{ route('hms.hr.shifts.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <form method="GET" class="d-flex gap-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($employeeShifts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Employee ID</th>
                                        <th class="px-4 py-3">Department</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">End Date</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeeShifts as $employeeShift)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $employeeShift->employee->full_name }}</h6>
                                            </td>
                                            <td class="px-4 py-3">{{ $employeeShift->employee->employee_id }}</td>
                                            <td class="px-4 py-3">
                                                @if($employeeShift->employee->department)
                                                    <span class="badge bg-primary">{{ $employeeShift->employee->department->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">{{ $employeeShift->start_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                {{ $employeeShift->end_date ? $employeeShift->end_date->format('M d, Y') : 'Ongoing' }}
                                            </td>
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
                        <div class="d-flex justify-content-between align-items-center p-4 border-top">
                            {{ $employeeShifts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-week fa-4x text-muted mb-3"></i>
                            <h4>No Employees Assigned</h4>
                            <p class="text-muted">Assign employees to this shift to build the roster.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                                <i class="fas fa-user-plus me-2"></i>Assign Employees
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Employees Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hms.hr.employee-shifts.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="shift_id" value="{{ $shift->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Employees to Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employees <span class="text-danger">*</span></label>
                        <select name="employee_ids[]" class="form-select" multiple size="8" required>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }} - {{ $employee->employee_id }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple employees</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control">
                        <small class="text-muted">Leave empty for ongoing assignment</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

