@extends('admin.layouts.app')

@section('title', 'Hospital Branches')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-building me-3"></i>Hospital Branches
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Branches</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('hms.settings.branches.create') }}" class="btn btn-light btn-lg" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3);">
                        <i class="fas fa-plus me-2"></i>Add Branch
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Grid -->
    <div class="row g-4">
        @forelse($branches as $branch)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">{{ $branch->name }}</h6>
                            @if($branch->is_main_branch)
                                <span class="badge bg-primary-subtle text-primary px-2 py-1">Main Branch</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Branch Code</small>
                            <span class="fw-bold text-dark">{{ $branch->branch_code }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Address</small>
                            <span class="text-dark">{{ Str::limit($branch->address, 60) }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Contact</small>
                            <div><i class="fas fa-phone text-success me-1"></i> {{ $branch->phone }}</div>
                            <div><i class="fas fa-envelope text-primary me-1"></i> {{ $branch->email }}</div>
                        </div>
                        @if($branch->manager_name)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Manager</small>
                            <span class="text-dark">{{ $branch->manager_name }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                                <i class="fas fa-building" style="font-size: 3rem; color: #10b981;"></i>
                            </div>
                        </div>
                        <h4 class="text-dark mb-3 fw-bold">No Hospital Branches</h4>
                        <p class="text-muted mb-4">Add your first hospital branch to get started</p>
                        <a href="{{ route('hms.settings.branches.create') }}" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                            <i class="fas fa-plus-circle me-2"></i>Add Branch
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(16, 185, 129, 0.2) !important;
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
