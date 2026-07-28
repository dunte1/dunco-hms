@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-cog text-primary mr-2"></i>
                HL7 Configuration
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">HL7 Settings</h5>
        </div>
        <div class="card-body">
            <form id="hl7ConfigForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">HL7 Endpoint URL</label>
                    <input type="url" name="hl7_endpoint" class="form-control" 
                           placeholder="https://example.com/hl7/receive" 
                           value="{{ config('ehr.hl7_endpoint') }}">
                    <small class="form-text text-muted">Endpoint to receive HL7 messages</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sending Facility</label>
                    <input type="text" name="sending_facility" class="form-control" 
                           value="{{ config('app.name', 'DuncoHMS') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Receiving Facility</label>
                    <input type="text" name="receiving_facility" class="form-control" 
                           placeholder="External Hospital">
                </div>
                <div class="mb-3">
                    <label class="form-label">HL7 Version</label>
                    <select name="hl7_version" class="form-control">
                        <option value="2.5">HL7 v2.5</option>
                        <option value="2.6">HL7 v2.6</option>
                        <option value="2.7">HL7 v2.7</option>
                    </select>
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

