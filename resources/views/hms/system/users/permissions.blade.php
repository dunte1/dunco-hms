@extends('admin.layouts.app')

@section('title', 'Manage Permissions - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-shield-alt me-3"></i>Manage Permissions
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.system.users.index') }}" class="text-white-50">Users</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.system.users.show', $user) }}" class="text-white-50">{{ $user->name }}</a></li>
                                <li class="breadcrumb-item text-white active">Permissions</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.system.users.show', $user) }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back to User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Roles Management -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-tag text-primary me-2"></i>
                        Assign Roles
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hms.system.users.update-roles', $user) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Roles</label>
                            <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                @foreach($allRoles as $role)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="{{ $role->id }}" id="role_{{ $role->id }}"
                                               {{ $user->hasRole($role) ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex justify-content-between w-100" for="role_{{ $role->id }}">
                                            <span>{{ $role->name }}</span>
                                            <small class="text-muted">({{ $role->permissions->count() }} permissions)</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Tip:</strong> Edit role permissions at <a href="{{ route('admin.roles.index') }}" target="_blank">/admin/roles</a>. 
                                All users with that role will automatically get updated permissions!
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Roles
                        </button>
                    </form>
                </div>
            </div>

            <!-- Current Status -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Permission Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Total Permissions</label>
                        <h3 class="mb-0 text-primary">{{ $user->getAllPermissions()->count() }}</h3>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Via Roles</label>
                        <p class="mb-0 fw-semibold">{{ $user->getPermissionsViaRoles()->count() }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Direct Permissions</label>
                        <p class="mb-0 fw-semibold">{{ $user->getDirectPermissions()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct Permissions Management -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-key text-warning me-2"></i>
                        Direct Permissions (Override Role Permissions)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>How it works:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Via Roles:</strong> Permissions automatically granted through assigned roles. Edit roles at <a href="{{ route('admin.roles.index') }}">/admin/roles</a> to update all users with that role.</li>
                            <li><strong>Direct Permissions:</strong> Permissions assigned directly to this user (shown below). These override/add to role permissions.</li>
                            <li><strong>Auto-Update:</strong> When you edit a role's permissions, ALL users with that role automatically get the updated permissions. No manual updates needed!</li>
                        </ul>
                    </div>

                    <form action="{{ route('hms.system.users.update-permissions', $user) }}" method="POST">
                        @csrf
                        <div class="row">
                            @foreach($allPermissions as $category => $categoryPermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="border rounded p-3">
                                        <h6 class="fw-bold mb-3 text-capitalize">
                                            <i class="fas fa-folder text-primary me-2"></i>
                                            {{ ucfirst($category) }}
                                        </h6>
                                        <div style="max-height: 300px; overflow-y: auto;">
                                            @foreach($categoryPermissions as $permission)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                           value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                           {{ in_array($permission->id, $directPermissions) ? 'checked' : '' }}
                                                           {{ in_array($permission->id, $permissionsViaRoles) ? 'disabled' : '' }}>
                                                    <label class="form-check-label w-100" for="perm_{{ $permission->id }}">
                                                        <span>{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                                                        @if(in_array($permission->id, $permissionsViaRoles))
                                                            <small class="text-success ms-2" title="Already granted via role">
                                                                <i class="fas fa-check-circle"></i> (via role)
                                                            </small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Permissions already granted via roles are disabled. Remove the role to assign directly.
                            </small>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Update Direct Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection

