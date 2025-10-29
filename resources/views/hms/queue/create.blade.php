@extends('admin.layouts.app')

@section('title', 'Add to Queue')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-plus-circle me-3"></i>Add Patient to Queue
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.queue.index') }}" class="text-white-50">Queue Management</a></li>
                                <li class="breadcrumb-item text-white active">Add to Queue</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.queue.store') }}" method="POST">
                        @csrf

                        <!-- Patient Selection -->
                        <div class="mb-4">
                            <label for="patient_id" class="form-label fw-bold">Patient (Optional)</label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                                <option value="">Select Patient (or enter manually below)</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                            data-name="{{ $patient->first_name }} {{ $patient->last_name }}"
                                            data-phone="{{ $patient->phone ?? '' }}">
                                        {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_no }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Patient Name -->
                        <div class="mb-4">
                            <label for="patient_name" class="form-label fw-bold">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" 
                                   class="form-control @error('patient_name') is-invalid @enderror" 
                                   value="{{ old('patient_name') }}" required>
                            @error('patient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Patient Phone -->
                        <div class="mb-4">
                            <label for="patient_phone" class="form-label fw-bold">Patient Phone</label>
                            <input type="text" name="patient_phone" id="patient_phone" 
                                   class="form-control @error('patient_phone') is-invalid @enderror" 
                                   value="{{ old('patient_phone') }}">
                            @error('patient_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-4">
                            <label for="department" class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                            <select name="department" id="department" 
                                    class="form-select @error('department') is-invalid @enderror" required>
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

                        <!-- Doctor Selection (Optional) -->
                        <div class="mb-4">
                            <label for="doctor_id" class="form-label fw-bold">Doctor (Optional)</label>
                            <select name="doctor_id" id="doctor_id" 
                                    class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                            data-department="{{ $doctor->department->name ?? '' }}"
                                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->department->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Queue Type -->
                        <div class="mb-4">
                            <label for="queue_type" class="form-label fw-bold">Queue Type <span class="text-danger">*</span></label>
                            <select name="queue_type" id="queue_type" 
                                    class="form-select @error('queue_type') is-invalid @enderror" required>
                                <option value="appointment" {{ old('queue_type') == 'appointment' ? 'selected' : '' }}>Appointment</option>
                                <option value="walk_in" {{ old('queue_type') == 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                                <option value="emergency" {{ old('queue_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="follow_up" {{ old('queue_type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                            </select>
                            @error('queue_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label for="priority" class="form-label fw-bold">Priority</label>
                            <select name="priority" id="priority" 
                                    class="form-select @error('priority') is-invalid @enderror">
                                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Note: Emergency queue type automatically sets priority to Emergency</small>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hms.queue.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                <i class="fas fa-check me-2"></i>Add to Queue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill patient name and phone when patient is selected
    const patientSelect = document.getElementById('patient_id');
    const patientNameInput = document.getElementById('patient_name');
    const patientPhoneInput = document.getElementById('patient_phone');
    
    patientSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            patientNameInput.value = option.dataset.name || '';
            patientPhoneInput.value = option.dataset.phone || '';
        }
    });

    // Auto-fill department when doctor is selected
    const doctorSelect = document.getElementById('doctor_id');
    const departmentSelect = document.getElementById('department');
    
    doctorSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value && option.dataset.department) {
            departmentSelect.value = option.dataset.department;
        }
    });

    // Auto-set priority when queue type is emergency
    const queueTypeSelect = document.getElementById('queue_type');
    const prioritySelect = document.getElementById('priority');
    
    queueTypeSelect.addEventListener('change', function() {
        if (this.value === 'emergency') {
            prioritySelect.value = 'emergency';
        }
    });
});
</script>
@endsection

