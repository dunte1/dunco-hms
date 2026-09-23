@extends('admin.layouts.app')

@section('title', 'Student Rotations')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-graduate me-3"></i>Student Rotations
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Student Rotations</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.student-rotations.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus-circle me-2"></i>New Rotation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Rotations</h6>
                            <h2 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-graduate text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Active</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $stats['active'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-play-circle text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Scheduled</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $stats['scheduled'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Completed</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6366f1;">{{ $stats['completed'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
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
                                Rotations List
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('hms.hr.student-rotations.index') }}" class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 200px;">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-lg" placeholder="Search...">
                                </div>
                                <select name="status" class="form-select form-select-lg" style="max-width: 150px;">
                                    <option value="">All Status</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <select name="department_id" class="form-select form-select-lg" style="max-width: 180px;">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none;">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-5"></i>
                                <strong>{{ session('success') }}</strong>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($rotations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Rotation#</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Student</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Institution</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Program</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Department</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Supervisor</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Dates</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rotations as $rotation)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary-subtle text-primary px-3 py-2 fw-bold">
                                                    {{ $rotation->rotation_number }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                                                            <i class="fas fa-user-graduate text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $rotation->student_name }}</h6>
                                                        <small class="text-muted">{{ $rotation->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3"><small>{{ $rotation->institution }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $rotation->program }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $rotation->department->name ?? 'N/A' }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $rotation->supervisor->first_name ?? 'N/A' }} {{ $rotation->supervisor->last_name ?? '' }}</small></td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $rotation->start_date->format('M d') }} - {{ $rotation->end_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColors = [
                                                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'play-circle'],
                                                        'scheduled' => ['bg' => '#fef3c7', 'text' => '#78350f', 'icon' => 'clock'],
                                                        'completed' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'check-circle'],
                                                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'times-circle'],
                                                    ];
                                                    $color = $statusColors[$rotation->status] ?? $statusColors['scheduled'];
                                                @endphp
                                                <span class="badge rounded-pill px-3 py-2" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                                    <i class="fas fa-{{ $color['icon'] }} me-1"></i>
                                                    {{ ucfirst($rotation->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('hms.hr.student-rotations.show', $rotation) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($rotations->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $rotations->firstItem() }}</strong> to <strong>{{ $rotations->lastItem() }}</strong> of <strong>{{ $rotations->total() }}</strong> entries
                            </div>
                            <div>{{ $rotations->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);">
                                    <i class="fas fa-user-graduate" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Student Rotations Found</h4>
                            <p class="text-muted mb-4">Start by creating a new rotation.</p>
                            <a href="{{ route('hms.hr.student-rotations.create') }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>Create First Rotation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important; }
    .table tbody tr:hover { background-color: #f8fafc !important; }
</style>
@endsection
