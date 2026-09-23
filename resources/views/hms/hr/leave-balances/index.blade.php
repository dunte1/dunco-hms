@extends('admin.layouts.app')

@section('title', 'Leave Balances')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-wallet me-3"></i>Leave Balances
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Leave Balances</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0 d-flex gap-2">
                        <form action="{{ route('hms.hr.leave-balances.seed') }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Seed leave balances for the current year?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-lg shadow-sm px-4">
                                <i class="fas fa-seedling me-2"></i>Seed Balances
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('hms.hr.leave-balances.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small">Employee</label>
                            <select name="employee_id" class="form-select form-select-lg">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark small">Year</label>
                            <select name="year" class="form-select form-select-lg">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none;">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </form>
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
                                <span class="badge bg-info-subtle text-info px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Leave Balances
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search balances...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                <strong>{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($balances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="balancesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Leave Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Entitled</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Used</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Remaining</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Carried Forward</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($balances as $balance)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $balance->employee->first_name }} {{ $balance->employee->last_name }}</h6>
                                                        <small class="text-muted">{{ $balance->employee->employee_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    {{ $balance->leaveType->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="fw-bold">{{ $balance->entitled }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="fw-bold">{{ $balance->used }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge rounded-pill px-3 py-2 fw-bold"
                                                      style="background: {{ $balance->remaining > 0 ? '#d1fae5' : '#fee2e2' }};
                                                             color: {{ $balance->remaining > 0 ? '#065f46' : '#991b1b' }};">
                                                    {{ $balance->remaining }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="fw-bold">{{ $balance->carried_forward ?? 0 }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">{{ $balance->year }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($balances->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $balances->firstItem() }}</strong> to <strong>{{ $balances->lastItem() }}</strong> of <strong>{{ $balances->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $balances->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
                                    <i class="fas fa-wallet" style="font-size: 3rem; color: #0ea5e9;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Leave Balances Found</h4>
                            <p class="text-muted mb-4">Seed balances to get started.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    }
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#balancesTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
@endsection
