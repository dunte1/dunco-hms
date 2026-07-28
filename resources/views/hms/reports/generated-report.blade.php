@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        Report: {{ $template->name }}
                    </h2>
                    <p class="text-muted mb-0">Generated on {{ now()->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <a href="{{ route('hms.reports.custom-builder.show', $template) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ $template->name }}</h4>
                    @if($template->description)
                        <p class="text-muted">{{ $template->description }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}</p>
                    @if(!empty($filters))
                        <p><strong>Filters Applied:</strong></p>
                        <ul class="list-unstyled">
                            @foreach($filters as $key => $value)
                                @if(!empty($value))
                                    <li><small>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</small></li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Report Data -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Report Data ({{ count($data) }} records)</h5>
        </div>
        <div class="card-body">
            @if(count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                @foreach(array_keys((array)$data[0]) as $column)
                                    <th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    @foreach((array)$row as $value)
                                        <td>{{ $value ?? '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    No data found for the selected criteria.
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .card-header .btn {
        display: none !important;
    }
    .card {
        border: none;
        box-shadow: none;
    }
}
</style>
@endpush
@endsection

