@extends('admin.layouts.app')

@section('title', 'Create Student Rotation')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-plus-circle me-3"></i>Create Student Rotation
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.student-rotations.index') }}" class="text-white-50">Student Rotations</a></li>
                                <li class="breadcrumb-item text-white active">Create</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('hms.hr.student-rotations.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.student-rotations.store') }}" method="POST" id="rotationForm">
                        @csrf

                        <!-- Student Information -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 me-2">
                                    <i class="fas fa-user-graduate me-1"></i>
                                </span>
                                Student Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="student_name" class="form-label fw-bold text-dark">Student Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('student_name') is-invalid @enderror"
                                           id="student_name" name="student_name" value="{{ old('student_name') }}" required>
                                    @error('student_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-bold text-dark">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="phone" class="form-label fw-bold text-dark">Phone</label>
                                    <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-info-subtle text-info px-3 py-2 me-2">
                                    <i class="fas fa-university me-1"></i>
                                </span>
                                Academic Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="institution" class="form-label fw-bold text-dark">Institution <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('institution') is-invalid @enderror"
                                           id="institution" name="institution" value="{{ old('institution') }}" required>
                                    @error('institution') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="program" class="form-label fw-bold text-dark">Program <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('program') is-invalid @enderror"
                                           id="program" name="program" value="{{ old('program') }}" required>
                                    @error('program') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Rotation Assignment -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success px-3 py-2 me-2">
                                    <i class="fas fa-building me-1"></i>
                                </span>
                                Rotation Assignment
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="department_id" class="form-label fw-bold text-dark">Department <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg @error('department_id') is-invalid @enderror"
                                            id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="supervisor_id" class="form-label fw-bold text-dark">Supervisor <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg @error('supervisor_id') is-invalid @enderror"
                                            id="supervisor_id" name="supervisor_id" required>
                                        <option value="">Select Supervisor</option>
                                        @foreach($supervisors as $sup)
                                        <option value="{{ $sup->id }}" {{ old('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->first_name }} {{ $sup->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supervisor_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Details -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 me-2">
                                    <i class="fas fa-calendar me-1"></i>
                                </span>
                                Schedule & Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-bold text-dark">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-lg @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label fw-bold text-dark">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label for="objectives" class="form-label fw-bold text-dark">Rotation Objectives</label>
                                    <textarea class="form-control form-control-lg @error('objectives') is-invalid @enderror"
                                              id="objectives" name="objectives" rows="4"
                                              placeholder="Describe rotation objectives...">{{ old('objectives') }}</textarea>
                                    @error('objectives') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('hms.hr.student-rotations.index') }}" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Create Rotation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
    }
</style>
@endsection
