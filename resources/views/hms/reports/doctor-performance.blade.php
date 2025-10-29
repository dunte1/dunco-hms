@extends('admin.layouts.app')

@section('title', 'Doctor Performance Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); box-shadow: 0 10px 30px rgba(20, 184, 166, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-md me-3"></i>Doctor Performance Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Doctor Performance Report</li>
                            </ol>
                        </nav>
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
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); border: none;">
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
                        <span class="badge bg-teal-subtle text-teal px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Doctor Performance Metrics
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($doctorPerformance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Rank</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Total Appointments</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Completed</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Cancelled</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Success Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doctorPerformance->take(10) as $index => $performance)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold" style="width: 40px; height: 40px; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);">
                                                            <i class="fas fa-user-md text-white fs-5"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $performance['doctor']->full_name }}</h6>
                                                        <small class="text-muted">{{ $performance['doctor']->department->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">{{ $performance['total_appointments'] }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="background: #d1fae5; color: #065f46;">
                                                    <i class="fas fa-check-circle me-1"></i>{{ $performance['completed_appointments'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="background: #fee2e2; color: #991b1b;">
                                                    <i class="fas fa-times-circle me-1"></i>{{ $performance['cancelled_appointments'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $successRate = $performance['total_appointments'] > 0 
                                                        ? ($performance['completed_appointments'] / $performance['total_appointments']) * 100 
                                                        : 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 me-2">
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar" 
                                                                 style="width: {{ $successRate }}%; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);" 
                                                                 role="progressbar" 
                                                                 aria-valuenow="{{ $successRate }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="fw-bold" style="color: #14b8a6;">{{ number_format($successRate, 1) }}%</span>
                                                </div>
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
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);">
                                    <i class="fas fa-user-md" style="font-size: 3rem; color: #14b8a6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Performance Data Found</h4>
                            <p class="text-muted mb-4">No doctor performance data found for the selected date range.</p>
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
        transform: translateX(5px);
        transition: all 0.3s ease;
    }

    * {
        transition: all 0.3s ease;
    }
</style>
@endsection
