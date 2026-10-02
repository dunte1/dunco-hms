@extends('admin.layouts.app')

@section('title', 'Disease Surveillance Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-virus me-3"></i>Disease Surveillance Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Disease Surveillance</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.pdf', 'disease-surveillance') }}?{{ request()->query() }}" class="btn btn-light">
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
                    <form method="GET" action="{{ route('hms.moh-reports.disease-surveillance') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('hms.moh-reports.disease-surveillance') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Diagnoses</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ number_format($diseases->sum('count')) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-virus text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Unique Diseases</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ number_format($diseases->count()) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tags text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-danger"></i>Disease Distribution</h5>
                </div>
                <div class="card-body">
                    @if($diseases->count() > 0)
                        @foreach($diseases as $disease)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ $disease->diagnosis }}</span>
                                <span class="badge bg-danger rounded-pill px-3 py-2">{{ number_format($disease->count) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $diseases->sum('count') > 0 ? ($disease->count / $diseases->sum('count')) * 100 : 0 }}%; background: linear-gradient(135deg, #ef4444, #dc2626);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No disease data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Monthly Trend</h5>
                </div>
                <div class="card-body">
                    @if($trend->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">Month</th>
                                        <th class="fw-semibold">Disease</th>
                                        <th class="fw-semibold text-end">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trend->take(20) as $row)
                                        <tr>
                                            <td>{{ $row->month }}</td>
                                            <td class="fw-semibold text-dark">{{ $row->diagnosis }}</td>
                                            <td class="text-end"><span class="badge bg-primary rounded-pill">{{ number_format($row->count) }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No trend data available.</p>
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
