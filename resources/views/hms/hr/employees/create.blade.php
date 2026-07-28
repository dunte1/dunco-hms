@extends('admin.layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-plus me-3"></i>Add New Employee
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.employees.index') }}" class="text-white-50">Employees</a></li>
                                <li class="breadcrumb-item text-white active">Add New</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('hms.hr.employees.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.employees.store') }}" method="POST" id="employeeForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Personal Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-info-subtle text-info px-3 py-2 me-2">
                                    <i class="fas fa-user me-1"></i>
                                </span>
                                Personal Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-bold text-dark">
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                               placeholder="Enter first name" required>
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-bold text-dark">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('last_name') is-invalid @enderror" 
                                               id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                               placeholder="Enter last name" required>
                                    </div>
                                    @error('last_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold text-dark">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-lg border-start-0 @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" 
                                               placeholder="employee@example.com" required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold text-dark">
                                        Phone
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" 
                                               placeholder="+1234567890">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="date_of_birth" class="form-label fw-bold text-dark">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control form-control-lg border-start-0 @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    </div>
                                    @error('date_of_birth')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="gender" class="form-label fw-bold text-dark">Gender</label>
                                    <select class="form-select form-select-lg @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="hire_date" class="form-label fw-bold text-dark">
                                        Hire Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar-check text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control form-control-lg border-start-0 @error('hire_date') is-invalid @enderror" 
                                               id="hire_date" name="hire_date" value="{{ old('hire_date') }}" required>
                                    </div>
                                    @error('hire_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="photo" class="form-label fw-bold text-dark">
                                        Employee Photo
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-camera text-muted"></i>
                                        </span>
                                        <input type="file" class="form-control form-control-lg border-start-0 @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif">
                                    </div>
                                    <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                                    <div id="photoPreview" class="mt-2" style="display: none;">
                                        <img id="previewImg" src="" alt="Photo Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
                                    </div>
                                    @error('photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success px-3 py-2 me-2">
                                    <i class="fas fa-briefcase me-1"></i>
                                </span>
                                Employment Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="department_id" class="form-label fw-bold text-dark">
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    @if(count($departments) == 0)
                                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div class="flex-grow-1">
                                                <strong>No departments found!</strong> 
                                                <a href="{{ route('hms.hr.departments.index') }}" class="alert-link">Create a department first</a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-building text-muted"></i>
                                        </span>
                                        <select class="form-select form-select-lg border-start-0 @error('department_id') is-invalid @enderror" 
                                                id="department_id" name="department_id" required {{ count($departments) == 0 ? 'disabled' : '' }}>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $id => $name)
                                            <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('department_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="position" class="form-label fw-bold text-dark">
                                        Position <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user-tie text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('position') is-invalid @enderror" 
                                               id="position" name="position" value="{{ old('position') }}" 
                                               placeholder="e.g., Senior Developer" required>
                                    </div>
                                    @error('position')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="employment_type" class="form-label fw-bold text-dark">
                                        Employment Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Type</option>
                                        <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="intern" {{ old('employment_type') == 'intern' ? 'selected' : '' }}>Intern</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="salary" class="form-label fw-bold text-dark">Salary</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">$</span>
                                        <input type="number" class="form-control form-control-lg border-start-0 @error('salary') is-invalid @enderror" 
                                               id="salary" name="salary" value="{{ old('salary') }}" 
                                               placeholder="0.00" step="0.01">
                                    </div>
                                    @error('salary')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 me-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                </span>
                                Additional Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="address" class="form-label fw-bold text-dark">Address</label>
                                    <textarea class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" 
                                              placeholder="Enter full address">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="emergency_contact" class="form-label fw-bold text-dark">Emergency Contact</label>
                                    <textarea class="form-control form-control-lg @error('emergency_contact') is-invalid @enderror" 
                                              id="emergency_contact" name="emergency_contact" rows="2" 
                                              placeholder="Name, Relationship, Phone Number">{{ old('emergency_contact') }}</textarea>
                                    @error('emergency_contact')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- User Account Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 me-2">
                                    <i class="fas fa-user-lock me-1"></i>
                                </span>
                                User Account (Optional)
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="create_user_account" name="create_user_account" value="1">
                                        <label class="form-check-label fw-bold" for="create_user_account">
                                            Create login account for this employee
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6" id="password_group" style="display: none;">
                                    <label for="password" class="form-label fw-bold text-dark">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" class="form-control form-control-lg border-start-0 @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Enter password">
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6" id="password_confirmation_group" style="display: none;">
                                    <label for="password_confirmation" class="form-label fw-bold text-dark">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" class="form-control form-control-lg border-start-0" 
                                               id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 me-2">
                                    <i class="fas fa-id-card me-1"></i>
                                </span>
                                Additional Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label fw-bold text-dark">Nationality</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-flag text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('nationality') is-invalid @enderror" 
                                               id="nationality" name="nationality" value="{{ old('nationality') }}" 
                                               placeholder="e.g., Kenyan">
                                    </div>
                                    @error('nationality')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="id_number" class="form-label fw-bold text-dark">ID/Passport Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-id-card text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('id_number') is-invalid @enderror" 
                                               id="id_number" name="id_number" value="{{ old('id_number') }}" 
                                               placeholder="ID or Passport number">
                                    </div>
                                    @error('id_number')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success px-3 py-2 me-2">
                                    <i class="fas fa-university me-1"></i>
                                </span>
                                Bank Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="bank_name" class="form-label fw-bold text-dark">Bank Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-university text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('bank_name') is-invalid @enderror" 
                                               id="bank_name" name="bank_name" value="{{ old('bank_name') }}" 
                                               placeholder="Bank name">
                                    </div>
                                    @error('bank_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="account_number" class="form-label fw-bold text-dark">Account Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-hashtag text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('account_number') is-invalid @enderror" 
                                               id="account_number" name="account_number" value="{{ old('account_number') }}" 
                                               placeholder="Account number">
                                    </div>
                                    @error('account_number')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="bank_branch" class="form-label fw-bold text-dark">Branch</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-building text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('bank_branch') is-invalid @enderror" 
                                               id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}" 
                                               placeholder="Branch name">
                                    </div>
                                    @error('bank_branch')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Next of Kin Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 me-2">
                                    <i class="fas fa-users me-1"></i>
                                </span>
                                Next of Kin
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="next_of_kin_name" class="form-label fw-bold text-dark">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('next_of_kin_name') is-invalid @enderror" 
                                               id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name') }}" 
                                               placeholder="Full name">
                                    </div>
                                    @error('next_of_kin_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="next_of_kin_relationship" class="form-label fw-bold text-dark">Relationship</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-heart text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('next_of_kin_relationship') is-invalid @enderror" 
                                               id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship') }}" 
                                               placeholder="e.g., Spouse, Parent">
                                    </div>
                                    @error('next_of_kin_relationship')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="next_of_kin_contact" class="form-label fw-bold text-dark">Contact</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg border-start-0 @error('next_of_kin_contact') is-invalid @enderror" 
                                               id="next_of_kin_contact" name="next_of_kin_contact" value="{{ old('next_of_kin_contact') }}" 
                                               placeholder="Phone number">
                                    </div>
                                    @error('next_of_kin_contact')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Supervisor Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-info-subtle text-info px-3 py-2 me-2">
                                    <i class="fas fa-user-tie me-1"></i>
                                </span>
                                Reporting Manager
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="supervisor_id" class="form-label fw-bold text-dark">Supervisor/Manager</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user-tie text-muted"></i>
                                        </span>
                                        <select class="form-select form-select-lg border-start-0 @error('supervisor_id') is-invalid @enderror" 
                                                id="supervisor_id" name="supervisor_id">
                                            <option value="">Select Supervisor</option>
                                            @foreach($employees ?? [] as $emp)
                                            <option value="{{ $emp->id }}" {{ old('supervisor_id') == $emp->id ? 'selected' : '' }}>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('supervisor_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contract Details Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-purple-subtle text-purple px-3 py-2 me-2">
                                    <i class="fas fa-file-contract me-1"></i>
                                </span>
                                Contract Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="contract_type" class="form-label fw-bold text-dark">Contract Type</label>
                                    <select class="form-select form-select-lg @error('contract_type') is-invalid @enderror" 
                                            id="contract_type" name="contract_type">
                                        <option value="">Select Type</option>
                                        <option value="permanent" {{ old('contract_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="contract" {{ old('contract_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="temporary" {{ old('contract_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    </select>
                                    @error('contract_type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="contract_start_date" class="form-label fw-bold text-dark">Contract Start Date</label>
                                    <input type="date" class="form-control form-control-lg @error('contract_start_date') is-invalid @enderror" 
                                           id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date') }}">
                                    @error('contract_start_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="contract_end_date" class="form-label fw-bold text-dark">Contract End Date</label>
                                    <input type="date" class="form-control form-control-lg @error('contract_end_date') is-invalid @enderror" 
                                           id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date') }}">
                                    @error('contract_end_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('hms.hr.employees.index') }}" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Add Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
    }
    
    .page-header-box {
        transition: transform 0.3s ease;
    }
    
    .page-header-box:hover {
        transform: translateY(-2px);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('employeeForm');
    
    form.addEventListener('submit', function(e) {
        // Add any client-side validation here if needed
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding Employee...';
    });
    
    // Photo preview
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.style.display = 'none';
            }
        });
    }
    
    // Toggle password fields when create user account checkbox is checked
    const createUserAccount = document.getElementById('create_user_account');
    const passwordGroup = document.getElementById('password_group');
    const passwordConfirmationGroup = document.getElementById('password_confirmation_group');
    
    if (createUserAccount) {
        createUserAccount.addEventListener('change', function() {
            if (this.checked) {
                passwordGroup.style.display = 'block';
                passwordConfirmationGroup.style.display = 'block';
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;
            } else {
                passwordGroup.style.display = 'none';
                passwordConfirmationGroup.style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
            }
        });
    }
});
</script>
@endpush
@endsection
