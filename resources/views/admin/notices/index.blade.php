@extends('admin.layouts.app')

@section('title', 'Notice Board - Announcements')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); box-shadow: 0 10px 30px rgba(6, 182, 212, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-clipboard me-3"></i>Notice Board - Announcements
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item text-white active">Notices</li>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Notices</h6>
                            <h2 class="mb-0 fw-bold" style="color: #06b6d4;">{{ $totalNotices ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clipboard text-white fs-4"></i>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Published</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $publishedNotices ?? 0 }}</h2>
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
        <div class="col-xl-4 col-md-4">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Created Today</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $todayNotices ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-day text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <span class="badge bg-cyan-subtle text-cyan px-3 py-2 me-3">
                                <i class="fas fa-list me-1"></i>
                            </span>
                            All Notices
                        </h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($notices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Title</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Published Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Created</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notices as $notice)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $notice->title }}</div>
                                                @if($notice->body)
                                                    <small class="text-muted">{{ Str::limit($notice->body, 60) }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    @if($notice->published_at)
                                                        <i class="far fa-calendar me-1 text-success"></i>
                                                        {{ \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">Not published</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $notice->published_at ? '#d1fae5' : '#fef3c7' }}; 
                                                             color: {{ $notice->published_at ? '#065f46' : '#78350f' }};">
                                                    <i class="fas fa-{{ $notice->published_at ? 'check' : 'clock' }}-circle me-1"></i>
                                                    {{ $notice->published_at ? 'Published' : 'Draft' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="tooltip" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash"></i>
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
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);">
                                    <i class="fas fa-clipboard" style="font-size: 3rem; color: #06b6d4;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Notices</h4>
                            <p class="text-muted mb-4">Create your first notice using the form on the right</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Create Notice Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-cyan-subtle text-cyan px-3 py-2 me-3">
                            <i class="fas fa-plus me-1"></i>
                        </span>
                        Create Notice
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.notices.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Title <span class="text-danger">*</span></label>
                            <input name="title" class="form-control form-control-lg" required placeholder="Enter notice title" />
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Body/Content</label>
                            <textarea name="body" rows="6" class="form-control" placeholder="Enter notice content (optional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Publish Date</label>
                            <input type="date" name="published_at" class="form-control form-control-lg" />
                            <small class="text-muted">Leave empty to create as draft</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none;">
                            <i class="fas fa-save me-2"></i>Save Notice
                        </button>
                    </form>
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


