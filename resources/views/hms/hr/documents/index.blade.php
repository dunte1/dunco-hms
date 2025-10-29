@extends('admin.layouts.app')

@section('title', 'Staff Documents Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-folder-open me-3"></i>Staff Documents Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Staff Documents</li>
                            </ol>
                        </nav>
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
                        <span class="badge bg-pink-subtle text-pink px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Employee Documents
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert" style="border-left: 4px solid #10b981 !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                <strong>{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Employee</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Document Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Title</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">File</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Uploaded</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $document->employee->full_name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $document->employee->employee_id ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    {{ $document->documentType->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="fw-medium">{{ $document->title }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file me-2 text-muted"></i>
                                                    <small class="text-muted">{{ $document->file_name }}</small>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">{{ $document->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge rounded-pill px-3 py-2" 
                                                      style="font-size: 0.85rem; 
                                                             background: {{ $document->status === 'active' ? '#d1fae5' : '#fee2e2' }}; 
                                                             color: {{ $document->status === 'active' ? '#065f46' : '#991b1b' }};">
                                                    <i class="fas fa-{{ $document->status === 'active' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                                    {{ ucfirst($document->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($documents->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $documents->firstItem() }}</strong> to <strong>{{ $documents->lastItem() }}</strong> of <strong>{{ $documents->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $documents->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);">
                                    <i class="fas fa-folder-open" style="font-size: 3rem; color: #ec4899;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Documents Found</h4>
                            <p class="text-muted mb-4">Employee documents will appear here once uploaded.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
