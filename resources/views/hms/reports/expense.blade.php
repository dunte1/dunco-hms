@extends('admin.layouts.app')

@section('title', 'Expense Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-money-bill-wave me-3"></i>Expense Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Expense Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Card -->
    <div class="row g-3 mb-4">
        <div class="col-xl-12 col-md-12">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Expenses</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">${{ number_format($totalExpenses, 2) }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-money-bill-wave text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">From Date</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">To Date</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none;">
                                <i class="fas fa-filter me-2"></i>Apply Filter
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
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Expense Details
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($expenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Category</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Description</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Amount</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.85rem; background: #fee2e2; color: #991b1b;">
                                                    <i class="fas fa-tag me-1"></i>{{ $expense->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ $expense->description ?? 'N/A' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-danger">${{ number_format($expense->amount, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success" style="font-size: 0.85rem;">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Paid
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                                    <i class="fas fa-money-bill-wave" style="font-size: 3rem; color: #ef4444;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Expenses Found</h4>
                            <p class="text-muted mb-4">No expenses found for the selected date range.</p>
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

    * {
        transition: all 0.3s ease;
    }
</style>
@endsection
