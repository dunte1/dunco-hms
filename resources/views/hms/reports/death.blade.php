@extends('admin.layouts.app')

@section('title', 'Death Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); box-shadow: 0 10px 30px rgba(107, 114, 128, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-cross me-3"></i>Death Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Death Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Card -->
    <div class="row g-3 mb-4">
        <div class="col-xl-12 col-md-12">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Deaths</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6b7280;">{{ $deathReports->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-cross text-white fs-4"></i>
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
                        <span class="badge bg-gray-subtle text-gray px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Death Records
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($deathReports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Report #</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Deceased Name</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Date of Death</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Cause of Death</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Age</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Gender</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deathReports as $report)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">#{{ $report->report_number ?? $report->id }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $report->deceased_name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ \Carbon\Carbon::parse($report->death_date)->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ Str::limit($report->cause_of_death ?? 'N/A', 30) }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $report->age ?? 'N/A' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $report->gender === 'male' ? '#dbeafe' : '#fce7f3' }}; 
                                                             color: {{ $report->gender === 'male' ? '#1e40af' : '#9f1239' }};">
                                                    <i class="fas fa-{{ $report->gender === 'male' ? 'mars' : 'venus' }} me-1"></i>
                                                    {{ ucfirst($report->gender) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success" style="font-size: 0.85rem;">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Complete
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($deathReports->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $deathReports->firstItem() }}</strong> to <strong>{{ $deathReports->lastItem() }}</strong> of <strong>{{ $deathReports->total() }}</strong> entries
                            </div>
                            <div>{{ $deathReports->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
                                    <i class="fas fa-cross" style="font-size: 3rem; color: #6b7280;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Death Records Found</h4>
                            <p class="text-muted mb-4">No death records available in the system.</p>
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
