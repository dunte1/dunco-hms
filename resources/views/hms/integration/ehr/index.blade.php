@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-network-wired text-primary mr-2"></i>
                EHR Integration
            </h2>
            <p class="text-muted mb-0">Configure HL7 and FHIR integrations for interoperability</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        HL7 Integration
                    </h5>
                </div>
                <div class="card-body">
                    <p>HL7 (Health Level Seven) is a standard for exchanging health information electronically.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-2"></i> ADT Messages</li>
                        <li><i class="fas fa-check text-success mr-2"></i> ORM Messages</li>
                        <li><i class="fas fa-check text-success mr-2"></i> ORU Messages</li>
                    </ul>
                    <a href="{{ route('hms.integration.ehr.hl7-config') }}" class="btn btn-primary">
                        <i class="fas fa-cog mr-2"></i> Configure HL7
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cloud mr-2"></i>
                        FHIR Integration
                    </h5>
                </div>
                <div class="card-body">
                    <p>FHIR (Fast Healthcare Interoperability Resources) for modern healthcare data exchange.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-2"></i> Patient Resources</li>
                        <li><i class="fas fa-check text-success mr-2"></i> Observation Resources</li>
                        <li><i class="fas fa-check text-success mr-2"></i> MedicationRequest Resources</li>
                    </ul>
                    <a href="{{ route('hms.integration.ehr.fhir-config') }}" class="btn btn-info">
                        <i class="fas fa-cog mr-2"></i> Configure FHIR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary btn-block" onclick="testHl7Connection()">
                                <i class="fas fa-plug mr-2"></i> Test HL7 Connection
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-info btn-block" onclick="sendTestHl7()">
                                <i class="fas fa-paper-plane mr-2"></i> Send Test HL7 Message
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-success btn-block" onclick="sendTestFhir()">
                                <i class="fas fa-paper-plane mr-2"></i> Send Test FHIR Resource
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testHl7Connection() {
    const endpoint = prompt('Enter HL7 endpoint URL:');
    if (endpoint) {
        fetch('{{ route("hms.integration.ehr.test-hl7") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ endpoint })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success ? 'Connection successful!' : 'Connection failed: ' + data.message);
        });
    }
}

function sendTestHl7() {
    alert('HL7 test message functionality - configure endpoint first');
}

function sendTestFhir() {
    alert('FHIR test resource functionality - configure endpoint first');
}
</script>
@endpush
@endsection

