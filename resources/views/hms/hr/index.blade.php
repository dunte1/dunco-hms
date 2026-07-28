@extends('admin.layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 30px rgba(249, 115, 22, 0.3);">
                <h2 class="text-white mb-0 fw-bold">
                    <i class="fas fa-users-cog me-3"></i>HR Dashboard
                </h2>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        @foreach($stats as $stat)
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-2 small">{{ $stat['label'] }}</h6>
                                <h2 class="mb-0 fw-bold" style="color: #f97316;">{{ $stat['value'] }}</h2>
                            </div>
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Staff Distribution by Department</h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Gender Ratio</h5>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Employment Type Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="employmentTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Recent Attendance -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Today's Attendance</h5>
                </div>
                <div class="card-body">
                    @if($recentAttendance->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentAttendance->take(5) as $attendance)
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $attendance->user->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : 'Not checked in' }}</small>
                                        </div>
                                        <span class="badge 
                                            @if($attendance->status === 'present') bg-success
                                            @elseif($attendance->status === 'late') bg-warning
                                            @elseif($attendance->status === 'absent') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No attendance records for today</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Leave Requests -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Pending Leave Requests</h5>
                </div>
                <div class="card-body">
                    @if($pendingLeaves->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pendingLeaves as $leave)
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div>
                                        <h6 class="mb-1">{{ $leave->employee->full_name }}</h6>
                                        <small class="text-muted">{{ $leave->leave_type }}</small><br>
                                        <small class="text-muted">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No pending leave requests</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Birthdays & Contract Expirations -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted mb-2">Birthdays (Next 7 Days)</h6>
                    @if($upcomingBirthdays->count() > 0)
                        @foreach($upcomingBirthdays as $employee)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0 small">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">{{ $employee->position }}</small>
                                </div>
                                <small class="text-primary fw-bold">{{ $employee->date_of_birth->format('M d') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-3">No upcoming birthdays</p>
                    @endif
                    
                    <hr>
                    <h6 class="text-muted mb-2">Contract Expirations (Next 30 Days)</h6>
                    @if($contractExpirations->count() > 0)
                        @foreach($contractExpirations->take(3) as $employee)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0 small">{{ $employee->full_name }}</h6>
                                    <small class="text-muted">{{ $employee->position }}</small>
                                </div>
                                <small class="text-warning fw-bold">{{ $employee->contract_end_date->format('M d') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">No expiring contracts</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Summary & Quick Actions -->
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Payroll Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ number_format($payrollSummary['this_month'], 2) }}</h4>
                            <small class="text-muted">This Month</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $payrollSummary['pending'] }}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('hms.hr.employees.index') }}" class="btn btn-primary w-100 btn-sm">
                                <i class="fas fa-users me-1"></i> Employees
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('hms.hr.attendance.index') }}" class="btn btn-success w-100 btn-sm">
                                <i class="fas fa-clock me-1"></i> Attendance
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('hms.hr.leave-requests.index') }}" class="btn btn-warning w-100 btn-sm">
                                <i class="fas fa-calendar-times me-1"></i> Leaves
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('hms.hr.payrolls.index') }}" class="btn btn-purple w-100 btn-sm">
                                <i class="fas fa-money-bill-wave me-1"></i> Payroll
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Department Distribution Chart
    const deptCtx = document.getElementById('departmentChart');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels: @json($deptChartLabels),
                datasets: [{
                    data: @json($deptChartData),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Gender Ratio Chart
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: @json($genderChartLabels),
                datasets: [{
                    data: @json($genderChartData),
                    backgroundColor: ['#3b82f6', '#ec4899', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Employment Type Chart
    const empTypeCtx = document.getElementById('employmentTypeChart');
    if (empTypeCtx) {
        new Chart(empTypeCtx, {
            type: 'bar',
            data: {
                labels: @json($empTypeLabels),
                datasets: [{
                    label: 'Employees',
                    data: @json($empTypeData),
                    backgroundColor: '#f97316'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection


