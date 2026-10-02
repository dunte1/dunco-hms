@extends('admin.layouts.app')

@section('title', 'IPD Summary Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-procedures me-3"></i>IPD Summary Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">IPD Summary</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.pdf', 'ipd-summary') }}?{{ request()->query() }}" class="btn btn-light">
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
                    <form method="GET" action="{{ route('hms.moh-reports.ipd-summary') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('hms.moh-reports.ipd-summary') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Admissions</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ number_format($totalAdmissions) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-procedures text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Discharges</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ number_format($totalDischarges) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Avg. Length of Stay</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ number_format($averageLengthOfStay, 1) }} days</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Bed Occupancy</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $occupancyRate }}%</h2>
                            <small class="text-muted">{{ number_format($bedOccupancy) }} / {{ number_format($totalBeds) }} beds</small>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6, #2563eb); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-bed text-white fs-4"></i>
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
                                            <td class="text-end"><span class="badge bg-danger rounded-pill">{{ number_format($diag->count) }}</span></td>
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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-info"></i>IPD Admissions</h5>
                </div>
                <div class="card-body p-0">
                    @if($admissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Patient</th>
                                        <th class="px-4 py-3 fw-semibold">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold">Ward</th>
                                        <th class="px-4 py-3 fw-semibold">Diagnosis</th>
                                        <th class="px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admissions as $admission)
                                        <tr>
                                            <td class="px-4 py-3">{{ $admission->admission_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $admission->patient->first_name ?? '' }} {{ $admission->patient->last_name ?? '' }}</td>
                                            <td class="px-4 py-3">Dr. {{ $admission->doctor->first_name ?? '' }} {{ $admission->doctor->last_name ?? '' }}</td>
                                            <td class="px-4 py-3">{{ $admission->ward->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">{{ $admission->diagnosis ?? 'N/A' }}</td>
                                            <td class="px-4 py-3">
                                                @if($admission->discharge_date)
                                                    <span class="badge bg-success rounded-pill">Discharged</span>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">{{ $admissions->withQueryString()->links() }}</div>
                    @else
                        <p class="text-muted text-center py-5">No IPD admissions found for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important; }
    .table tbody tr:hover { background-color: #f8fafc !important; }
</style>
@endsection
