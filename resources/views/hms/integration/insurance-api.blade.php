@extends('admin.layouts.app')

@section('title', 'Insurance API Integration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Insurance API Integration</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Insurance Providers</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Provider Name</th>
                                                    <th>API Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($providers as $provider)
                                                <tr>
                                                    <td>{{ $provider->name }}</td>
                                                    <td>
                                                        <span class="badge badge-success">Active</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" onclick="testProviderApi({{ $provider->id }})">
                                                            <i class="fas fa-plug"></i> Test API
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No insurance providers configured</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">API Logs</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Provider</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($logs as $log)
                                                <tr>
                                                    <td>{{ $log->api_provider }}</td>
                                                    <td>{{ ucfirst($log->request_type) }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $log->status === 'success' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($log->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No API logs available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Insurance Operations</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                                    <h5>Verify Insurance</h5>
                                                    <p>Check patient insurance coverage and eligibility</p>
                                                    <button class="btn btn-primary" data-toggle="modal" data-target="#verifyInsuranceModal">
                                                        Verify Insurance
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-file-invoice fa-3x text-info mb-3"></i>
                                                    <h5>Submit Claim</h5>
                                                    <p>Submit insurance claims electronically</p>
                                                    <button class="btn btn-info" data-toggle="modal" data-target="#submitClaimModal">
                                                        Submit Claim
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-search fa-3x text-warning mb-3"></i>
                                                    <h5>Check Eligibility</h5>
                                                    <p>Verify service coverage and benefits</p>
                                                    <button class="btn btn-warning" data-toggle="modal" data-target="#checkEligibilityModal">
                                                        Check Eligibility
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Insurance Modal -->
<div class="modal fade" id="verifyInsuranceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Insurance</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="verifyInsuranceForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="patient_insurance_id">Patient Insurance</label>
                        <select class="form-control" id="patient_insurance_id" name="patient_insurance_id" required>
                            <option value="">Select Patient Insurance</option>
                            @foreach(\App\Models\PatientInsurance::with('patient')->get() as $insurance)
                            <option value="{{ $insurance->id }}">{{ $insurance->patient->first_name }} {{ $insurance->patient->last_name }} - {{ $insurance->policy_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="provider">Insurance Provider</label>
                        <select class="form-control" id="provider" name="provider" required>
                            <option value="">Select Provider</option>
                            @foreach(\App\Models\InsuranceProvider::all() as $provider)
                            <option value="{{ $provider->name }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Verify Insurance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submit Claim Modal -->
<div class="modal fade" id="submitClaimModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Insurance Claim</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="submitClaimForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="claim_patient_insurance_id">Patient Insurance</label>
                        <select class="form-control" id="claim_patient_insurance_id" name="patient_insurance_id" required>
                            <option value="">Select Patient Insurance</option>
                            @foreach(\App\Models\PatientInsurance::with('patient')->get() as $insurance)
                            <option value="{{ $insurance->id }}">{{ $insurance->patient->first_name }} {{ $insurance->patient->last_name }} - {{ $insurance->policy_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="claim_amount">Claim Amount</label>
                        <input type="number" class="form-control" id="claim_amount" name="claim_amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="service_codes">Service Codes</label>
                        <input type="text" class="form-control" id="service_codes" name="service_codes[]" placeholder="Enter service codes separated by commas" required>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis_codes">Diagnosis Codes</label>
                        <input type="text" class="form-control" id="diagnosis_codes" name="diagnosis_codes[]" placeholder="Enter diagnosis codes separated by commas" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Check Eligibility Modal -->
<div class="modal fade" id="checkEligibilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Eligibility</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="checkEligibilityForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eligibility_patient_insurance_id">Patient Insurance</label>
                        <select class="form-control" id="eligibility_patient_insurance_id" name="patient_insurance_id" required>
                            <option value="">Select Patient Insurance</option>
                            @foreach(\App\Models\PatientInsurance::with('patient')->get() as $insurance)
                            <option value="{{ $insurance->id }}">{{ $insurance->patient->first_name }} {{ $insurance->patient->last_name }} - {{ $insurance->policy_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_type">Service Type</label>
                        <select class="form-control" id="service_type" name="service_type" required>
                            <option value="">Select Service Type</option>
                            <option value="consultation">Consultation</option>
                            <option value="surgery">Surgery</option>
                            <option value="lab_test">Lab Test</option>
                            <option value="radiology">Radiology</option>
                            <option value="pharmacy">Pharmacy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Check Eligibility</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testProviderApi(providerId) {
    alert('Testing API connection for provider ' + providerId);
}

$('#verifyInsuranceForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("integration.insurance.verify") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Insurance verification completed successfully!');
            $('#verifyInsuranceModal').modal('hide');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while verifying insurance');
    });
});

$('#submitClaimForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("integration.insurance.submit-claim") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Insurance claim submitted successfully!');
            $('#submitClaimModal').modal('hide');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting claim');
    });
});

$('#checkEligibilityForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("integration.insurance.check-eligibility") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Eligibility check completed successfully!');
            $('#checkEligibilityModal').modal('hide');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while checking eligibility');
    });
});
</script>
@endpush
