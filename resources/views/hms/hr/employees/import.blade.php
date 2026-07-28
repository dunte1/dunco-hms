@extends('admin.layouts.app')

@section('title', 'Import Employees')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-file-upload me-3"></i>Import Employees</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('import_errors') && count(session('import_errors')) > 0)
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Import Errors:</h6>
                            <ul class="mb-0">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hms.hr.employees.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Supported formats: .xlsx, .xls, .csv (Max 5MB)</small>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Instructions:</h6>
                            <ol class="mb-0">
                                <li>Download the import template first</li>
                                <li>Fill in the employee data following the template format</li>
                                <li>Ensure department names match existing departments</li>
                                <li>Required fields: First Name, Last Name, Email, Department, Position, Employment Type, Hire Date</li>
                            </ol>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Employees
                            </button>
                            <a href="{{ route('hms.hr.employees.import.template') }}" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Template
                            </a>
                            <a href="{{ route('hms.hr.employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>Template Columns</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li><strong>Employee ID:</strong> Auto-generated</li>
                        <li><strong>First Name:</strong> Required</li>
                        <li><strong>Last Name:</strong> Required</li>
                        <li><strong>Email:</strong> Required, Unique</li>
                        <li><strong>Phone:</strong> Optional</li>
                        <li><strong>Date of Birth:</strong> YYYY-MM-DD</li>
                        <li><strong>Gender:</strong> male/female/other</li>
                        <li><strong>Department:</strong> Must exist</li>
                        <li><strong>Position:</strong> Required</li>
                        <li><strong>Employment Type:</strong> full_time/part_time/contract/intern</li>
                        <li><strong>Hire Date:</strong> Required, YYYY-MM-DD</li>
                        <li><strong>Salary:</strong> Numeric</li>
                        <li><strong>Status:</strong> active/inactive/terminated</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

