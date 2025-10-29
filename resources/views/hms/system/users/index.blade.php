@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-users me-3"></i>Users Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Users</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.system.users.create') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-pink-subtle text-pink px-3 py-2 me-3">
                            <i class="fas fa-users me-1"></i>
                        </span>
                        System Users
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 py-3 px-4">User</th>
                                        <th class="border-0 py-3 px-4">Email</th>
                                        <th class="border-0 py-3 px-4">Role</th>
                                        <th class="border-0 py-3 px-4">Created</th>
                                        <th class="border-0 py-3 px-4">Status</th>
                                        <th class="border-0 py-3 px-4 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <span class="text-primary fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-dark">{{ $user->name }}</h6>
                                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-dark">{{ $user->email }}</span>
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-check-circle me-1"></i>Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('hms.system.users.show', $user) }}">
                                                                <i class="fas fa-eye me-2"></i>View
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('hms.system.users.edit', $user) }}">
                                                                <i class="fas fa-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        @if($user->id !== auth()->id())
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('hms.system.users.destroy', $user) }}" 
                                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="d-flex justify-content-center py-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Users Found</h5>
                            <p class="text-muted mb-4">Get started by creating your first user account.</p>
                            <a href="{{ route('hms.system.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New User
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge {
        font-size: 0.75rem;
    }

    /* Dark mode styles */
    .dark .card {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    .dark .card-header {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    .dark .text-dark {
        color: #ffffff !important;
    }

    .dark .text-muted {
        color: #b0b0b0 !important;
    }

    .dark .table-light {
        background-color: #3d3d3d !important;
    }

    .dark .table-light th {
        color: #ffffff !important;
        border-color: #404040 !important;
    }

    .dark .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .dark .dropdown-menu {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    .dark .dropdown-item {
        color: #ffffff !important;
    }

    .dark .dropdown-item:hover {
        background-color: #3d3d3d !important;
    }
</style>
@endsection
