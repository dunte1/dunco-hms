@extends('admin.layouts.app')

@section('title', 'Bed Occupancy Report')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold"><i class="fas fa-bed me-3"></i>Bed Occupancy Report</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.moh-reports.index') }}" class="text-white-50">MOH Reports</a></li>
                                <li class="breadcrumb-item text-white active">Bed Occupancy</li>
                            </ol>
                        </nav>
                    </div>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Beds</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6366f1;">{{ number_format($totalBeds) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #6366f1, #4f46e5); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-bed text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Occupied</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ number_format($occupiedBeds) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-in-bed text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Available</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ number_format($availableBeds) }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Overall Occupancy</h6>
                            <h2 class="mb-0 fw-bold" style="color: {{ $overallOccupancy > 90 ? '#ef4444' : ($overallOccupancy > 70 ? '#f59e0b' : '#10b981') }};">{{ $overallOccupancy }}%</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, {{ $overallOccupancy > 90 ? '#ef4444' : ($overallOccupancy > 70 ? '#f59e0b' : '#10b981') }}, {{ $overallOccupancy > 90 ? '#dc2626' : ($overallOccupancy > 70 ? '#d97706' : '#059669') }}); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-pie text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-hospital me-2 text-indigo"></i>Ward-wise Occupancy</h5>
                </div>
                <div class="card-body p-0">
                    @if($wards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold">Ward</th>
                                        <th class="px-4 py-3 fw-semibold text-center">Total Beds</th>
                                        <th class="px-4 py-3 fw-semibold text-center">Occupied</th>
                                        <th class="px-4 py-3 fw-semibold text-center">Available</th>
                                        <th class="px-4 py-3 fw-semibold text-center">Occupancy Rate</th>
                                        <th class="px-4 py-3 fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wards as $ward)
                                        <tr>
                                            <td class="px-4 py-3 fw-bold text-dark">{{ $ward->name }}</td>
                                            <td class="px-4 py-3 text-center">{{ $ward->total_beds }}</td>
                                            <td class="px-4 py-3 text-center">{{ $ward->occupied_beds }}</td>
                                            <td class="px-4 py-3 text-center">{{ $ward->available_beds }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px; max-width: 100px;">
                                                        <div class="progress-bar {{ $ward->occupancy_rate > 90 ? 'bg-danger' : ($ward->occupancy_rate > 70 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $ward->occupancy_rate }}%"></div>
                                                    </div>
                                                    <span class="fw-bold">{{ $ward->occupancy_rate }}%</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill {{ $ward->occupancy_rate > 90 ? 'bg-danger' : ($ward->occupancy_rate > 70 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                    {{ $ward->occupancy_rate > 90 ? 'Critical' : ($ward->occupancy_rate > 70 ? 'High' : 'Normal') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-5">No ward data available.</p>
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
