@extends('admin.layouts.app')

@section('title', 'Employee Leave Balances')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-user-check me-3"></i>Employee Leave Balances
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.leave-balances.index') }}" class="text-white-50">Leave Balances</a></li>
                                <li class="breadcrumb-item text-white active">{{ $employee->first_name }} {{ $employee->last_name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hms.hr.leave-balances.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                                <i class="fas fa-user text-white fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="fw-bold text-dark mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                            <p class="text-muted mb-2">{{ $employee->position ?? 'N/A' }}</p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-info px-3 py-2">{{ $employee->department->name ?? 'N/A' }}</span>
                                <span class="badge bg-secondary px-3 py-2">{{ $year }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balance Cards -->
    @if($balances->count() > 0)
        <div class="row g-4">
            @foreach($balances as $balance)
                @php
                    $percentage = $balance->entitled > 0 ? round(($balance->used / $balance->entitled) * 100) : 0;
                    $remaining = $balance->remaining;
                @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="text-muted text-uppercase mb-1 small">{{ $balance->leaveType->name ?? 'Leave' }}</h6>
                                    <h3 class="fw-bold mb-0" style="color: #0ea5e9;">{{ $remaining }} days left</h3>
                                </div>
                                <div class="rounded-circle p-3"
                                     style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-check text-info fs-5"></i>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Used: {{ $balance->used }} / {{ $balance->entitled }}</small>
                                    <small class="fw-bold">{{ $percentage }}%</small>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 10px;">
                                    <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }}"
                                         style="width: {{ $percentage }}%; border-radius: 10px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="fw-bold text-dark mb-0">{{ $balance->entitled }}</h5>
                                        <small class="text-muted">Entitled</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="fw-bold text-dark mb-0">{{ $balance->used }}</h5>
                                        <small class="text-muted">Used</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h5 class="fw-bold mb-0" style="color: {{ $remaining > 0 ? '#10b981' : '#ef4444' }};">{{ $remaining }}</h5>
                                    <small class="text-muted">Remaining</small>
                                </div>
                            </div>

                            @if($balance->carried_forward > 0)
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="fas fa-arrow-right me-1"></i>Carried Forward</small>
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 fw-bold">{{ $balance->carried_forward }} days</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="text-center py-5 my-5">
                        <div class="mb-4">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
                                <i class="fas fa-wallet" style="font-size: 3rem; color: #0ea5e9;"></i>
                            </div>
                        </div>
                        <h4 class="text-dark mb-3 fw-bold">No Leave Balances</h4>
                        <p class="text-muted mb-4">No balances found for {{ $employee->first_name }} in {{ $year }}.</p>
                        <a href="{{ route('hms.hr.leave-balances.index') }}" class="btn btn-primary btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Balances
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection
