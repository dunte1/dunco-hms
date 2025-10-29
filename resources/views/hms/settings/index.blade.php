@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-cog me-3"></i>Settings
        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item text-white active">Settings</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Categories -->
    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-hospital text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Hospital Info</h5>
                            <small class="text-muted">Basic information</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Configure hospital name, address, contact details, and other general information.</p>
                    <a href="{{ route('hms.settings.general') }}" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Branch Setup -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Branch Setup</h5>
                            <small class="text-muted">Hospital branches</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Manage multiple hospital branches, locations, and branch-specific settings.</p>
                    <a href="{{ route('hms.settings.branches') }}" class="btn btn-success w-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Timezone & Currency -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-globe text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Timezone & Currency</h5>
                            <small class="text-muted">Regional settings</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Set timezone, currency, date and time formats for your hospital.</p>
                    <a href="{{ route('hms.system.timezone') }}" class="btn btn-warning w-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Theme & Branding -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-palette text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Theme & Branding</h5>
                            <small class="text-muted">Customization</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Customize logo, theme colors, and enable/disable dark mode.</p>
                    <a href="{{ route('hms.system.theme') }}" class="btn w-100" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border: none; color: white;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-history text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Audit Logs</h5>
                            <small class="text-muted">Activity tracking</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">View system activity logs, user actions, and system changes.</p>
                    <a href="{{ route('hms.settings.audit-logs') }}" class="btn btn-light w-100 border">
                        <i class="fas fa-arrow-right me-2"></i>View Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- Backup & Restore -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-database text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Backup & Restore</h5>
                            <small class="text-muted">Data management</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Create backups of your database and restore from previous backups.</p>
                    <a href="{{ route('hms.settings.backup') }}" class="btn btn-info w-100" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Google Maps Settings -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-marked-alt text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Google Maps</h5>
                            <small class="text-muted">Location settings</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Configure Google Maps API key, location coordinates, and map display settings for the contact page.</p>
                    <a href="{{ route('hms.system.maps') }}" class="btn btn-success w-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 hover-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-phone-alt text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Contact Information</h5>
                            <small class="text-muted">Public contact details</small>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Manage public contact information, office hours, and social media links displayed on the website.</p>
                    <a href="{{ route('hms.system.contact-info') }}" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
                        <i class="fas fa-arrow-right me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-indigo-subtle text-indigo px-3 py-2 me-3">
                            <i class="fas fa-chart-bar me-1"></i>
                        </span>
                        System Statistics
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 fw-bold" style="color: #6366f1;">{{ $settings->total() }}</h3>
                                <small class="text-muted">System Settings</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 fw-bold" style="color: #10b981;">{{ $branches->count() }}</h3>
                                <small class="text-muted">Hospital Branches</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 fw-bold" style="color: #f59e0b;">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</h3>
                                <small class="text-muted">Current Date</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 fw-bold" style="color: #ec4899;">{{ \Carbon\Carbon::now()->format('H:i') }}</h3>
                                <small class="text-muted">Current Time</small>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(99, 102, 241, 0.2) !important;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    * {
        transition: all 0.3s ease;
    }
</style>
@endsection


