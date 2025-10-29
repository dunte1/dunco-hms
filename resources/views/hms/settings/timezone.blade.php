@extends('admin.layouts.app')

@section('title', 'Timezone & Regional Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-globe me-3"></i>Timezone & Regional Settings
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Timezone</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-warning-subtle text-warning px-3 py-2 me-3">
                            <i class="fas fa-clock me-1"></i>
                        </span>
                        Date, Time & Currency Settings
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hms.system.timezone.update') }}" id="timezoneForm">
                        @csrf

                        <h5 class="fw-bold text-dark mb-4">Timezone Configuration</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Timezone <span class="text-danger">*</span></label>
                                <select name="timezone" class="form-select form-select-lg" required>
                                    <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                                    <option value="Africa/Nairobi" {{ $settings['timezone'] == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                                    <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                    <option value="America/Los_Angeles" {{ $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                                    <option value="Europe/London" {{ $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                    <option value="Asia/Dubai" {{ $settings['timezone'] == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                    <option value="Asia/Shanghai" {{ $settings['timezone'] == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (CST)</option>
                                </select>
                                <small class="text-muted">Current time: <span id="currentTime"></span></small>
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Date & Time Format</h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Date Format <span class="text-danger">*</span></label>
                                <select name="date_format" class="form-select form-select-lg" required>
                                    <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="d-m-Y" {{ $settings['date_format'] == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Time Format <span class="text-danger">*</span></label>
                                <select name="time_format" class="form-select form-select-lg" required>
                                    <option value="H:i:s" {{ $settings['time_format'] == 'H:i:s' ? 'selected' : '' }}>24-hour (HH:MM:SS)</option>
                                    <option value="h:i:s A" {{ $settings['time_format'] == 'h:i:s A' ? 'selected' : '' }}>12-hour (HH:MM:SS AM/PM)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Preview</label>
                                <input type="text" class="form-control form-control-lg" value="{{ now()->format($settings['date_format'] . ' ' . str_replace(':s', '', $settings['time_format'])) }}" readonly>
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Currency Settings</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Currency Code <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select form-select-lg" required>
                                    <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="KES" {{ $settings['currency'] == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                    <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ $settings['currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="INR" {{ $settings['currency'] == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                    <option value="AED" {{ $settings['currency'] == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Currency Symbol <span class="text-danger">*</span></label>
                                <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" class="form-control form-control-lg" required placeholder="e.g., $, €, £">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('hms.settings.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg px-5" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: white;">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current time display
    function updateTime() {
        const now = new Date();
        document.getElementById('currentTime').textContent = now.toLocaleTimeString();
    }
    updateTime();
    setInterval(updateTime, 1000);
});
</script>
@endsection
