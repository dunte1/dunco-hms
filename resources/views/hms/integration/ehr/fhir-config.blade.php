@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-cloud text-primary mr-2"></i>
                FHIR Configuration
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">FHIR Settings</h5>
        </div>
        <div class="card-body">
            <form id="fhirConfigForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">FHIR Endpoint URL</label>
                    <input type="url" name="fhir_endpoint" class="form-control" 
                           placeholder="https://fhir.example.com/fhir" 
                           value="{{ config('ehr.fhir_endpoint') }}">
                    <small class="form-text text-muted">FHIR server endpoint</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">FHIR Version</label>
                    <select name="fhir_version" class="form-control">
                        <option value="R4">FHIR R4</option>
                        <option value="STU3">FHIR STU3</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Authentication Token</label>
                    <input type="text" name="auth_token" class="form-control" 
                           placeholder="Bearer token">
                    <small class="form-text text-muted">Optional authentication token</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Configuration
                </button>
                <a href="{{ route('hms.integration.ehr.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

