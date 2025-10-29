@extends('admin.layouts.app')

@section('title', 'Leave Requests Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); box-shadow: 0 10px 30px rgba(20, 184, 166, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-calendar-times me-3"></i>Leave Requests Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Leave Requests</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.leave-requests.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus me-2"></i>New Leave Request
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Requests</h6>
                            <h2 class="mb-0 fw-bold" style="color: #14b8a6;">{{ $leaveRequests->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                            <h6 class="text-muted text-uppercase mb-2 small">Pending</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $leaveRequests->where('status', 'pending')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Approved</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $leaveRequests->where('status', 'approved')->count() }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Rejected</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ $leaveRequests->where('status', 'rejected')->count() }}</h2>
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
    </div>

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                <span class="badge bg-teal-subtle text-teal px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Leave Requests
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search leave requests...">
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><h6 class="dropdown-header">Filter by Status</h6></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="all">All Status</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="pending">Pending</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="approved">Approved</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="rejected">Rejected</a></li>
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

                    @if($leaveRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="leaveRequestsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 15%;">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Leave Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Start Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">End Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 8%;">Days</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 15%;">Reason</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center" style="width: 14%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveRequests as $request)
                                        <tr class="leave-row" data-status="{{ $request->status }}">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $request->employee->full_name }}</h6>
                                                        <small class="text-muted">{{ $request->employee->employee_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    <i class="fas fa-{{ $request->leave_type == 'sick' ? 'heart' : ($request->leave_type == 'vacation' ? 'umbrella-beach' : ($request->leave_type == 'maternity' ? 'baby' : 'exclamation-triangle')) }} me-1"></i>
                                                    {{ ucfirst($request->leave_type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $request->start_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $request->end_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 fw-bold">
                                                    {{ $request->total_days }} days
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $request->reason }}">
                                                    {{ $request->reason }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $request->status === 'approved' ? '#d1fae5' : ($request->status === 'pending' ? '#fef3c7' : '#fee2e2') }}; 
                                                             color: {{ $request->status === 'approved' ? '#065f46' : ($request->status === 'pending' ? '#78350f' : '#991b1b') }};">
                                                    <i class="fas fa-{{ $request->status === 'approved' ? 'check-circle' : ($request->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if($request->status === 'pending')
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <form action="{{ route('hms.hr.leave-requests.approve', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to approve this leave request?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            onclick="rejectRequest({{ $request->id }})" 
                                                            data-bs-toggle="tooltip" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                @else
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($leaveRequests->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $leaveRequests->firstItem() }}</strong> to <strong>{{ $leaveRequests->lastItem() }}</strong> of <strong>{{ $leaveRequests->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $leaveRequests->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);">
                                    <i class="fas fa-calendar-times" style="font-size: 3rem; color: #14b8a6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Leave Requests Found</h4>
                            <p class="text-muted mb-4">Start by creating your first leave request.</p>
                            <a href="{{ route('hms.hr.leave-requests.create') }}" class="btn btn-primary btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); border: none;">
                                <i class="fas fa-plus me-2"></i>Create First Leave Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-times-circle me-2"></i>Reject Leave Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Reason for Rejection</label>
                        <textarea name="admin_notes" class="form-control form-control-lg" rows="4" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-times-circle me-1"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Enhanced Hover Effects */
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .leave-row:hover {
        background-color: #f8fafc !important;
    }

    .btn-success:hover {
        background: #059669;
        border-color: transparent;
    }

    .btn-danger:hover {
        background: #dc2626;
        border-color: transparent;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.25);
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
    const table = document.getElementById('leaveRequestsTable');
    
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
            const rows = document.querySelectorAll('.leave-row');
            
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

function rejectRequest(requestId) {
    document.getElementById('rejectForm').action = `/hms/hr/leave-requests/${requestId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush
@endsection
