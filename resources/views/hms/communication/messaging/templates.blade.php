@extends('admin.layouts.app')

@section('title', 'Message Templates')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-file-alt me-3"></i>Message Templates
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.messaging.index') }}" class="text-white-50">Messaging</a></li>
                                <li class="breadcrumb-item text-white active">Templates</li>
                            </ol>
                        </nav>
                    </div>
                    <button class="btn btn-light btn-lg" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3);" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus me-2"></i>Create Template
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row g-4">
        @forelse($templates as $template)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">{{ $template['name'] }}</h6>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Use Template">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">{{ Str::limit($template['content'], 120) }}</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary-subtle text-primary px-2 py-1">Variables:</span>
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{patient_name}</span>
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1">{date}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <button class="btn btn-primary w-100" onclick="useTemplate({{ json_encode($template) }})">
                            <i class="fas fa-paper-plane me-2"></i>Use This Template
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);">
                                <i class="fas fa-file-alt" style="font-size: 3rem; color: #8b5cf6;"></i>
                            </div>
                        </div>
                        <h4 class="text-dark mb-3 fw-bold">No Templates</h4>
                        <p class="text-muted mb-4">Create your first message template to get started</p>
                        <button class="btn btn-primary btn-lg px-5" data-bs-toggle="modal" data-bs-target="#createTemplateModal" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border: none;">
                            <i class="fas fa-plus-circle me-2"></i>Create Template
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Create New Template
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="templateForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Template Name</label>
                        <input type="text" class="form-control form-control-lg" id="templateName" placeholder="e.g., Appointment Reminder">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Message Content</label>
                        <textarea class="form-control" rows="6" id="templateContent" placeholder="Enter your message template..."></textarea>
                        <small class="text-muted">Use variables: {patient_name}, {date}, {time}, {amount}, etc.</small>
                    </div>
                    <div class="alert alert-info">
                        <strong><i class="fas fa-info-circle me-2"></i>Available Variables:</strong>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-secondary">{patient_name}</span>
                            <span class="badge bg-secondary">{date}</span>
                            <span class="badge bg-secondary">{time}</span>
                            <span class="badge bg-secondary">{amount}</span>
                            <span class="badge bg-secondary">{invoice_number}</span>
                            <span class="badge bg-secondary">{doctor_name}</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTemplate()" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border: none;">
                    <i class="fas fa-save me-2"></i>Save Template
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(139, 92, 246, 0.2) !important;
    }

    .btn-outline-primary:hover {
        background: #8b5cf6;
        border-color: #8b5cf6;
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

function useTemplate(template) {
    // Redirect to bulk messaging with template data
    window.location.href = '{{ route("hms.messaging.bulk") }}?template=' + encodeURIComponent(JSON.stringify(template));
}

function saveTemplate() {
    const name = document.getElementById('templateName').value;
    const content = document.getElementById('templateContent').value;
    
    if (!name || !content) {
        alert('Please fill in all fields');
        return;
    }
    
    // Here you would save the template via AJAX
    alert('Template saved! (Note: This is a demo. Full implementation would save to database)');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('createTemplateModal'));
    modal.hide();
    
    // Clear form
    document.getElementById('templateForm').reset();
}
</script>
@endsection
