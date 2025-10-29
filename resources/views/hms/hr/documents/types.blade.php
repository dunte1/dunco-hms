@extends('admin.layouts.app')

@section('title', 'Document Types Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-tags me-3"></i>Document Types Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Document Types</li>
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
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        Document Types
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($documentTypes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Type Name</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Description</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Required</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Documents</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentTypes as $type)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="fw-bold">{{ $type->name }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-muted">{{ $type->description ?? 'No description' }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge {{ $type->is_required ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary' }} px-3 py-2">
                                                    {{ $type->is_required ? 'Required' : 'Optional' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    {{ $type->documents_count }} documents
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">{{ $type->created_at->format('M d, Y') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($documentTypes->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $documentTypes->firstItem() }}</strong> to <strong>{{ $documentTypes->lastItem() }}</strong> of <strong>{{ $documentTypes->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $documentTypes->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);">
                                    <i class="fas fa-tags" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Document Types Found</h4>
                            <p class="text-muted mb-4">Document types will be available here once configured.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
