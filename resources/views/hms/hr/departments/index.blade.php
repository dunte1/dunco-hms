@extends('admin.layouts.app')

@section('title', 'Employee Departments Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-building me-3"></i>Employee Departments Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›'; background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Departments</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <button type="button" class="btn btn-light btn-lg shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                            <i class="fas fa-plus-circle me-2"></i>Add New Department
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Card -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Departments</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3b82f6;">{{ $departments->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card with Enhanced Design -->
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
                                Departments List
                            </h5>
                        </div>
                    </div>
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert" style="border-left: 4px solid #ef4444 !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                                <strong>{{ session('error') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($departments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 30%;">Department Name</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 50%;">Description</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Employees</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center" style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $department)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                                            <i class="fas fa-building text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $department->name }}</h6>
                                                        <small class="text-muted">ID: #{{ str_pad($department->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($department->description)
                                                    <div class="text-truncate" style="max-width: 400px;" title="{{ $department->description }}">
                                                        {{ Str::limit($department->description, 80) }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $department->employees()->count() }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary rounded-start" 
                                                            onclick="editDepartment({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ addslashes($department->description ?? '') }}')"
                                                            data-bs-toggle="tooltip" title="Edit" style="border-color: #3b82f6;">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger rounded-end" 
                                                            onclick="deleteDepartment({{ $department->id }}, '{{ addslashes($department->name) }}')"
                                                            data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($departments->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $departments->firstItem() }}</strong> to <strong>{{ $departments->lastItem() }}</strong> of <strong>{{ $departments->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $departments->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                                    <i class="fas fa-building" style="font-size: 3rem; color: #3b82f6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Departments Found</h4>
                            <p class="text-muted mb-4">Start by creating your first department to organize your employees effectively.</p>
                            <button type="button" class="btn btn-primary btn-lg px-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                <i class="fas fa-plus-circle me-2"></i>Create First Department
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Add New Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('hms.hr.departments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-dark">
                            Department Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="e.g., Administration" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold text-dark">Description</label>
                        <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none;">
                        <i class="fas fa-save me-1"></i>Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-edit me-2"></i>Edit Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDepartmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-bold text-dark">
                            Department Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold text-dark">Description</label>
                        <textarea class="form-control form-control-lg" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i>Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="deleteDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background: #fef2f2;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #ef4444;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Are you sure?</h5>
                    <p class="text-muted mb-0">
                        You want to delete the department <strong class="text-danger">"<span id="deleteDepartmentName"></span>"</strong>?
                    </p>
                </div>
                <div class="alert alert-warning border-0 shadow-sm">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All employees in this department will need to be reassigned.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form id="deleteDepartmentForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash-alt me-1"></i>Delete Department
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced Hover Effects */
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-color: transparent;
        color: white;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        border-radius: 0.5rem;
    }

    /* Smooth transitions */
    * {
        transition: all 0.3s ease;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function editDepartment(id, name, description) {
    document.getElementById('editDepartmentForm').action = `/hms/hr/departments/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    
    new bootstrap.Modal(document.getElementById('editDepartmentModal')).show();
}

function deleteDepartment(id, name) {
    document.getElementById('deleteDepartmentForm').action = `/hms/hr/departments/${id}`;
    document.getElementById('deleteDepartmentName').textContent = name;
    
    new bootstrap.Modal(document.getElementById('deleteDepartmentModal')).show();
}
</script>
@endpush
@endsection

