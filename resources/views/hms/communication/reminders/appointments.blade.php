@extends('admin.layouts.app')

@section('title', 'Appointment Reminders')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-calendar-check me-3"></i>Appointment Reminders
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.reminders.index') }}" class="text-white-50">Reminders</a></li>
                                <li class="breadcrumb-item text-white active">Appointments</li>
                            </ol>
                        </nav>
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
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-warning-subtle text-warning px-3 py-2 me-3">
                            <i class="fas fa-calendar-check me-1"></i>
                        </span>
                        Upcoming Appointments (Next 7 Days)
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Doctor</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Appointment Date</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Time</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Send Reminder</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $appointment->patient->full_name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $appointment->patient->patient_no ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">{{ $appointment->doctor->full_name ?? 'N/A' }}</div>
                                                @if($appointment->doctor && $appointment->doctor->department)
                                                    <small class="text-muted">{{ $appointment->doctor->department->name }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    <i class="far fa-calendar me-1 text-warning"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Send Email">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Send SMS">
                                                        <i class="fas fa-sms"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($appointments->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $appointments->firstItem() }}</strong> to <strong>{{ $appointments->lastItem() }}</strong> of <strong>{{ $appointments->total() }}</strong> entries
                            </div>
                            <div>{{ $appointments->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                                    <i class="fas fa-calendar-check" style="font-size: 3rem; color: #f59e0b;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Upcoming Appointments</h4>
                            <p class="text-muted mb-4">There are no appointments scheduled for the next 7 days.</p>
                            <a href="{{ route('hms.appointments.index') }}" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                                <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-outline-success:hover {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    * {
        transition: all 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection
