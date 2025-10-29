@extends('admin.layouts.app')

@section('title', 'Online Appointment Requests')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-globe me-3"></i>Online Appointment Requests
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item text-white active">Appointment Requests</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-4">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Requests</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $totalRequests ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-inbox text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Today</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $todayRequests ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-day text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Existing Patients</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $existingPatients ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-check text-white fs-4"></i>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <span class="badge bg-orange-subtle text-orange px-3 py-2 me-3">
                                <i class="fas fa-list me-1"></i>
                            </span>
                            All Appointment Requests
                        </h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient Name</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Contact</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Preferred Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Requested</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $request->patient_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <div class="fw-medium text-dark">{{ $request->email }}</div>
                                                    @if($request->phone)
                                                        <small class="text-muted">
                                                            <i class="fas fa-phone me-1"></i>{{ $request->phone }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-medium text-dark">{{ $request->doctor_name ?? 'Any Doctor' }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    <i class="far fa-calendar me-1 text-primary"></i>
                                                    {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $request->is_existing_patient ? '#d1fae5' : '#fef3c7' }}; 
                                                             color: {{ $request->is_existing_patient ? '#065f46' : '#78350f' }};">
                                                    <i class="fas fa-{{ $request->is_existing_patient ? 'user-check' : 'user-plus' }} me-1"></i>
                                                    {{ $request->is_existing_patient ? 'Existing Patient' : 'New Patient' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            data-bs-toggle="tooltip" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            data-bs-toggle="tooltip" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($requests->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $requests->firstItem() }}</strong> to <strong>{{ $requests->lastItem() }}</strong> of <strong>{{ $requests->total() }}</strong> entries
                            </div>
                            <div>{{ $requests->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #f59e0b;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Appointment Requests</h4>
                            <p class="text-muted mb-4">There are no appointment requests at the moment.</p>
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

    .btn-outline-primary:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-outline-success:hover {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
    }

    * {
        transition: all 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection


