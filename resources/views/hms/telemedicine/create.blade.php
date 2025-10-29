@extends('admin.layouts.app')

@section('title', 'Schedule Telemedicine Session')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Schedule Telemedicine Session</h3>
                </div>
                <div class="card-body">
                    <form id="scheduleSessionForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_id">Patient</label>
                                    <select class="form-control" id="patient_id" name="patient_id" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doctor_id">Doctor</label>
                                    <select class="form-control" id="doctor_id" name="doctor_id" required>
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->department->name ?? 'No Department' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_time">Scheduled Time</label>
                                    <input type="datetime-local" class="form-control" id="scheduled_time" name="scheduled_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session_type">Session Type</label>
                                    <select class="form-control" id="session_type" name="session_type" required>
                                        <option value="">Select Type</option>
                                        <option value="video">Video Call</option>
                                        <option value="audio">Audio Call</option>
                                        <option value="chat">Text Chat</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="platform">Platform</label>
                                    <select class="form-control" id="platform" name="platform" required>
                                        <option value="">Select Platform</option>
                                        <option value="zoom">Zoom</option>
                                        <option value="teams">Microsoft Teams</option>
                                        <option value="custom">Custom Platform</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <input type="text" class="form-control" id="notes" name="notes" placeholder="Optional session notes">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Schedule Session
                            </button>
                            <a href="{{ route('telemedicine.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Sessions
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#scheduleSessionForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("telemedicine.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Telemedicine session scheduled successfully!');
            window.location.href = '{{ route("telemedicine.index") }}';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scheduling session');
    });
});

// Set minimum date to today
document.getElementById('scheduled_time').min = new Date().toISOString().slice(0, 16);
</script>
@endpush
