@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-calendar-day text-primary mr-2"></i>
                        Daily Summary - {{ $summary['date'] }}
                    </h2>
                    <p class="text-muted mb-0">Automated daily hospital statistics</p>
                </div>
                <div>
                    <form action="{{ route('hms.daily-summary.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="date" name="date" value="{{ $summary['date'] }}" class="form-control d-inline-block" style="width: auto;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync mr-2"></i> Generate
                        </button>
                    </form>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $summary['patients']['new'] }}</h3>
                    <p class="mb-0">New Patients Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $summary['patients']['total'] }}</h3>
                    <p class="mb-0">Total Patients</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $summary['patients']['active'] }}</h3>
                    <p class="mb-0">Active Patients</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $summary['appointments']['scheduled'] }}</h3>
                    <p class="mb-0">Scheduled Appointments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments & Visits -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Appointments</h5>
                </div>
                <div class="card-body">
                    <p><strong>Scheduled:</strong> {{ $summary['appointments']['scheduled'] }}</p>
                    <p><strong>Completed:</strong> {{ $summary['appointments']['completed'] }}</p>
                    <p><strong>Cancelled:</strong> {{ $summary['appointments']['cancelled'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">OPD/IPD</h5>
                </div>
                <div class="card-body">
                    <p><strong>OPD Visits:</strong> {{ $summary['opd']['visits'] }}</p>
                    <p><strong>OPD Revenue:</strong> ${{ number_format($summary['opd']['revenue'], 2) }}</p>
                    <p><strong>IPD Admissions:</strong> {{ $summary['ipd']['admissions'] }}</p>
                    <p><strong>IPD Discharges:</strong> {{ $summary['ipd']['discharges'] }}</p>
                    <p><strong>Current IPD:</strong> {{ $summary['ipd']['current'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Billing & Revenue</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Invoices:</strong> {{ $summary['billing']['invoices'] }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Total Revenue:</strong> ${{ number_format($summary['billing']['revenue'], 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Collections:</strong> ${{ number_format($summary['billing']['collections'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Doctors -->
    @if(!empty($summary['top_doctors']))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Top Performing Doctors</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Appointments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summary['top_doctors'] as $doctor)
                                        <tr>
                                            <td>{{ $doctor['name'] }}</td>
                                            <td>{{ $doctor['appointments'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Alerts -->
    @if(!empty($summary['alerts']))
        <div class="row">
            <div class="col-md-12">
                @foreach($summary['alerts'] as $alert)
                    <div class="alert alert-{{ $alert['type'] }}">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ $alert['message'] }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

