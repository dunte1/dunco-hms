@extends('admin.layouts.app')

@section('title', 'Maternal Health Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-baby me-3"></i>Maternal Health Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Maternal Health</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.moh-reports.pdf', 'maternal-health') }}?{{ request()->query() }}" class="btn btn-light">
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
                    <form method="GET" action="{{ route('hms.moh-reports.maternal-health') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('hms.moh-reports.maternal-health') }}" class="btn btn-outline-secondary"><i class="fas fa-redo me-1"></i> Reset</a>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Births</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ec4899;">{{ number_format($totalBirths) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ec4899, #db2777); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-baby text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Delivery Types</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ number_format($byDeliveryType->count()) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                        <div>
                            <h6 class="text-muted text-uppercase mb-2 small">With Complications</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ number_format($withComplications) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Maternal Deaths</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ number_format($maternalDeaths) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heart-broken text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-pink"></i>Delivery Types</h5>
                </div>
                <div class="card-body">
                    @if($byDeliveryType->count() > 0)
                        @foreach($byDeliveryType as $type)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $type->delivery_type ?? 'N/A')) }}</span>
                                <span class="badge bg-pink rounded-pill px-3 py-2" style="background: #ec4899;">{{ number_format($type->count) }}</span>
                            </div>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalBirths > 0 ? ($type->count / $totalBirths) * 100 : 0 }}%; background: linear-gradient(135deg, #ec4899, #db2777);"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No delivery type data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-info"></i>Birth Records</h5>
                </div>
                <div class="card-body p-0">
                    @if($births->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Date</th>
                                        <th class="px-4 py-3 fw-semibold">Mother</th>
                                        <th class="px-4 py-3 fw-semibold">Delivery Type</th>
                                        <th class="px-4 py-3 fw-semibold">Complications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($births as $birth)
                                        <tr>
                                            <td class="px-4 py-3">{{ $birth->birth_date?->format('d M Y') }}</td>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $birth->mother_name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3"><span class="badge rounded-pill" style="background: #fce7f3; color: #9d174d;">{{ ucfirst(str_replace('_', ' ', $birth->delivery_type ?? 'N/A')) }}</span></td>
                                            <td class="px-4 py-3">{{ $birth->complications ?? 'None' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3">{{ $births->withQueryString()->links() }}</div>
                    @else
                        <p class="text-muted text-center py-5">No birth records found for the selected period.</p>
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
