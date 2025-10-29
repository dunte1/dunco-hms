@extends('admin.layouts.app')

@section('title', 'Biometric Security')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Biometric Security Management</h2>
            <p class="text-muted">Enroll and manage biometric authentication</p>
            @if($patient ?? null)
                <div class="alert alert-info mt-2">
                    <i class="fas fa-user-injured"></i> <strong>Patient Enrollment:</strong> 
                    Enrolling biometric data for <strong>{{ $patient->full_name }}</strong> 
                    ({{ $patient->patient_no }}) - 
                    @php
                        $hasInsurance = \App\Models\PatientInsurance::where('patient_id', $patient->id)->where('is_active', true)->exists();
                    @endphp
                    {{ $hasInsurance ? 'Insurance Patient' : 'Standard Patient' }}
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Attempts</h5>
                    <h3>{{ $stats['total_attempts'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Success Rate</h5>
                    <h3>{{ $stats['success_rate'] }}%</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Avg Confidence</h5>
                    <h3>{{ $stats['avg_confidence'] }}%</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Failed Attempts</h5>
                    <h3>{{ $stats['failed'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Biometric Enrollment -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Enroll Biometric</h5>
                </div>
                <div class="card-body">
                    <form id="biometricEnrollForm">
                        @csrf
                        @if($patient ?? null)
                            <input type="hidden" id="patientId" value="{{ $patient->id }}">
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Biometric Type</label>
                            <select class="form-select" id="biometricType" required>
                                <option value="">Select Type</option>
                                <option value="fingerprint">Fingerprint (Recommended for Insurance Patients)</option>
                                <option value="facial">Facial Recognition</option>
                                <option value="iris">Iris Scan</option>
                                <option value="voice">Voice Recognition</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biometric Data</label>
                            <textarea class="form-control" id="biometricData" rows="5" 
                                placeholder='Enter biometric template data (JSON format) or use scanner' required></textarea>
                            <small class="text-muted">Connect your biometric scanner or paste template data</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-fingerprint"></i> Enroll Biometric
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Biometric Scanner</h5>
                </div>
                <div class="card-body text-center">
                    <div id="scannerArea" style="min-height: 300px; border: 2px dashed #ddd; border-radius: 8px; padding: 40px;">
                        <i class="fas fa-fingerprint fa-5x text-muted mb-3"></i>
                        <p class="text-muted">Click to scan or connect biometric device</p>
                        <button class="btn btn-outline-primary" onclick="startBiometricScan()">
                            <i class="fas fa-scan"></i> Start Scan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Option -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p>Delete all your biometric data. This action cannot be undone.</p>
                    <button class="btn btn-danger" onclick="deleteBiometric()">
                        <i class="fas fa-trash"></i> Delete All Biometric Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startBiometricScan() {
    // This would integrate with actual biometric scanner hardware
    alert('Biometric scanner integration needed. Connect your scanner device.');
}

$('#biometricEnrollForm').on('submit', function(e) {
    e.preventDefault();
    
    const biometricType = $('#biometricType').val();
    let biometricData;
    
    try {
        biometricData = JSON.parse($('#biometricData').val());
    } catch (e) {
        alert('Invalid JSON format. Please enter valid biometric data.');
        return;
    }
    
    const patientId = $('#patientId').val();
    
    $.ajax({
        url: '{{ route("biometric.register") }}',
        method: 'POST',
        data: {
            biometric_type: biometricType,
            biometric_data: biometricData,
            patient_id: patientId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('Biometric enrolled successfully!');
                @if($patient ?? null)
                    // Redirect to patient details after enrollment
                    window.location.href = '{{ route("hms.patients.show", $patient->id ?? 0) }}';
                @else
                    location.reload();
                @endif
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
        }
    });
});

function deleteBiometric() {
    if (!confirm('Are you sure you want to delete all biometric data?')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("biometric.delete") }}',
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('Biometric data deleted successfully');
                location.reload();
            }
        }
    });
}
</script>
@endsection

