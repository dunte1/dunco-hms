@extends('admin.layouts.app')

@section('title', 'Staff Attendance Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-user-check me-3"></i>Staff Attendance Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Staff Attendance</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('hms.moh-reports.staff-attendance') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                            <a href="{{ route('hms.moh-reports.staff-attendance') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">Total Active Staff</h6>
                            <h2 class="mb-0 fw-bold" style="color: #06b6d4;">{{ number_format($totalStaff) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">Present Today</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ number_format($presentToday) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">On Leave</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ number_format($onLeaveToday) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plane-departure text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">Absent Today</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ number_format($absentToday) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times-circle text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2 text-info"></i>By Department</h5>
                </div>
                <div class="card-body">
                    @if($byDepartment->count() > 0)
                        @foreach($byDepartment as $dept)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ $dept->department_name }}</span>
                                <span class="badge bg-info rounded-pill px-3 py-2">{{ number_format($dept->count) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalStaff > 0 ? ($dept->count / $totalStaff) * 100 : 0 }}%; background: linear-gradient(135deg, #06b6d4, #0891b2);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No department data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2 text-info"></i>Attendance Records</h5>
                </div>
                <div class="card-body p-0">
                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Staff</th>
                                        <th class="px-4 py-3 fw-semibold">Check In</th>
                                        <th class="px-4 py-3 fw-semibold">Check Out</th>
                                        <th class="px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $att)
                                        <tr>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $att->user->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A') : '-' }}</td>
                                            <td class="px-4 py-3">{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i A') : '-' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill {{ $att->status === 'present' ? 'bg-success' : ($att->status === 'leave' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ ucfirst($att->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">{{ $attendances->withQueryString()->links() }}</div>
                    @else
                        <p class="text-muted text-center py-5">No attendance records found for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important; }
</style>
@endsection
