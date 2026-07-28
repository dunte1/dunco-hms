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
                        {{ $template->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ $template->description ?? 'No description' }}</p>
                </div>
                <div>
                    <a href="{{ route('hms.reports.custom-builder.edit', $template) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.reports.custom-builder.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Template Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Template Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Category:</strong> {{ $template->category ?? 'Uncategorized' }}</p>
                            <p><strong>Table:</strong> {{ $template->config['table'] ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                @if($template->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </p>
                            @if($template->is_premium)
                                <p><strong>Type:</strong> <span class="badge badge-warning">Premium</span></p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Usage Count:</strong> {{ $template->usage_count }}</p>
                            <p><strong>Last Run:</strong> 
                                {{ $template->last_run_at ? $template->last_run_at->diffForHumans() : 'Never' }}
                            </p>
                            <p><strong>Created:</strong> {{ $template->created_at->format('M d, Y') }}</p>
                            @if($template->creator)
                                <p><strong>Created By:</strong> {{ $template->creator->name }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($template->description)
                        <hr>
                        <p><strong>Description:</strong></p>
                        <p>{{ $template->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Report Configuration -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Report Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Selected Fields:</strong>
                            <ul class="list-unstyled mt-2">
                                @if(isset($template->config['fields']))
                                    @foreach($template->config['fields'] as $field)
                                        <li><i class="fas fa-check text-success mr-2"></i>{{ $field }}</li>
                                    @endforeach
                                @else
                                    <li class="text-muted">All fields</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <strong>Sorting:</strong>
                            <p class="mt-2">
                                Order by: <code>{{ $template->config['order_by'] ?? 'id' }}</code>
                                ({{ $template->config['order_direction'] ?? 'asc' }})
                            </p>
                            @if(isset($template->config['group_by']))
                                <p>Group by: <code>{{ $template->config['group_by'] }}</code></p>
                            @endif
                            @if(isset($template->config['limit']))
                                <p>Limit: <code>{{ $template->config['limit'] }}</code> records</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Report -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Generate Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.reports.custom-builder.generate', $template) }}" method="POST" id="generateForm">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date From</label>
                                <input type="date" name="filters[date_from]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date To</label>
                                <input type="date" name="filters[date_to]" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Export Format</label>
                                <select name="format" class="form-control">
                                    <option value="html">View in Browser</option>
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-export mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.reports.custom-builder.duplicate', $template) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-copy mr-2"></i> Duplicate Template
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-warning btn-block mb-2" data-toggle="modal" data-target="#scheduleModal">
                        <i class="fas fa-clock mr-2"></i> Schedule Report
                    </button>
                    
                    <form action="{{ route('hms.reports.custom-builder.destroy', $template) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this template?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-2"></i> Delete Template
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h2 class="text-primary">{{ $template->usage_count }}</h2>
                        <p class="text-muted mb-0">Times Used</p>
                    </div>
                    @if($template->last_run_at)
                        <div class="text-center">
                            <small class="text-muted">Last run: {{ $template->last_run_at->format('M d, Y H:i') }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Report</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('hms.reports.custom-builder.schedule', $template) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Frequency</label>
                        <select name="schedule_frequency" class="form-control" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recipient Email</label>
                        <input type="email" name="recipient_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-control" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

