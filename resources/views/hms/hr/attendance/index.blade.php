@extends('admin.layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-calendar-check me-3"></i>Attendance Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Attendance</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.attendance.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus me-2"></i>Record Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Records</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $attendance->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Present</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $attendance->where('status', 'present')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Absent</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ $attendance->where('status', 'absent')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times-circle text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">On Leave</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $attendance->where('status', 'leave')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-times text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Attendance Records
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search attendance...">
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><h6 class="dropdown-header">Filter by Status</h6></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="all">All Status</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="present">Present</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="absent">Absent</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="late">Late</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="leave">Leave</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('status') || session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert" style="border-left: 4px solid #10b981 !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                <strong>{{ session('status') ?? session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($attendance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="attendanceTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 20%;">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Check In</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Check Out</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Hours Worked</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center" style="width: 8%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendance as $record)
                                        <tr class="attendance-row" data-status="{{ $record->status }}">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $record->user->name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $record->user->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $record->date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($record->check_in)
                                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                                        <i class="fas fa-sign-in-alt me-1"></i>{{ $record->check_in->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($record->check_out)
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                        <i class="fas fa-sign-out-alt me-1"></i>{{ $record->check_out->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">{{ round($record->hours_worked / 60, 1) }} hrs</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $record->status === 'present' ? '#d1fae5' : ($record->status === 'late' ? '#fef3c7' : ($record->status === 'leave' ? '#dbebfb' : '#fee2e2')) }}; 
                                                             color: {{ $record->status === 'present' ? '#065f46' : ($record->status === 'late' ? '#78350f' : ($record->status === 'leave' ? '#1e40af' : '#991b1b')) }};">
                                                    <i class="fas fa-{{ $record->status === 'present' ? 'check-circle' : ($record->status === 'late' ? 'clock' : ($record->status === 'leave' ? 'calendar-times' : 'times-circle')) }} me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($attendance->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $attendance->firstItem() }}</strong> to <strong>{{ $attendance->lastItem() }}</strong> of <strong>{{ $attendance->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $attendance->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e9d5ff 0%, #ddd6fe 100%);">
                                    <i class="fas fa-calendar-check" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Attendance Records Found</h4>
                            <p class="text-muted mb-4">Start by recording attendance for employees.</p>
                            <a href="{{ route('hms.hr.attendance.create') }}" class="btn btn-primary btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none;">
                                <i class="fas fa-plus me-2"></i>Record First Attendance
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced Hover Effects */
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .attendance-row:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border-color: transparent;
        color: white;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
        border-radius: 0.5rem;
    }

    /* Smooth transitions */
    * {
        transition: all 0.3s ease;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('attendanceTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // Filter functionality
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const filterValue = this.getAttribute('data-filter');
            const rows = document.querySelectorAll('.attendance-row');
            
            if (filterValue === 'all') {
                rows.forEach(row => row.style.display = '');
            } else {
                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    row.style.display = status === filterValue ? '' : 'none';
                });
            }
            
            // Update active state
            document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush
@endsection
