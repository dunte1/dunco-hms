@extends('admin.layouts.app')

@section('title', 'Bulk Messaging')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-users me-3"></i>Bulk Messaging
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.messaging.index') }}" class="text-white-50">Messaging</a></li>
                                <li class="breadcrumb-item text-white active">Bulk Messaging</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 me-3">
                            <i class="fas fa-paper-plane me-1"></i>
                        </span>
                        Send Bulk Messages
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('hms.messaging.send') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Message Type <span class="text-danger">*</span></label>
                                <select name="message_type" class="form-select form-select-lg" required>
                                    <option value="">Select Type</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                                @error('message_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Select Recipients</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-bold" for="selectAll">
                                        Select All Patients
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Patients List</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($patients as $patient)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input patient-checkbox" type="checkbox" name="patients[]" value="{{ $patient->id }}" id="patient{{ $patient->id }}">
                                            <label class="form-check-label" for="patient{{ $patient->id }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $patient->full_name }}</h6>
                                                        <small class="text-muted">{{ $patient->email }} | {{ $patient->phone }}</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-center py-3 text-muted">No patients found</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="6" required placeholder="Enter your message here..."></textarea>
                            @error('message')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('hms.messaging.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                                <i class="fas fa-paper-plane me-2"></i>Send Messages
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.patient-checkbox');
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all when individual checkboxes change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAll.checked = allChecked;
        });
    });
});
</script>
@endsection
