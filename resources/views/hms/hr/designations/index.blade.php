@extends('admin.layouts.app')

@section('title', 'Designations Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-briefcase me-3"></i>Designations Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›'; background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Designations</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <button type="button" class="btn btn-light btn-lg shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                            <i class="fas fa-plus-circle me-2"></i>Add New Designation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards with Hover Effects -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Designations</h6>
                            <h2 class="mb-0 fw-bold" style="color: #667eea;">{{ $designations->total() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-briefcase text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Medical</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $designations->where('department', 'Medical')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-md text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Nursing</h6>
                            <h2 class="mb-0 fw-bold" style="color: #06b6d4;">{{ $designations->where('department', 'Nursing')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-nurse text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Administration</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $designations->where('department', 'Administration')->count() }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
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
                                Designations List
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 300px;">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search designations...">
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><h6 class="dropdown-header">Filter by Department</h6></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="all">All Departments</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="Medical">Medical</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="Nursing">Nursing</a></li>
                                        <li><a class="dropdown-item filter-item" href="#" data-filter="Administration">Administration</a></li>
                                    </ul>
                                </div>
                            </div>
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

                    @if($designations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="designationsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 25%;">Designation</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 15%;">Department</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 12%;">Level</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 28%;">Description</th>
                                        <th class="px-4 py-3 fw-semibold text-dark" style="width: 10%;">Created</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center" style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($designations as $designation)
                                        <tr class="designation-row" data-department="{{ $designation->department }}">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="fas fa-briefcase text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $designation->name }}</h6>
                                                        <small class="text-muted">ID: #{{ str_pad($designation->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($designation->department)
                                                    <span class="badge rounded-pill px-3 py-2" style="font-size: 0.85rem; background: {{ $designation->department == 'Medical' ? '#d1fae5' : ($designation->department == 'Nursing' ? '#cffafe' : '#fef3c7') }}; color: {{ $designation->department == 'Medical' ? '#065f46' : ($designation->department == 'Nursing' ? '#164e63' : '#78350f') }};">
                                                        <i class="fas fa-{{ $designation->department == 'Medical' ? 'user-md' : ($designation->department == 'Nursing' ? 'user-nurse' : 'building') }} me-1"></i>
                                                        {{ $designation->department }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($designation->level)
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                        <i class="fas fa-layer-group me-1"></i>
                                                        {{ $designation->level }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($designation->description)
                                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $designation->description }}">
                                                        {{ Str::limit($designation->description, 60) }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $designation->created_at->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $designation->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary rounded-start" 
                                                            onclick="editDesignation({{ $designation->id }}, '{{ addslashes($designation->name) }}', '{{ addslashes($designation->description) }}', '{{ $designation->department }}', '{{ $designation->level }}')"
                                                            data-bs-toggle="tooltip" title="Edit" style="border-color: #667eea;">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger rounded-end" 
                                                            onclick="deleteDesignation({{ $designation->id }}, '{{ addslashes($designation->name) }}')"
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

                        @if($designations->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $designations->firstItem() }}</strong> to <strong>{{ $designations->lastItem() }}</strong> of <strong>{{ $designations->total() }}</strong> entries
                            </div>
                            <div>
                                {{ $designations->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);">
                                    <i class="fas fa-briefcase" style="font-size: 3rem; color: #667eea;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Designations Found</h4>
                            <p class="text-muted mb-4">Start by creating your first designation to organize your staff roles effectively.</p>
                            <button type="button" class="btn btn-primary btn-lg px-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                                <i class="fas fa-plus-circle me-2"></i>Create First Designation
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Designation Modal -->
<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Add New Designation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('hms.hr.designations.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold text-dark">
                                Designation Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., Senior Doctor" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="department" class="form-label fw-bold text-dark">Department</label>
                            @if($departments->isEmpty())
                                <div class="alert alert-warning d-flex align-items-center mb-2" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div class="flex-grow-1">
                                        <strong>No departments found!</strong> 
                                        <a href="{{ route('hms.hr.departments.index') }}" class="alert-link">Create a department first</a>
                                    </div>
                                </div>
                            @endif
                            <select class="form-select form-select-lg @error('department') is-invalid @enderror" 
                                    id="department" name="department" {{ $departments->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="level" class="form-label fw-bold text-dark">Level</label>
                            <select class="form-select form-select-lg @error('level') is-invalid @enderror" 
                                    id="level" name="level">
                                <option value="">Select Level</option>
                                <option value="Entry" {{ old('level') == 'Entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="Mid" {{ old('level') == 'Mid' ? 'selected' : '' }}>Mid Level</option>
                                <option value="Senior" {{ old('level') == 'Senior' ? 'selected' : '' }}>Senior Level</option>
                                <option value="Manager" {{ old('level') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Director" {{ old('level') == 'Director' ? 'selected' : '' }}>Director</option>
                            </select>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="description" class="form-label fw-bold text-dark">Description</label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Brief description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-save me-1"></i>Create Designation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Designation Modal -->
<div class="modal fade" id="editDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-edit me-2"></i>Edit Designation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDesignationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label fw-bold text-dark">
                                Designation Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="edit_name" name="name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_department" class="form-label fw-bold text-dark">Department</label>
                            <select class="form-select form-select-lg" id="edit_department" name="department">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label for="edit_level" class="form-label fw-bold text-dark">Level</label>
                            <select class="form-select form-select-lg" id="edit_level" name="level">
                                <option value="">Select Level</option>
                                <option value="Entry">Entry Level</option>
                                <option value="Mid">Mid Level</option>
                                <option value="Senior">Senior Level</option>
                                <option value="Manager">Manager</option>
                                <option value="Director">Director</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_description" class="form-label fw-bold text-dark">Description</label>
                            <textarea class="form-control form-control-lg" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i>Update Designation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Designation Modal -->
<div class="modal fade" id="deleteDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Designation
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
                        You want to delete the designation <strong class="text-danger">"<span id="deleteDesignationName"></span>"</strong>?
                    </p>
                </div>
                <div class="alert alert-warning border-0 shadow-sm">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All employees with this designation will need to be reassigned.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form id="deleteDesignationForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash-alt me-1"></i>Delete Designation
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

    .designation-row:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
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

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('designationsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // Filter functionality
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const filterValue = this.getAttribute('data-filter');
            const rows = document.querySelectorAll('.designation-row');
            
            if (filterValue === 'all') {
                rows.forEach(row => row.style.display = '');
            } else {
                rows.forEach(row => {
                    const dept = row.getAttribute('data-department');
                    row.style.display = dept === filterValue ? '' : 'none';
                });
            }
            
            // Update active state
            document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

function editDesignation(id, name, description, department, level) {
    document.getElementById('editDesignationForm').action = `/hms/hr/designations/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_department').value = department || '';
    document.getElementById('edit_level').value = level || '';
    
    new bootstrap.Modal(document.getElementById('editDesignationModal')).show();
}

function deleteDesignation(id, name) {
    document.getElementById('deleteDesignationForm').action = `/hms/hr/designations/${id}`;
    document.getElementById('deleteDesignationName').textContent = name;
    
    new bootstrap.Modal(document.getElementById('deleteDesignationModal')).show();
}
</script>
@endpush
@endsection
