@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-edit me-3"></i>Edit User
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.system.users.index') }}" class="text-white-50">Users</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.system.users.show', $user) }}" class="text-white-50">{{ $user->name }}</a></li>
                                <li class="breadcrumb-item text-white active">Edit</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.system.users.show', $user) }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit text-primary me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.system.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimum 8 characters. Leave blank to keep current password.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="">-- Select Role --</option>
                                    @php
                                        $roles = \Spatie\Permission\Models\Role::all();
                                    @endphp
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                                {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Account Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Setting to "Active" will automatically record you as the approver.
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status_notes" class="form-label">Status Notes (Optional)</label>
                            <textarea class="form-control @error('status_notes') is-invalid @enderror" 
                                      id="status_notes" name="status_notes" rows="2" 
                                      placeholder="Add notes about status change...">{{ old('status_notes', $user->status_notes) }}</textarea>
                            @error('status_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Add any notes about why the status was changed.</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_verify_email" name="auto_verify_email" value="1"
                                       {{ $user->email_verified_at ? 'checked disabled' : '' }}>
                                <label class="form-check-label" for="auto_verify_email">
                                    <strong>Verify Email & Auto-Activate</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                @if($user->email_verified_at)
                                    Email is already verified. If status is "Pending", checking this and setting status to "Active" will activate the account.
                                @else
                                    Checking this will verify the email. If status is "Pending", it will automatically change to "Active".
                                @endif
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                            <a href="{{ route('hms.system.users.show', $user) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Information
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <strong>Note:</strong> Changing the password is optional. Leave it blank to keep the current password.
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-user-clock me-2"></i>
                        Last updated: {{ $user->updated_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

