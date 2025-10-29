@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reports & Analytics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h4>Patient Reports</h4>
                                    <p>Demographics, statistics, and patient analytics</p>
                                    <a href="{{ route('hms.reports.patients') }}" class="btn btn-light">View Reports</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                                    <h4>Revenue Reports</h4>
                                    <p>Financial analytics and revenue trends</p>
                                    <a href="{{ route('hms.reports.revenue') }}" class="btn btn-light">View Reports</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar fa-2x mb-2"></i>
                                    <h4>Appointment Reports</h4>
                                    <p>Appointment analytics and doctor performance</p>
                                    <a href="{{ route('hms.reports.appointments') }}" class="btn btn-light">View Reports</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-money-bill fa-2x mb-2"></i>
                                    <h4>Financial Reports</h4>
                                    <p>Payment analytics and financial summaries</p>
                                    <a href="{{ route('hms.reports.financial') }}" class="btn btn-light">View Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h3 class="text-primary">{{ \App\Models\Patient::count() }}</h3>
                                            <p class="text-muted">Total Patients</p>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-success">${{ number_format(\App\Models\Invoice::sum('total_amount'), 2) }}</h3>
                                            <p class="text-muted">Total Revenue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Export Data</h5>
                                </div>
                                <div class="card-body">
                                    <p>Export patient data and reports to CSV format for analysis.</p>
                                    <a href="{{ route('hms.reports.export-patients') }}" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Export Patients
                                    </a>
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