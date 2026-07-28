@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user me-3"></i>User Details
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.system.users.index') }}" class="text-white-50">Users</a></li>
                                <li class="breadcrumb-item text-white active">{{ $user->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.system.users.edit', $user) }}" class="btn btn-light btn-lg px-4 me-2">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        <a href="{{ route('hms.system.users.index') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- User Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-circle text-primary me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Full Name</label>
                            <p class="fw-semibold mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Email Address</label>
                            <p class="fw-semibold mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">User ID</label>
                            <p class="fw-semibold mb-0">#{{ $user->id }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Account Created</label>
                            <p class="fw-semibold mb-0">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Last Updated</label>
                            <p class="fw-semibold mb-0">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($user->status === 'active')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @elseif($user->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                @endif
                                @if($user->approved_by)
                                    <br><small class="text-muted">Approved by {{ $user->approver->name ?? 'N/A' }}</small>
                                    @if($user->approved_at)
                                        <br><small class="text-muted">{{ $user->approved_at->format('M d, Y') }}</small>
                                    @endif
                                @elseif($user->email_verified_at && $user->status === 'active')
                                    <br><small class="text-muted">Auto-activated via email verification</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Email Verified</label>
                            <p class="mb-0">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                    <br><small class="text-muted">{{ $user->email_verified_at->format('M d, Y h:i A') }}</small>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-circle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        Roles & Permissions
                    </h5>
                    <a href="{{ route('hms.system.users.permissions', $user) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-cog me-1"></i>Manage Permissions
                    </a>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-2">Assigned Roles</label>
                            <div class="mt-2">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary me-2 mb-2 px-3 py-2">
                                        <i class="fas fa-user-tag me-1"></i>{{ $role->name }}
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-white ms-2" title="Edit role permissions">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </span>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Note:</strong> When you edit a role's permissions at <code>/admin/roles/{role}/edit</code>, 
                                all users with that role automatically get updated permissions. No need to update users individually!
                            </small>
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>No roles assigned to this user.
                            <br><small>Assign a role to grant permissions, or assign permissions directly.</small>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <label class="text-muted small d-block mb-2">Total Permissions</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                {{ $user->getAllPermissions()->count() }} Permissions
                            </span>
                            <small class="text-muted ms-3">
                                ({{ $user->getPermissionsViaRoles()->count() }} via roles + {{ $user->getDirectPermissions()->count() }} direct)
                            </small>
                        </div>
                    </div>

                    @if($user->getDirectPermissions()->count() > 0)
                        <div class="mt-3">
                            <label class="text-muted small d-block mb-2">Direct Permissions (not from roles)</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->getDirectPermissions()->take(10) as $permission)
                                    <span class="badge bg-info-subtle text-info">{{ $permission->name }}</span>
                                @endforeach
                                @if($user->getDirectPermissions()->count() > 10)
                                    <span class="badge bg-secondary">+{{ $user->getDirectPermissions()->count() - 10 }} more</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hms.system.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('hms.system.users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-danger w-100" disabled title="Cannot delete your own account">
                                <i class="fas fa-trash me-2"></i>Delete User
                            </button>
                        @endif
                        <a href="{{ route('hms.system.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Account Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Pending Verification</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Member Since</label>
                        <p class="fw-semibold mb-0">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <hr class="my-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hms.system.users.id-card.preview', $user) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-id-card me-2"></i>Preview ID Card
                        </a>
                        <a href="{{ route('hms.system.users.id-card', $user) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-download me-2"></i>Download ID Card
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

