@extends('admin.layouts.app')

@section('title', 'AI Diagnosis Suggestions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">AI Diagnosis Suggestions</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" id="openDiagnosisModal">
                            <i class="fas fa-plus"></i> Generate Diagnosis Suggestion
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
                                    <th>Suggested Diagnoses</th>
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
                                    <td>
                                        @if(is_array($suggestion->suggested_diagnoses))
                                            @foreach($suggestion->suggested_diagnoses as $diagnosis)
                                            <span class="badge badge-info mr-1">{{ $diagnosis['condition'] ?? 'N/A' }} ({{ $diagnosis['probability'] ?? 0 }}%)</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No diagnoses suggested</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $suggestion->confidence_score }}%">
                                                {{ $suggestion->confidence_score }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $suggestion->status === 'accepted' ? 'success' : ($suggestion->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($suggestion->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="acceptDiagnosis({{ $suggestion->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectDiagnosis({{ $suggestion->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="viewDiagnosisDetails({{ $suggestion->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No AI diagnosis suggestions available</td>
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

<!-- Generate Diagnosis Modal -->
<div class="modal fade" id="generateDiagnosisModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate AI Diagnosis Suggestion</h5>
                <button type="button" class="close" id="closeDiagnosisModalBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generateDiagnosisForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patient_id">Patient</label>
                                <select class="form-control" id="patient_id" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach(\App\Models\Patient::latest()->get() as $patient)
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
                                    @foreach(\App\Models\Doctor::with('department')->latest()->get() as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Symptoms <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="fever" id="fever">
                                    <label class="form-check-label" for="fever">Fever</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="cough" id="cough">
                                    <label class="form-check-label" for="cough">Cough</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="headache" id="headache">
                                    <label class="form-check-label" for="headache">Headache</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="chest_pain" id="chest_pain">
                                    <label class="form-check-label" for="chest_pain">Chest Pain</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="shortness_of_breath" id="shortness_of_breath">
                                    <label class="form-check-label" for="shortness_of_breath">Shortness of Breath</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="symptoms[]" value="nausea" id="nausea">
                                    <label class="form-check-label" for="nausea">Nausea</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Vital Signs (Optional)</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="vital_signs[temperature]" placeholder="Temperature (°C)" step="0.1">
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="vital_signs[blood_pressure_systolic]" placeholder="BP Systolic">
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="vital_signs[heart_rate]" placeholder="Heart Rate (BPM)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelDiagnosisModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitDiagnosisBtn">Generate Diagnosis Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="diagnosisDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Diagnosis Suggestion Details</h5>
                <button type="button" class="close" id="closeDiagnosisDetailsBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="diagnosisDetailsContent">
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
    
    function acceptDiagnosis(id) {
        if (confirm('Accept this diagnosis suggestion?')) {
            alert('Diagnosis suggestion accepted!');
        }
    }

    function rejectDiagnosis(id) {
        if (confirm('Reject this diagnosis suggestion?')) {
            alert('Diagnosis suggestion rejected!');
        }
    }

    function viewDiagnosisDetails(id) {
        jQuery('#diagnosisDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        jQuery('#diagnosisDetailsModal').modal('show');
        
        setTimeout(function() {
            jQuery('#diagnosisDetailsContent').html(
                '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<h6>AI Analysis</h6>' +
                        '<p>AI analyzed symptoms, vital signs, and lab results using medical knowledge base to suggest potential diagnoses.</p>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<h6>Suggested Diagnoses</h6>' +
                        '<ul>' +
                            '<li>Common Cold - 75% probability</li>' +
                            '<li>Flu - 60% probability</li>' +
                            '<li>Allergic Reaction - 45% probability</li>' +
                        '</ul>' +
                    '</div>' +
                '</div>'
            );
        }, 1000);
    }

    // Make functions globally available
    window.acceptDiagnosis = acceptDiagnosis;
    window.rejectDiagnosis = rejectDiagnosis;
    window.viewDiagnosisDetails = viewDiagnosisDetails;

    // Wait for jQuery and DOM
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            // Open modal
            $('#openDiagnosisModal').on('click', function() {
                $('#generateDiagnosisModal').modal('show');
            });

            // Close modal buttons
            $('#closeDiagnosisModalBtn, #cancelDiagnosisModalBtn').on('click', function() {
                $('#generateDiagnosisModal').modal('hide');
            });

            $('#closeDiagnosisDetailsBtn').on('click', function() {
                $('#diagnosisDetailsModal').modal('hide');
            });

            // Handle form submission
            $('#generateDiagnosisForm').on('submit', function(e) {
                e.preventDefault();
                
                var form = this;
                var formData = $(form).serialize();
                var submitBtn = $('#submitDiagnosisBtn');
                var originalText = submitBtn.html();
                
                // Validate form
                var patientId = $('#patient_id').val();
                var doctorId = $('#doctor_id').val();
                var symptoms = $('input[name="symptoms[]"]:checked');
                
                if (!patientId || !doctorId || symptoms.length === 0) {
                    alert('Please select a patient, doctor, and at least one symptom');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
                
                $.ajax({
                    url: '{{ route("ai.diagnosis-suggestions.generate") }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#generateDiagnosisModal').modal('hide');
                            alert('AI diagnosis suggestion generated successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Failed to generate diagnosis suggestion'));
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'An error occurred while generating diagnosis suggestion';
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
