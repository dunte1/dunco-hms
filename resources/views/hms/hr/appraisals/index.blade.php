@extends('admin.layouts.app')

@section('title', 'Performance Appraisals')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-chart-line me-3"></i>Performance Appraisals
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›'; background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Performance Appraisals</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.appraisals.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus-circle me-2"></i>New Appraisal
                        </a>
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
                            <h6 class="text-muted text-uppercase mb-2 small">Total Appraisals</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $appraisals->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-line text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Approved</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ \App\Models\PerformanceAppraisal::where('status', 'approved')->count() }}</h2>
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
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Pending</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ \App\Models\PerformanceAppraisal::whereIn('status', ['draft', 'submitted'])->count() }}</h2>
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
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Promotions Rec.</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ef4444;">{{ \App\Models\PerformanceAppraisal::where('promotion_recommended', true)->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-trophy text-white fs-4"></i>
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
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Appraisals List
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search employees...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                <strong>{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($appraisals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 20%;">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 15%;">Review Period</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Score</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Rating</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 13%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appraisals as $appraisal)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $appraisal->employee->first_name }} {{ $appraisal->employee->last_name }}</h6>
                                                        <small class="text-muted">{{ $appraisal->employee->position }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    {{ $appraisal->review_period }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $appraisal->appraisal_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($appraisal->overall_score)
                                                    <span class="badge rounded-pill px-3 py-2 fw-bold" 
                                                          style="background: {{ $appraisal->overall_score >= 80 ? '#d1fae5' : ($appraisal->overall_score >= 60 ? '#fef3c7' : '#fee2e2') }}; 
                                                                 color: {{ $appraisal->overall_score >= 80 ? '#065f46' : ($appraisal->overall_score >= 60 ? '#78350f' : '#991b1b') }};">
                                                        {{ $appraisal->overall_score }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($appraisal->overall_rating)
                                                    <span class="badge rounded-pill px-3 py-2">
                                                        {{ ucfirst($appraisal->overall_rating) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge px-3 py-2
                                                    @if($appraisal->status == 'approved') bg-success
                                                    @elseif($appraisal->status == 'reviewed') bg-info
                                                    @elseif($appraisal->status == 'submitted') bg-warning
                                                    @elseif($appraisal->status == 'draft') bg-secondary
                                                    @else bg-dark
                                                    @endif">
                                                    {{ ucfirst($appraisal->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('hms.hr.appraisals.show', $appraisal) }}" 
                                                       class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.appraisals.edit', $appraisal) }}" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteAppraisal({{ $appraisal->id }})" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($appraisals->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $appraisals->firstItem() }}</strong> to <strong>{{ $appraisals->lastItem() }}</strong> of <strong>{{ $appraisals->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $appraisals->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e9d5ff 0%, #ddd6fe 100%);">
                                    <i class="fas fa-chart-line" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Appraisals Found</h4>
                            <p class="text-muted mb-4">Start by creating your first performance appraisal.</p>
                            <a href="{{ route('hms.hr.appraisals.create') }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>Create First Appraisal
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
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});

function deleteAppraisal(id) {
    if (confirm('Are you sure you want to delete this appraisal?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/hms/hr/appraisals/' + id;
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        var methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection

