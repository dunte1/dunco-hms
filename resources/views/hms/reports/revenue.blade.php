@extends('admin.layouts.app')

@section('title', 'Revenue Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-chart-line me-3"></i>Revenue Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Revenue Report</li>
                            </ol>
                        </nav>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #059669;">${{ number_format($totalRevenue, 2) }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Paid Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">${{ number_format($paidRevenue, 2) }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Pending Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">${{ number_format($pendingRevenue, 2) }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Partial Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">${{ number_format($partialRevenue, 2) }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-pie text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-chart-line me-1"></i>
                        </span>
                        Daily Revenue Trend (Last 30 Days)
                    </h5>
                                </div>
                <div class="card-body p-4">
                    <canvas id="revenueChart" height="60"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Invoice Details
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($invoices->count() > 0)
                    <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Invoice #</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Total</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Paid</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Balance</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($invoices as $invoice)
                                <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">#{{ $invoice->invoice_number }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ $invoice->invoice_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">${{ number_format($invoice->total_amount, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-success fw-bold">${{ number_format($invoice->paid_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-danger fw-bold">${{ number_format($invoice->balance_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $invoice->status === 'paid' ? '#d1fae5' : ($invoice->status === 'partial' ? '#fef3c7' : '#fee2e2') }}; 
                                                             color: {{ $invoice->status === 'paid' ? '#065f46' : ($invoice->status === 'partial' ? '#78350f' : '#991b1b') }};">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                        @if($invoices->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $invoices->firstItem() }}</strong> to <strong>{{ $invoices->lastItem() }}</strong> of <strong>{{ $invoices->total() }}</strong> entries
                            </div>
                            <div>{{ $invoices->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <i class="fas fa-chart-line" style="font-size: 3rem; color: #10b981; opacity: 0.3;"></i>
                            <h4 class="text-dark mt-3 mb-2 fw-bold">No Revenue Data</h4>
                            <p class="text-muted">No invoices found for the selected period.</p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueData = @json($dailyRevenue);

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: revenueData.map(item => item.date),
        datasets: [{
            label: 'Daily Revenue',
            data: revenueData.map(item => item.revenue),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toFixed(2);
                    }
                }
            }
        }
    }
});
</script>
@endsection
