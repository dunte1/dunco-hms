@extends('admin.layouts.app')

@section('title', 'Payroll Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-money-bill-wave me-3"></i>Payroll Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Payrolls</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.payrolls.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-calculator me-2"></i>Generate New Payroll
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Payrolls</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $payrolls->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-invoice-dollar text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Payout</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">${{ number_format($payrolls->sum('net_salary'), 2) }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-dollar-sign text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Paid</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $payrolls->where('status', 'paid')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                            <h6 class="text-muted text-uppercase mb-2 small">Pending</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ $payrolls->where('status', 'pending')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock text-white fs-4"></i>
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
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Payroll Records
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search payrolls...">
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><h6 class="dropdown-header">Filter by Status</h6></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="all">All Status</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="paid">Paid</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="pending">Pending</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="cancelled">Cancelled</a></li>
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

                    @if($payrolls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="payrollsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 18%;">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Period</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Pay Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-end" style="width: 10%;">Basic</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-end" style="width: 10%;">Gross</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-end" style="width: 10%;">Net</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center" style="width: 8%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payrolls as $payroll)
                                        <tr class="payroll-row" data-status="{{ $payroll->status }}">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $payroll->employee->full_name }}</h6>
                                                        <small class="text-muted">{{ $payroll->employee->employee_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                                    {{ $payroll->payroll_period }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $payroll->pay_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <span class="fw-bold text-dark">${{ number_format($payroll->basic_salary, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <span class="fw-bold text-success">${{ number_format($payroll->gross_salary, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-end">
                                                <span class="fw-bold" style="color: #059669; font-size: 1.05rem;">
                                                    ${{ number_format($payroll->net_salary, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $payroll->status === 'paid' ? '#d1fae5' : ($payroll->status === 'pending' ? '#fef3c7' : '#fee2e2') }}; 
                                                             color: {{ $payroll->status === 'paid' ? '#065f46' : ($payroll->status === 'pending' ? '#78350f' : '#991b1b') }};">
                                                    <i class="fas fa-{{ $payroll->status === 'paid' ? 'check-circle' : ($payroll->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                                    {{ ucfirst($payroll->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($payroll->status === 'pending')
                                                    <button type="button" class="btn btn-outline-success" 
                                                            data-bs-toggle="tooltip" title="Mark as Paid">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($payrolls->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $payrolls->firstItem() }}</strong> to <strong>{{ $payrolls->lastItem() }}</strong> of <strong>{{ $payrolls->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $payrolls->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                                    <i class="fas fa-money-bill-wave" style="font-size: 3rem; color: #f59e0b;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Payroll Records Found</h4>
                            <p class="text-muted mb-4">Start by generating your first payroll for employees.</p>
                            <a href="{{ route('hms.hr.payrolls.create') }}" class="btn btn-warning btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                                <i class="fas fa-calculator me-2"></i>Generate First Payroll
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

    .payroll-row:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        border-color: transparent;
        color: white;
    }

    .btn-outline-success:hover {
        background: #10b981;
        border-color: transparent;
        color: white;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.25);
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
    const table = document.getElementById('payrollsTable');
    
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
            const rows = document.querySelectorAll('.payroll-row');
            
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
