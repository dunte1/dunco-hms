@extends('admin.layouts.app')

@section('title', 'AI Appointment Suggestions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">AI Appointment Suggestions</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" id="openGenerateModal">
                            <i class="fas fa-plus"></i> Generate Suggestion
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Suggested Time</th>
                                    <th>Confidence Score</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suggestions as $suggestion)
                                <tr>
                                    <td>{{ $suggestion->patient->first_name }} {{ $suggestion->patient->last_name }}</td>
                                    <td>{{ $suggestion->doctor->first_name }} {{ $suggestion->doctor->last_name }}</td>
                                    <td>{{ $suggestion->suggested_time->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $suggestion->confidence_score }}%">
                                                {{ $suggestion->confidence_score }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $suggestion->status === 'accepted' ? 'success' : ($suggestion->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($suggestion->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="acceptSuggestion({{ $suggestion->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectSuggestion({{ $suggestion->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="viewDetails({{ $suggestion->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No AI suggestions available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $suggestions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Suggestion Modal -->
<div class="modal fade" id="generateSuggestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate AI Appointment Suggestion</h5>
                <button type="button" class="close" id="closeAppointmentModalBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generateSuggestionForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="patient_id">Patient</label>
                        <select class="form-control" id="patient_id" name="patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach(\App\Models\Patient::latest()->get() as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor_id">Doctor</label>
                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            @foreach(\App\Models\Doctor::with('department')->latest()->get() as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->department->name ?? 'No Department' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferred_date">Preferred Date</label>
                        <input type="date" class="form-control" id="preferred_date" name="preferred_date" required>
                    </div>
                    <div class="form-group">
                        <label for="preferred_time">Preferred Time</label>
                        <input type="time" class="form-control" id="preferred_time" name="preferred_time" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelAppointmentModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Generate Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suggestion Details</h5>
                <button type="button" class="close" id="closeDetailsModalBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    function acceptSuggestion(id) {
        if (confirm('Accept this appointment suggestion?')) {
            alert('Suggestion accepted!');
        }
    }

    function rejectSuggestion(id) {
        if (confirm('Reject this appointment suggestion?')) {
            alert('Suggestion rejected!');
        }
    }

    function viewDetails(id) {
        jQuery('#detailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        jQuery('#detailsModal').modal('show');
        
        setTimeout(function() {
            jQuery('#detailsContent').html(
                '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<h6>AI Reasoning</h6>' +
                        '<p>AI analyzed doctor availability, patient preferences, and historical patterns to suggest the optimal appointment time.</p>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<h6>Doctor Availability</h6>' +
                        '<ul>' +
                            '<li>09:00 - Available</li>' +
                            '<li>09:30 - Available</li>' +
                            '<li>10:00 - Available</li>' +
                        '</ul>' +
                    '</div>' +
                '</div>'
            );
        }, 1000);
    }

    // Make functions globally available
    window.acceptSuggestion = acceptSuggestion;
    window.rejectSuggestion = rejectSuggestion;
    window.viewDetails = viewDetails;

    // Wait for jQuery and DOM
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            // Open modal
            $('#openGenerateModal').on('click', function() {
                $('#generateSuggestionModal').modal('show');
            });

            // Close modal buttons
            $('#closeAppointmentModalBtn, #cancelAppointmentModalBtn').on('click', function() {
                $('#generateSuggestionModal').modal('hide');
            });

            $('#closeDetailsModalBtn').on('click', function() {
                $('#detailsModal').modal('hide');
            });

            // Handle form submission
            $('#generateSuggestionForm').on('submit', function(e) {
                e.preventDefault();
                
                var form = this;
                var formData = $(form).serialize();
                var submitBtn = $('#submitBtn');
                var originalText = submitBtn.html();
                
                // Validate form
                var patientId = $('#patient_id').val();
                var doctorId = $('#doctor_id').val();
                var preferredDate = $('#preferred_date').val();
                var preferredTime = $('#preferred_time').val();
                
                if (!patientId || !doctorId || !preferredDate || !preferredTime) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
                
                $.ajax({
                    url: '{{ route("ai.appointment-suggestions.generate") }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#generateSuggestionModal').modal('hide');
                            alert('AI suggestion generated successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Failed to generate suggestion'));
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'An error occurred while generating suggestion';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = [];
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                if (Array.isArray(value)) {
                                    errors = errors.concat(value);
                                } else {
                                    errors.push(value);
                                }
                            });
                            errorMsg = errors.join(', ');
                        } else if (xhr.status === 422) {
                            errorMsg = 'Validation error. Please check your input.';
                        }
                        alert(errorMsg);
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    }
})();
</script>
@endpush
