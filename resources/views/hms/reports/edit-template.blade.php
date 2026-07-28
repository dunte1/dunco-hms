@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-edit text-primary mr-2"></i>
                        Edit Template: {{ $template->name }}
                    </h2>
                    <p class="text-muted mb-0">Update your report template configuration</p>
                </div>
                <a href="{{ route('hms.reports.custom-builder.show', $template) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <form id="templateForm" action="{{ route('hms.reports.custom-builder.update', $template) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required 
                                           value="{{ $template->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="patient" {{ $template->category == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="billing" {{ $template->category == 'billing' ? 'selected' : '' }}>Billing</option>
                                        <option value="lab" {{ $template->category == 'lab' ? 'selected' : '' }}>Laboratory</option>
                                        <option value="pharmacy" {{ $template->category == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                        <option value="hr" {{ $template->category == 'hr' ? 'selected' : '' }}>HR</option>
                                        <option value="financial" {{ $template->category == 'financial' ? 'selected' : '' }}>Financial</option>
                                        <option value="other" {{ $template->category == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $template->description }}</textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_premium" value="1" 
                                   id="isPremium" {{ $template->is_premium ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPremium">Premium Feature</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   id="isActive" {{ $template->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>

                <!-- Data Source -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Data Source</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Table</label>
                            <input type="text" class="form-control" value="{{ $template->config['table'] ?? 'N/A' }}" disabled>
                            <small class="form-text text-muted">Table cannot be changed after creation</small>
                        </div>
                        <div>
                            <label class="form-label">Selected Fields</label>
                            <ul class="list-unstyled">
                                @if(isset($template->config['fields']))
                                    @foreach($template->config['fields'] as $field)
                                        <li><i class="fas fa-check text-success mr-2"></i>{{ $field }}</li>
                                    @endforeach
                                @else
                                    <li class="text-muted">All fields</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Configuration</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="config[table]" value="{{ $template->config['table'] ?? '' }}">
                        <input type="hidden" name="config[fields]" value="{{ json_encode($template->config['fields'] ?? ['*']) }}">
                        <input type="hidden" name="config[group_by]" value="{{ $template->config['group_by'] ?? '' }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Order By</label>
                                    <input type="text" name="config[order_by]" class="form-control" 
                                           value="{{ $template->config['order_by'] ?? 'id' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Direction</label>
                                    <select name="config[order_direction]" class="form-control">
                                        <option value="asc" {{ ($template->config['order_direction'] ?? 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        <option value="desc" {{ ($template->config['order_direction'] ?? 'asc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Limit</label>
                                    <input type="number" name="config[limit]" class="form-control" 
                                           value="{{ $template->config['limit'] ?? 100 }}" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save mr-2"></i> Update Template
                        </button>
                        <a href="{{ route('hms.reports.custom-builder.show', $template) }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

