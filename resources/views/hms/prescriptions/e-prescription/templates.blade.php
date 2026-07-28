@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-prescription text-primary mr-2"></i>
                        E-Prescription Templates
                    </h2>
                    <p class="text-muted mb-0">Select a template to create a digital prescription</p>
                </div>
                <a href="{{ route('hms.prescriptions.e-prescription.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Create New E-Prescription
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($templates as $template)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $template->name }}</h5>
                    </div>
                    <div class="card-body">
                        @if($template->description)
                            <p class="text-muted">{{ $template->description }}</p>
                        @endif
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $template->category ?? 'General' }}
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Used {{ $template->usage_count }} times
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('hms.prescriptions.e-prescription.create', ['template_id' => $template->id]) }}" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-file-prescription mr-2"></i> Use Template
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    No templates available. <a href="{{ route('hms.prescriptions.e-prescription.manage-templates') }}">Create one</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

