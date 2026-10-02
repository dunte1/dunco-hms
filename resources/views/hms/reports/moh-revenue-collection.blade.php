@extends('admin.layouts.app')

@section('title', 'Revenue Collection Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-coins me-3"></i>Revenue Collection Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Revenue Collection</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.pdf', 'revenue-collection') }}?{{ request()->query() }}" class="btn btn-light">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('hms.moh-reports.revenue-collection') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('hms.moh-reports.revenue-collection') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $currencySymbol = \App\Models\SystemSetting::get('currency_symbol', 'KES');
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">Total Collected</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $currencySymbol }} {{ number_format($totalCollected, 2) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-coins text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Outstanding</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ $currencySymbol }} {{ number_format($outstandingBalance, 2) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-circle text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Payment Methods</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ number_format($byMethod->count()) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-credit-card text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Departments</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ number_format($byDepartment->count()) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6, #2563eb); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-purple"></i>Payments by Method</h5>
                </div>
                <div class="card-body">
                    @if($byMethod->count() > 0)
                        @foreach($byMethod as $method)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ ucwords(str_replace('_', ' ', $method->payment_method ?? 'N/A')) }}</span>
                                <span class="badge bg-success rounded-pill px-3 py-2">{{ $currencySymbol }} {{ number_format($method->total, 2) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalCollected > 0 ? ($method->total / $totalCollected) * 100 : 0 }}%; background: linear-gradient(135deg, #8b5cf6, #7c3aed);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No payment data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2 text-primary"></i>Revenue by Department</h5>
                </div>
                <div class="card-body">
                    @if($byDepartment->count() > 0)
                        @foreach($byDepartment as $dept)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ $dept->department_name ?? 'N/A' }}</span>
                                <span class="badge bg-primary rounded-pill px-3 py-2">{{ $currencySymbol }} {{ number_format($dept->total, 2) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalCollected > 0 ? ($dept->total / $totalCollected) * 100 : 0 }}%; background: linear-gradient(135deg, #3b82f6, #2563eb);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No department revenue data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-info"></i>Payment Records</h5>
                </div>
                <div class="card-body p-0">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Patient</th>
                                        <th class="px-4 py-3 fw-semibold">Invoice #</th>
                                        <th class="px-4 py-3 fw-semibold">Method</th>
                                        <th class="px-4 py-3 fw-semibold text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-4 py-3">{{ $payment->payment_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $payment->invoice->patient->full_name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                            <td class="px-4 py-3"><span class="badge rounded-pill bg-light text-dark">{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</span></td>
                                            <td class="px-4 py-3 text-end fw-bold" style="color: #10b981;">{{ $currencySymbol }} {{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">{{ $payments->withQueryString()->links() }}</div>
                    @else
                        <p class="text-muted text-center py-5">No payment records found for the selected period.</p>
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
