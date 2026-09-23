@extends('admin.layouts.app')

@section('title', 'Internships')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 30px rgba(249, 115, 22, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-briefcase me-3"></i>Internships
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.index') }}" class="text-white-50">HR Management</a></li>
                                <li class="breadcrumb-item text-white active">Internships</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('hms.hr.internships.create') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-plus-circle me-2"></i>New Internship
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Total Interns</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f97316;">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-briefcase text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
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
        <div class="col-xl-4 col-md-6">
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
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 me-3">
                                    <i class="fas fa-list me-1"></i>
                                </span>
                                Internships List
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('hms.hr.internships.index') }}" class="d-flex gap-2 justify-content-md-end">
                                <div class="flex-fill" style="max-width: 200px;">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-lg" placeholder="Search...">
                                </div>
                                <select name="status" class="form-select form-select-lg" style="max-width: 150px;">
                                    <option value="">All Status</option>
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
                                <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none;">
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

                    @if($internships->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Internship#</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Name</th>
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
                                    @foreach($internships as $internship)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 fw-bold">
                                                    {{ $internship->internship_number }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                                            <i class="fas fa-briefcase text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $internship->intern_name }}</h6>
                                                        <small class="text-muted">{{ $internship->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3"><small>{{ $internship->institution }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $internship->program }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $internship->department->name ?? 'N/A' }}</small></td>
                                            <td class="px-4 py-3"><small>{{ $internship->supervisor->first_name ?? 'N/A' }} {{ $internship->supervisor->last_name ?? '' }}</small></td>
                                            <td class="px-4 py-3">
                                                <small class="text-dark fw-medium">{{ $internship->start_date->format('M d') }} - {{ $internship->end_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $statusColors = [
                                                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'play-circle'],
                                                        'completed' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'check-circle'],
                                                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'times-circle'],
                                                    ];
                                                    $color = $statusColors[$internship->status] ?? $statusColors['active'];
                                                @endphp
                                                <span class="badge rounded-pill px-3 py-2" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                                    <i class="fas fa-{{ $color['icon'] }} me-1"></i>
                                                    {{ ucfirst($internship->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('hms.hr.internships.show', $internship) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($internships->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $internships->firstItem() }}</strong> to <strong>{{ $internships->lastItem() }}</strong> of <strong>{{ $internships->total() }}</strong> entries
                            </div>
                            <div>{{ $internships->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);">
                                    <i class="fas fa-briefcase" style="font-size: 3rem; color: #f97316;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Internships Found</h4>
                            <p class="text-muted mb-4">Start by creating a new internship.</p>
                            <a href="{{ route('hms.hr.internships.create') }}" class="btn btn-primary btn-lg px-5 shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>Create First Internship
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
