@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        Manage E-Prescription Templates
                    </h2>
                    <p class="text-muted mb-0">Create and manage prescription templates</p>
                </div>
                <a href="{{ route('hms.prescriptions.e-prescription.create-template') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create Template
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($templates as $template)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $template->name }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $template->description ?? 'No description' }}</p>
                        <p><small><strong>Category:</strong> {{ $template->category ?? 'General' }}</small></p>
                        <p><small><strong>Used:</strong> {{ $template->usage_count }} times</small></p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    No templates found. <a href="{{ route('hms.prescriptions.e-prescription.create-template') }}">Create one</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $templates->links() }}
        </div>
    </div>
</div>
@endsection

