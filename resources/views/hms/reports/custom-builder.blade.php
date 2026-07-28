@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-tools text-primary mr-2"></i>
                        Custom Report Builder
                    </h2>
                    <p class="text-muted mb-0">Create and manage custom report templates</p>
                </div>
                <a href="{{ route('hms.reports.custom-builder.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create New Template
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('hms.reports.custom-builder.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="all">All</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search mr-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row">
        @forelse($templates as $template)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $template->name }}</h5>
                        @if($template->is_premium)
                            <span class="badge badge-warning">Premium</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($template->description)
                            <p class="text-muted small">{{ Str::limit($template->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $template->category ?? 'Uncategorized' }}
                            </small>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Used {{ $template->usage_count }} times
                            </small>
                        </div>
                        
                        @if($template->last_run_at)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    Last run: {{ $template->last_run_at->diffForHumans() }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('hms.reports.custom-builder.show', $template) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('hms.reports.custom-builder.edit', $template) }}" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('hms.reports.custom-builder.duplicate', $template) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-info" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </form>
                            <form action="{{ route('hms.reports.custom-builder.destroy', $template) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this template?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4>No Report Templates Found</h4>
                        <p class="text-muted">Create your first custom report template to get started.</p>
                        <a href="{{ route('hms.reports.custom-builder.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i> Create Template
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $templates->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

