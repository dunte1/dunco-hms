@extends('admin.layouts.app')

@section('title', 'DHA Integration')

@section('css')
<style>
.dha-result pre { background: #f8f9fa; border-radius: .375rem; padding: .75rem; font-size: .8rem; max-height: 300px; overflow: auto; }
.dha-result .card { border-left: 4px solid var(--bs-primary, #0d6efd); }
.dha-result .card.bg-success, .dha-result .card.bg-danger { border-left-color: transparent; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-network-wired text-primary mr-1"></i> DHA Integration
                    </h3>
                    <div>
                        @if($configured)
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected</span>
                        @else
                            <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Not Configured</span>
                        @endif
                        <span class="badge badge-info ml-1">{{ strtoupper($environment) }} Environment</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        The Digital Health Superhighway connects this facility to the national
                        <strong>Client Registry</strong>, <strong>Facility Registry</strong>,
                        <strong>Provider Registry</strong>, and <strong>Afyalink</strong> document interchange.
                    </p>
                    <div class="alert @if($configured) alert-info @else alert-warning @endif">
                        <h6 class="mb-1">
                            @if($configured)
                                <i class="fas fa-id-card"></i> Facility: <strong>{{ $facilityId ?: '—' }}</strong> (FRN)
                            @else
                                <i class="fas fa-cog"></i> Set <code>DHA_CLIENT_ID</code>, <code>DHA_CLIENT_SECRET</code>,
                                and <code>DHA_FACILITY_ID</code> in <code>.env</code> to enable live DHA calls.
                            @endif
                        </h6>
                        <small class="d-block mt-1">API Base URL: <code>{{ $baseUrl }}</code></small>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-user-tag"></i> Patient Verification</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.verify') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Digital ID / National ID</label>
                                            <input type="text" name="digital_id" class="form-control" required
                                                placeholder="Enter national ID or passport number">
                                        </div>
                                        <div class="form-group">
                                            <label>ID Type</label>
                                            <select name="digital_id_type" class="form-control">
                                                <option value="ID">National ID</option>
                                                <option value="PASSPORT">Passport</option>
                                                <option value="BIOMETRIC_REF">Biometric Reference</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-check-circle"></i> Verify Patient
                                        </button>
                                    </form>
                                    @if(session('verify_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('verify_result'), 'label' => 'Verification Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-search"></i> Client Registry Search</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.client-registry.search') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Identifier</label>
                                            <input type="text" name="identifier" class="form-control" required
                                                placeholder="CR ID, national ID, or patient identifier">
                                        </div>
                                        <div class="form-group">
                                            <label>Identifier Type</label>
                                            <select name="identifier_type" class="form-control">
                                                <option value="ID">National ID</option>
                                                <option value="PASSPORT">Passport</option>
                                                <option value="BIOMETRIC_REF">Biometric Reference</option>
                                                <option value="CR_ID">Client Registry ID</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-block">
                                            <i class="fas fa-search"></i> Search Registry
                                        </button>
                                    </form>
                                    @if(session('cr_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('cr_result'), 'label' => 'Search Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-hospital"></i> Facility Registry</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.facility-lookup') }}">
                                        @csrf
                                        <p class="text-muted">Look up the configured facility (FRN) against the national Facility Registry.</p>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-building"></i> Lookup Facility
                                        </button>
                                    </form>
                                    @if(session('facility_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('facility_result'), 'label' => 'Facility Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-user-md"></i> Provider Registry</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.provider-search') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Provider Identifier (PPB No.)</label>
                                            <input type="text" name="identifier" class="form-control" required placeholder="e.g. PPB registration number">
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <i class="fas fa-search"></i> Search Providers
                                        </button>
                                    </form>
                                    @if(session('provider_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('provider_result'), 'label' => 'Provider Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-id-badge"></i> Biometric Verification</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.biometric-verify') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Biometric Template (Base64)</label>
                                            <textarea name="biometric_template" class="form-control" rows="2" required placeholder="Base64-encoded fingerprint / face template"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="biometric_type" class="form-control">
                                                <option value="fingerprint">Fingerprint</option>
                                                <option value="face">Face</option>
                                                <option value="iris">Iris</option>
                                                <option value="voice">Voice</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>National ID (optional)</label>
                                            <input type="text" name="national_id" class="form-control" placeholder="For confirmation against CR">
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-block">
                                            <i class="fas fa-fingerprint"></i> Verify Biometric
                                        </button>
                                    </form>
                                    @if(session('biometric_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('biometric_result'), 'label' => 'Biometric Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-file-medical"></i> Afyalink Document Transmit</h5></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('integration.dha.transmit-document') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Document ID (internal record id)</label>
                                            <input type="number" name="document_id" class="form-control" required min="1">
                                        </div>
                                        <button type="submit" class="btn btn-dark btn-block">
                                            <i class="fas fa-paper-plane"></i> Transmit Document
                                        </button>
                                    </form>
                                    @if(session('document_result'))
                                    <div class="dha-result mt-3">
                                        @include('integration.dha._result', ['result' => session('document_result'), 'label' => 'Transmission Result'])
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection