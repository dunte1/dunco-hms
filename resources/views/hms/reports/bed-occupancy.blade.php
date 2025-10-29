@extends('admin.layouts.app')

@section('title', 'Bed Occupancy Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-bed me-3"></i>Bed Occupancy Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Bed Occupancy Report</li>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Beds</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $totalBeds }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bed text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Occupied</h6>
                            <h2 class="mb-0 fw-bold" style="color: #dc2626;">{{ $occupiedBeds }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-injured text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Available</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $availableBeds }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Occupancy Rate</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ number_format($occupancyRate, 1) }}%</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-percentage text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
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
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Bed Details
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($beds->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Bed Number</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Bed Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Room/Ward</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Floor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($beds as $bed)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">Bed #{{ $bed->bed_number }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.85rem; background: #e9d5ff; color: #6b21a8;">
                                                    <i class="fas fa-bed me-1"></i>{{ $bed->bedType->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $bed->status === 'occupied' ? '#fee2e2' : ($bed->status === 'maintenance' ? '#fef3c7' : '#d1fae5') }}; 
                                                             color: {{ $bed->status === 'occupied' ? '#991b1b' : ($bed->status === 'maintenance' ? '#78350f' : '#065f46') }};">
                                                    <i class="fas fa-{{ $bed->status === 'occupied' ? 'user-injured' : ($bed->status === 'maintenance' ? 'tools' : 'check-circle') }} me-1"></i>
                                                    {{ ucfirst($bed->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($bed->status === 'occupied' && $bed->currentAssignment)
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $bed->currentAssignment->patient->full_name ?? 'N/A' }}</h6>
                                                            <small class="text-muted">{{ $bed->currentAssignment->patient->patient_no ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $bed->room_number ?? 'N/A' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ $bed->floor ?? 'N/A' }}</small>
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
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);">
                                    <i class="fas fa-bed" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Beds Found</h4>
                            <p class="text-muted mb-4">No bed data available in the system.</p>
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
