@extends('admin.layouts.app')

@section('title', 'Pharmacy Consumption Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-pills me-3"></i>Pharmacy Consumption Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Pharmacy Consumption</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.pdf', 'pharmacy-consumption') }}?{{ request()->query() }}" class="btn btn-light">
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
                    <form method="GET" action="{{ route('hms.moh-reports.pharmacy-consumption') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('hms.moh-reports.pharmacy-consumption') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Prescriptions</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ number_format($totalPrescriptions) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-prescription text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Medicines Dispensed</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ number_format($totalMedicinesDispensed) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6, #2563eb); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-pills text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Stock Quantity</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ number_format($stockValue) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-boxes text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Expiring (30 days)</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ number_format($expiringMedicines) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-trophy me-2 text-warning"></i>Top Medicines</h5>
                </div>
                <div class="card-body">
                    @if($topMedicines->count() > 0)
                        @foreach($topMedicines as $index => $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ $index + 1 }}. {{ $item->medicine->name ?? 'Medicine #' . $item->medicine_id }}</span>
                                <span class="badge bg-success rounded-pill px-3 py-2">{{ number_format($item->total_qty) }} units</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $topMedicines->first()->total_qty > 0 ? ($item->total_qty / $topMedicines->first()->total_qty) * 100 : 0 }}%; background: linear-gradient(135deg, #10b981, #059669);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No medicine consumption data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-info"></i>Recent Prescriptions</h5>
                </div>
                <div class="card-body p-0">
                    @if($prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Patient</th>
                                        <th class="px-4 py-3 fw-semibold">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold">Items</th>
                                        <th class="px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $rx)
                                        <tr>
                                            <td class="px-4 py-3">{{ $rx->prescription_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $rx->patient->first_name ?? '' }} {{ $rx->patient->last_name ?? '' }}</td>
                                            <td class="px-4 py-3">Dr. {{ $rx->doctor->first_name ?? '' }} {{ $rx->doctor->last_name ?? '' }}</td>
                                            <td class="px-4 py-3">{{ $rx->items->count() }} items</td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill {{ $rx->status === 'dispensed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($rx->status ?? 'Pending') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">{{ $prescriptions->withQueryString()->links() }}</div>
                    @else
                        <p class="text-muted text-center py-5">No prescriptions found for the selected period.</p>
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
