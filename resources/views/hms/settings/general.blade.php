@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-hospital me-3"></i>General Settings
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">General</li>
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
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 me-3">
                            <i class="fas fa-cog me-1"></i>
                        </span>
                        Hospital Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hms.settings.general.update') }}">
                        @csrf

                        <h5 class="fw-bold text-dark mb-4">System Information</h5>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">System Name <span class="text-danger">*</span></label>
                                <input type="text" name="system_name" value="{{ old('system_name', $settings['system_name'] ?? 'DuncoHMS') }}" class="form-control form-control-lg" required>
                                <small class="text-muted">This is the name of the software system (your company branding). This will appear in the footer and about sections.</small>
                                @error('system_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">System Developer/Copyright <span class="text-danger">*</span></label>
                                <input type="text" name="system_developer" value="{{ old('system_developer', $settings['system_developer'] ?? 'Dunco Technologies') }}" class="form-control form-control-lg" required>
                                <small class="text-muted">Company/Developer name for copyright and branding purposes.</small>
                                @error('system_developer')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Hospital Details</h5>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">Hospital Name <span class="text-danger">*</span></label>
                                <input type="text" name="hospital_name" value="{{ old('hospital_name', $settings['hospital_name']) }}" class="form-control form-control-lg" required>
                                <small class="text-muted">The name of the hospital/clinic using this system. This will appear on invoices, reports, and the sidebar header.</small>
                                @error('hospital_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Hospital Address <span class="text-danger">*</span></label>
                            <textarea name="hospital_address" class="form-control" rows="3" required>{{ old('hospital_address', $settings['hospital_address']) }}</textarea>
                            @error('hospital_address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="hospital_phone" value="{{ old('hospital_phone', $settings['hospital_phone']) }}" class="form-control form-control-lg" required>
                                @error('hospital_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Email <span class="text-danger">*</span></label>
                                <input type="email" name="hospital_email" value="{{ old('hospital_email', $settings['hospital_email']) }}" class="form-control form-control-lg" required>
                                @error('hospital_email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Regional Settings</h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select form-select-lg" required>
                                    <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="KES" {{ $settings['currency'] == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                    <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ $settings['currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Timezone <span class="text-danger">*</span></label>
                                <select name="timezone" class="form-select form-select-lg" required>
                                    <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Africa/Nairobi" {{ $settings['timezone'] == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                                    <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Date Format <span class="text-danger">*</span></label>
                                <input type="text" name="date_format" value="{{ old('date_format', $settings['date_format']) }}" class="form-control form-control-lg" required placeholder="Y-m-d">
                                <small class="text-muted">e.g., Y-m-d, m/d/Y, d/m/Y</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Time Format <span class="text-danger">*</span></label>
                                <input type="text" name="time_format" value="{{ old('time_format', $settings['time_format']) }}" class="form-control form-control-lg" required placeholder="H:i:s">
                                <small class="text-muted">e.g., H:i:s (24-hour), h:i:s A (12-hour)</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('hms.settings.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none;">
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }
</style>
@endsection
