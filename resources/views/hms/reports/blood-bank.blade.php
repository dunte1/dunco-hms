@extends('admin.layouts.app')

@section('title', 'Blood Bank Reports')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-tint me-3"></i>Blood Bank Reports
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reports.index') }}" class="text-white-50">Reports</a></li>
                                <li class="breadcrumb-item text-white active">Blood Bank Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-6 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Donors</h6>
                            <h2 class="mb-0 fw-bold" style="color: #dc2626;">{{ $totalDonors }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Donations</h6>
                            <h2 class="mb-0 fw-bold" style="color: #dc2626;">{{ $totalDonations }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-tint text-white fs-4"></i>
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
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: none;">
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
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Blood Donor Details
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($donors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Donor ID</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Name</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Blood Group</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Phone</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Donations</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Last Donation</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donors as $donor)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">#{{ $donor->donor_id }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $donor->full_name }}</h6>
                                                        <small class="text-muted">{{ $donor->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" style="font-size: 0.85rem; background: #fee2e2; color: #991b1b;">
                                                    <i class="fas fa-tint me-1"></i>{{ $donor->bloodGroup->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $donor->phone }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold text-dark">{{ $donor->donations->count() }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark">{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('M d, Y') : 'Never' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $donor->is_eligible ? '#d1fae5' : '#fee2e2' }}; 
                                                             color: {{ $donor->is_eligible ? '#065f46' : '#991b1b' }};">
                                                    <i class="fas fa-{{ $donor->is_eligible ? 'check-circle' : 'times-circle' }} me-1"></i>
                                                    {{ $donor->is_eligible ? 'Eligible' : 'Not Eligible' }}
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
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                                    <i class="fas fa-tint" style="font-size: 3rem; color: #dc2626;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Blood Donors Found</h4>
                            <p class="text-muted mb-4">No blood donors found for the selected date range.</p>
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
