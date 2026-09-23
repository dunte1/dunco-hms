@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('hms.queue.index') }}" class="hover:text-primary">Queue Management</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>Edit {{ $queue->queue_number }}</span>
        </div>
        <h1 class="h3 mb-0"><i class="fa fa-edit text-warning me-2"></i>Edit Queue Ticket</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('hms.queue.update', $queue) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name', $queue->patient_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient Phone</label>
                        <input type="text" name="patient_phone" class="form-control" value="{{ old('patient_phone', $queue->patient_phone) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $queue->department) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Doctor</label>
                        <select name="doctor_id" class="form-select">
                            <option value="">-- Select Doctor --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $queue->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Queue Type <span class="text-danger">*</span></label>
                        <select name="queue_type" class="form-select" required>
                            @foreach(['appointment' => 'Appointment', 'walk_in' => 'Walk-in', 'emergency' => 'Emergency', 'follow_up' => 'Follow-up'] as $val => $label)
                                <option value="{{ $val }}" {{ old('queue_type', $queue->queue_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            @foreach(['low' => 'Low', 'normal' => 'Normal', 'high' => 'High', 'emergency' => 'Emergency'] as $val => $label)
                                <option value="{{ $val }}" {{ old('priority', $queue->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $queue->notes) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Update</button>
                    <a href="{{ route('hms.queue.show', $queue) }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-1"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
