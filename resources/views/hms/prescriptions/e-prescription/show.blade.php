@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-file-prescription text-primary mr-2"></i>
                        E-Prescription Details
                    </h2>
                    <p class="text-muted mb-0">Prescription #{{ $prescription->id }}</p>
                </div>
                <div>
                    <a href="{{ route('hms.prescriptions.e-prescription.pdf', $prescription) }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-2"></i> Generate PDF
                    </a>
                    <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Prescription Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Patient:</strong> {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
                            <p><strong>Patient No:</strong> {{ $prescription->patient->patient_no ?? $prescription->patient->id }}</p>
                            <p><strong>Date:</strong> {{ $prescription->prescription_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Doctor:</strong> Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</p>
                            <p><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($prescription->status) }}</span></p>
                            @if($prescription->signed_at)
                                <p><strong>Signed:</strong> {{ $prescription->signed_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($prescription->symptoms || $prescription->diagnosis)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Medical Information</h5>
                    </div>
                    <div class="card-body">
                        @if($prescription->symptoms)
                            <div class="mb-3">
                                <strong>Symptoms:</strong>
                                <p>{{ $prescription->symptoms }}</p>
                            </div>
                        @endif
                        @if($prescription->diagnosis)
                            <div class="mb-3">
                                <strong>Diagnosis:</strong>
                                <p>{{ $prescription->diagnosis }}</p>
                            </div>
                        @endif
                        @if($prescription->notes)
                            <div>
                                <strong>Notes:</strong>
                                <p>{{ $prescription->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Medicines</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Quantity</th>
                                    <th>Duration</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescription->items as $item)
                                    <tr>
                                        <td>{{ $item->medicine->name }}</td>
                                        <td>{{ $item->dosage }}</td>
                                        <td>{{ $item->frequency }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->duration_days }} days</td>
                                        <td>{{ $item->instructions ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($prescription->digital_signature)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Digital Signature</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $prescription->digital_signature }}" alt="Signature" class="img-fluid" style="max-height: 150px;">
                        <p class="mt-2 mb-0">
                            <small class="text-muted">
                                Signed by: {{ $prescription->signedBy->name ?? 'Unknown' }}<br>
                                On: {{ $prescription->signed_at->format('M d, Y h:i A') }}
                            </small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

