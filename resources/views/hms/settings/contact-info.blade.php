@extends('admin.layouts.app')

@section('title', 'Contact Information Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-phone-alt me-3"></i>Contact Information Settings
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Contact Info</li>
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
                            <i class="fas fa-info-circle me-1"></i>
                        </span>
                        Public Contact Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hms.system.contact-info.update') }}">
                        @csrf

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This information will be displayed on the public contact page and can be used throughout the website.
                        </div>

                        <h5 class="fw-bold text-dark mb-4">Primary Contact Details</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Primary Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_primary_phone" value="{{ old('contact_primary_phone', $settings['contact_primary_phone']) }}" class="form-control form-control-lg" required>
                                <small class="text-muted">Main phone number displayed on contact page</small>
                                @error('contact_primary_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Emergency Phone</label>
                                <input type="tel" name="contact_emergency_phone" value="{{ old('contact_emergency_phone', $settings['contact_emergency_phone']) }}" class="form-control form-control-lg">
                                <small class="text-muted">Emergency/24-hour contact number</small>
                                @error('contact_emergency_phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Primary Email <span class="text-danger">*</span></label>
                            <input type="email" name="contact_primary_email" value="{{ old('contact_primary_email', $settings['contact_primary_email']) }}" class="form-control form-control-lg" required>
                            <small class="text-muted">Main contact email address</small>
                            @error('contact_primary_email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Primary Address <span class="text-danger">*</span></label>
                            <textarea name="contact_primary_address" class="form-control" rows="3" required>{{ old('contact_primary_address', $settings['contact_primary_address']) }}</textarea>
                            <small class="text-muted">Full hospital address displayed on contact page</small>
                            @error('contact_primary_address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Office Hours</h5>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Office Hours</label>
                            <textarea name="contact_office_hours" class="form-control" rows="7" placeholder="Monday: 8:00 AM - 6:00 PM&#10;Tuesday: 8:00 AM - 6:00 PM&#10;Wednesday: 8:00 AM - 6:00 PM&#10;Thursday: 8:00 AM - 6:00 PM&#10;Friday: 8:00 AM - 6:00 PM&#10;Saturday: 9:00 AM - 2:00 PM&#10;Sunday: Closed">@php
$hours = old('contact_office_hours', $settings['contact_office_hours']);
if (is_string($hours) && str_starts_with($hours, '{')) {
    $hoursArray = json_decode($hours, true);
    if ($hoursArray && is_array($hoursArray)) {
        echo implode("\n", array_map(function($day, $time) { return ucfirst($day) . ': ' . $time; }, array_keys($hoursArray), $hoursArray));
    } else {
        echo $hours;
    }
} else {
    echo $hours;
}
@endphp</textarea>
                            <small class="text-muted">Enter office hours for each day (one per line). Example: Monday: 8:00 AM - 6:00 PM</small>
                            @error('contact_office_hours')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Social Media Links</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Facebook URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-facebook text-primary"></i></span>
                                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}" class="form-control form-control-lg" placeholder="https://facebook.com/yourpage">
                                </div>
                                @error('social_facebook')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Twitter/X URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-twitter text-info"></i></span>
                                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter']) }}" class="form-control form-control-lg" placeholder="https://twitter.com/yourhandle">
                                </div>
                                @error('social_twitter')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Instagram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-instagram text-danger"></i></span>
                                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}" class="form-control form-control-lg" placeholder="https://instagram.com/yourprofile">
                                </div>
                                @error('social_instagram')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">LinkedIn URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-linkedin text-primary"></i></span>
                                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin']) }}" class="form-control form-control-lg" placeholder="https://linkedin.com/company/yourcompany">
                                </div>
                                @error('social_linkedin')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">YouTube URL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                <input type="url" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube']) }}" class="form-control form-control-lg" placeholder="https://youtube.com/channel/yourchannel">
                            </div>
                            @error('social_youtube')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5">
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

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
</style>
@endsection

