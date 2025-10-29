@extends('admin.layouts.app')

@section('title', 'Lab Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-vial me-3"></i>Lab Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Lab Report</li>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Requests</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $totalRequests }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-vial text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Completed</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $completedRequests }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Pending</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $pendingRequests }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Revenue</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">${{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-dollar-sign text-white fs-4"></i>
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
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border: none;">
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
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Lab Request Details
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($labRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Request #</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Total</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($labRequests as $request)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">#{{ $request->request_number ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $request->patient->full_name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $request->patient->patient_no ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $request->doctor->full_name ?? 'N/A' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">${{ number_format($request->total_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $request->status === 'completed' ? '#d1fae5' : ($request->status === 'pending' ? '#fef3c7' : '#fee2e2') }}; 
                                                             color: {{ $request->status === 'completed' ? '#065f46' : ($request->status === 'pending' ? '#78350f' : '#991b1b') }};">
                                                    <i class="fas fa-{{ $request->status === 'completed' ? 'check-circle' : ($request->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                                    {{ ucfirst($request->status) }}
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
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);">
                                    <i class="fas fa-vial" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Lab Requests Found</h4>
                            <p class="text-muted mb-4">No lab requests found for the selected date range.</p>
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
