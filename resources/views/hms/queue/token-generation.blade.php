@extends('admin.layouts.app')

@section('title', 'Generate Queue Token')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-ticket-alt me-3"></i>Generate Queue Token
                        </h2>
                        <p class="text-white-50 mb-0">Generate a queue number for walk-in patients</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <form action="{{ route('hms.queue.generate-token') }}" method="POST">
                        @csrf

                        <!-- Patient Name -->
                        <div class="mb-4">
                            <label for="patient_name" class="form-label fw-bold">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" 
                                   class="form-control form-control-lg @error('patient_name') is-invalid @enderror" 
                                   value="{{ old('patient_name') }}" required 
                                   placeholder="Enter patient's full name">
                            @error('patient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Patient Phone -->
                        <div class="mb-4">
                            <label for="patient_phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="patient_phone" id="patient_phone" 
                                   class="form-control form-control-lg @error('patient_phone') is-invalid @enderror" 
                                   value="{{ old('patient_phone') }}"
                                   placeholder="Optional phone number">
                            @error('patient_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label for="department" class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select name="department" id="department" 
                                    class="form-select form-select-lg @error('department') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Queue Type -->
                        <div class="mb-4">
                            <label for="queue_type" class="form-label fw-bold">Service Type <span class="text-danger">*</span></label>
                            <select name="queue_type" id="queue_type" 
                                    class="form-select form-select-lg @error('queue_type') is-invalid @enderror" required>
                                <option value="walk_in" {{ old('queue_type') == 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                                <option value="appointment" {{ old('queue_type') == 'appointment' ? 'selected' : '' }}>Appointment</option>
                                <option value="follow_up" {{ old('queue_type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                <option value="emergency" {{ old('queue_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('queue_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg btn-success" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-print me-2"></i>Generate Token
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

