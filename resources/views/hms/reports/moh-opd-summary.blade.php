@extends('admin.layouts.app')

@section('title', 'OPD Summary Report')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-stethoscope me-3"></i>OPD Summary Report
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">OPD Summary</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.generate-pdf', 'opd-summary') }}?{{ request()->query() }}" class="btn btn-light">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('hms.moh-reports.opd-summary') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('hms.moh-reports.opd-summary') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Visits</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ number_format($totalVisits) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">KES {{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Visit Types</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $byType->count() }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-layer-group text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Departments</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $byDepartment->count() }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Type Breakdown & Department Breakdown -->
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-tags me-2 text-primary"></i>Visits by Type</h5>
                </div>
                <div class="card-body">
                    @if($byType->count() > 0)
                        @foreach($byType as $type)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $type->visit_type ?? 'N/A')) }}</span>
                                <span class="badge bg-primary rounded-pill px-3 py-2">{{ number_format($type->count) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalVisits > 0 ? ($type->count / $totalVisits) * 100 : 0 }}%; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No visit type data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2 text-warning"></i>Visits by Department</h5>
                </div>
                <div class="card-body">
                    @if($byDepartment->count() > 0)
                        @foreach($byDepartment as $dept)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ $dept->department_name ?? 'N/A' }}</span>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ number_format($dept->count) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalVisits > 0 ? ($dept->count / $totalVisits) * 100 : 0 }}%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No department data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Diagnoses & Visits Table -->
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-diagnoses me-2 text-danger"></i>Top Diagnoses</h5>
                </div>
                <div class="card-body">
                    @if($topDiagnoses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">#</th>
                                        <th class="fw-semibold">Diagnosis</th>
                                        <th class="fw-semibold text-end">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topDiagnoses as $index => $diag)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold text-dark">{{ $diag->diagnosis }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-danger rounded-pill">{{ number_format($diag->count) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No diagnosis data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-info"></i>OPD Visits</h5>
                </div>
                <div class="card-body p-0">
                    @if($visits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Patient</th>
                                        <th class="px-4 py-3 fw-semibold">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold">Type</th>
                                        <th class="px-4 py-3 fw-semibold">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-end">Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visits as $visit)
                                        <tr>
                                            <td class="px-4 py-3">{{ $visit->visit_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">{{ $visit->patient->first_name ?? '' }} {{ $visit->patient->last_name ?? '' }}</span>
                                            </td>
                                            <td class="px-4 py-3">Dr. {{ $visit->doctor->first_name ?? '' }} {{ $visit->doctor->last_name ?? '' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.8rem;">
                                                    {{ ucfirst(str_replace('_', ' ', $visit->visit_type ?? 'N/A')) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.8rem; background: {{ $visit->status === 'completed' ? '#d1fae5' : '#fef3c7' }}; color: {{ $visit->status === 'completed' ? '#065f46' : '#78350f' }};">
                                                    {{ $visit->getStatusLabelAttribute() }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-end fw-bold">KES {{ number_format($visit->consultation_fee ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">
                            {{ $visits->withQueryString()->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center py-5">No OPD visits found for the selected period.</p>
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
@endsection
