@extends('admin.layouts.app')

@section('title', 'Staff Notices')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); box-shadow: 0 10px 30px rgba(20, 184, 166, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-tie me-3"></i>Staff Notices
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}" class="text-white-50">Notices</a></li>
                                <li class="breadcrumb-item text-white active">Staff Notices</li>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Published</h6>
                            <h2 class="mb-0 fw-bold" style="color: #14b8a6;">{{ $totalNotices ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bullhorn text-white fs-4"></i>
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
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $todayNotices ?? 0 }}</h2>
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
                            <h6 class="text-muted text-uppercase mb-2 small">This Month</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $thisMonthNotices ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar text-white fs-4"></i>
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
                            <span class="badge bg-teal-subtle text-teal px-3 py-2 me-3">
                                <i class="fas fa-list me-1"></i>
                            </span>
                            All Staff Notices
                        </h5>
                        <a href="{{ route('admin.notices.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); border: none;">
                            <i class="fas fa-plus me-2"></i>Create Notice
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($notices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Title</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Content</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Published</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notices as $notice)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $notice->title }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-muted">{{ Str::limit($notice->body, 80) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    <i class="far fa-calendar me-1 text-teal"></i>
                                                    {{ \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($notice->published_at)->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            data-bs-toggle="tooltip" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($notices->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $notices->firstItem() }}</strong> to <strong>{{ $notices->lastItem() }}</strong> of <strong>{{ $notices->total() }}</strong> entries
                            </div>
                            <div>{{ $notices->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);">
                                    <i class="fas fa-user-tie" style="font-size: 3rem; color: #14b8a6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Staff Notices</h4>
                            <p class="text-muted mb-4">There are no published notices for staff at the moment.</p>
                            <a href="{{ route('admin.notices.index') }}" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); border: none;">
                                <i class="fas fa-plus-circle me-2"></i>Create Notice
                            </a>
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
