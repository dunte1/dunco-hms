@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-header me-3"></i>Header & Footer Settings
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#" class="text-white-50">Frontend CMS</a></li>
                                <li class="breadcrumb-item text-white active">Header & Footer</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-external-link-alt me-2"></i>View Frontend Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CMS Form -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form action="{{ route('cms.header-footer.update') }}" method="POST">
                @csrf

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Header Settings -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-arrow-up me-2"></i>Header Settings
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Open Hours Display Text <span class="text-danger">*</span></label>
                            <input type="text" name="header_open_hours" value="{{ old('header_open_hours', $settings['header_open_hours']) }}" class="form-control form-control-lg" required>
                            <small class="text-muted">This text appears at the top of the site header (e.g., "Mon–Fri 8:00–18:00" or "Open Hours: Mon–Fri 8:00–18:00")</small>
                            @error('header_open_hours')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Emergency Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="header_emergency_phone" value="{{ old('header_emergency_phone', $settings['header_emergency_phone']) }}" class="form-control form-control-lg" required>
                            <small class="text-muted">Emergency contact number displayed in the header (e.g., "+254 700 000 000")</small>
                            @error('header_emergency_phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-arrow-down me-2"></i>Footer Settings
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">About Section</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">About Text</label>
                            <textarea name="footer_about_text" class="form-control" rows="3">{{ old('footer_about_text', $settings['footer_about_text']) }}</textarea>
                            <small class="text-muted">Additional text for the footer about section (optional)</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Departments Links</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Departments (JSON format)</label>
                            <textarea name="footer_departments" class="form-control font-monospace" rows="8">@php
$depts = json_decode($settings['footer_departments'], true);
if (is_array($depts)) {
    echo json_encode($depts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo $settings['footer_departments'];
}
@endphp</textarea>
                            <small class="text-muted">Format: [{"name":"Cardiology","link":"#cardiology"},{"name":"Radiology","link":"#radiology"}]</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Patient Links</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Patient Links (JSON format)</label>
                            <textarea name="footer_patient_links" class="form-control font-monospace" rows="8">@php
$links = json_decode($settings['footer_patient_links'], true);
if (is_array($links)) {
    echo json_encode($links, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo $settings['footer_patient_links'];
}
@endphp</textarea>
                            <small class="text-muted">Format: [{"name":"Book Appointment","link":"/book-appointment"}]</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Legal Links</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Legal Links (JSON format)</label>
                            <textarea name="footer_legal_links" class="form-control font-monospace" rows="6">@php
$legal = json_decode($settings['footer_legal_links'], true);
if (is_array($legal)) {
    echo json_encode($legal, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo $settings['footer_legal_links'];
}
@endphp</textarea>
                            <small class="text-muted">Format: [{"name":"Terms of Service","link":"#"}]</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Copyright & Social Media</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Copyright Text</label>
                            <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright']) }}" class="form-control">
                            <small class="text-muted">Copyright text displayed at the bottom of the footer</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Facebook URL</label>
                                <input type="url" name="footer_social_facebook" value="{{ old('footer_social_facebook', $settings['footer_social_facebook']) }}" class="form-control" placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Twitter/X URL</label>
                                <input type="url" name="footer_social_twitter" value="{{ old('footer_social_twitter', $settings['footer_social_twitter']) }}" class="form-control" placeholder="https://twitter.com/yourpage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Instagram URL</label>
                                <input type="url" name="footer_social_instagram" value="{{ old('footer_social_instagram', $settings['footer_social_instagram']) }}" class="form-control" placeholder="https://instagram.com/yourpage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">LinkedIn URL</label>
                                <input type="url" name="footer_social_linkedin" value="{{ old('footer_social_linkedin', $settings['footer_social_linkedin']) }}" class="form-control" placeholder="https://linkedin.com/company/yourpage">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="footer_newsletter_enabled" value="1" id="newsletterEnabled" {{ old('footer_newsletter_enabled', $settings['footer_newsletter_enabled']) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="newsletterEnabled">
                                    Enable Newsletter Subscription Form
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('cms.home') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to CMS
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Save Header & Footer Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

