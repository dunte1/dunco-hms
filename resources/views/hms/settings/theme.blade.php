@extends('admin.layouts.app')

@section('title', 'Theme & Branding')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-palette me-3"></i>Theme & Branding
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Theme</li>
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
                        <span class="badge bg-pink-subtle text-pink px-3 py-2 me-3">
                            <i class="fas fa-palette me-1"></i>
                        </span>
                        Customize Appearance
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hms.system.theme.update') }}" enctype="multipart/form-data" id="themeForm">
                        @csrf

                        <h5 class="fw-bold text-dark mb-4">Color Scheme</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Primary Color <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}" class="form-control form-control-color" style="height: 60px;" required>
                                    <input type="text" value="{{ old('primary_color', $settings['primary_color']) }}" class="form-control" readonly>
                                </div>
                                <small class="text-muted">Main brand color used throughout the application</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Secondary Color <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="color" name="secondary_color" value="{{ old('secondary_color', $settings['secondary_color']) }}" class="form-control form-control-color" style="height: 60px;" required>
                                    <input type="text" value="{{ old('secondary_color', $settings['secondary_color']) }}" class="form-control" readonly>
                                </div>
                                <small class="text-muted">Secondary brand color for accents</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Color Preview</label>
                                <div class="p-4 rounded border" style="background: linear-gradient(135deg, {{ $settings['primary_color'] }} 0%, {{ $settings['secondary_color'] }} 100%); min-height: 100px;">
                                    <p class="text-white mb-0 fw-bold">Your Brand Gradient Preview</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Hospital Branding</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Hospital Logo</label>
                                <div class="upload-area" id="logoUploadArea">
                                    <input type="file" name="hospital_logo" id="hospital_logo" class="form-control form-control-lg d-none" accept="image/*" onchange="handleLogoUpload(this)">
                                    <input type="hidden" name="hospital_logo_cropped" id="hospital_logo_cropped">
                                    <div class="upload-placeholder" onclick="document.getElementById('hospital_logo').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="mb-2">Click to upload hospital logo</p>
                                        <small class="text-muted">Recommended size: 200x60 pixels (PNG, JPG)</small>
                                    </div>
                                    <div class="upload-preview d-none" id="logoPreview">
                                        <img id="logoPreviewImg" src="" alt="Logo Preview" class="img-thumbnail" style="max-width: 200px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openCropModal('logo')">
                                                <i class="fas fa-crop me-1"></i>Crop Logo
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLogo()">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if($settings['hospital_logo'])
                                    <div class="mt-3">
                                        <p class="text-success mb-1"><i class="fas fa-check-circle me-1"></i>Current Logo:</p>
                                        @php
                                            $logoUrl = $settings['hospital_logo'];
                                            if (!str_starts_with($logoUrl, 'http') && !str_starts_with($logoUrl, '/')) {
                                                $logoUrl = asset('storage/' . $logoUrl);
                                            } elseif (str_starts_with($logoUrl, '/storage/')) {
                                                $logoUrl = asset($logoUrl);
                                            }
                                        @endphp
                                        <img src="{{ $logoUrl }}" alt="Current Logo" class="img-thumbnail" style="max-width: 200px;" onerror="this.parentElement.style.display='none';">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Favicon</label>
                                <div class="upload-area" id="faviconUploadArea">
                                    <input type="file" name="favicon" id="favicon" class="form-control form-control-lg d-none" accept="image/*" onchange="previewFavicon(this)">
                                    <div class="upload-placeholder" onclick="document.getElementById('favicon').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="mb-2">Click to upload favicon</p>
                                        <small class="text-muted">Recommended size: 32x32 pixels (ICO, PNG)</small>
                                    </div>
                                    <div class="upload-preview d-none" id="faviconPreview">
                                        <img id="faviconPreviewImg" src="" alt="Favicon Preview" class="img-thumbnail" style="width: 32px; height: 32px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFavicon()">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if($settings['favicon'])
                                    <div class="mt-3">
                                        <p class="text-success mb-1"><i class="fas fa-check-circle me-1"></i>Current Favicon:</p>
                                        @php
                                            $faviconUrl = $settings['favicon'];
                                            if (!str_starts_with($faviconUrl, 'http') && !str_starts_with($faviconUrl, '/')) {
                                                $faviconUrl = asset('storage/' . $faviconUrl);
                                            } elseif (str_starts_with($faviconUrl, '/storage/')) {
                                                $faviconUrl = asset($faviconUrl);
                                            }
                                        @endphp
                                        <img src="{{ $faviconUrl }}" alt="Current Favicon" class="img-thumbnail" style="width: 32px; height: 32px;" onerror="this.parentElement.style.display='none';">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4">Display Preferences</h5>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="dark_mode" id="darkMode" value="1" {{ $settings['dark_mode'] ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="darkMode">
                                    Enable Dark Mode
                                </label>
                            </div>
                            <small class="text-muted">Allow users to switch between light and dark themes</small>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2"></i>Branding Tips:</h6>
                            <ul class="mb-0">
                                <li>Use high-contrast colors for better accessibility</li>
                                <li>Ensure your logo is clear and readable at all sizes</li>
                                <li>Test color combinations for colorblind users</li>
                                <li>Dark mode is recommended for reduced eye strain</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('hms.settings.index') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border: none; color: white;" id="saveBtn">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Cropper Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="cropImage" src="" style="max-width: 100%; max-height: 500px;">
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">Drag to adjust, scroll to zoom</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropAndSaveBtn">
                    <i class="fas fa-check me-2"></i>Crop & Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" integrity="sha512-cyzxRvewl+FOKTtpBzYjW6x6IAYUCZy3sGP40hn+DQkqeluGRCax7qztK2ImL64SA+C7kVWdLI6wvdlStawhyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Cropper.js JS - Load before scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-6lplKUSl86rUVprDIjiW8DuOniNX8UDoRATqZSds/7t6zCQZfaCe3e5zcGaQwxa8Kpn5RTM9Fvl3X2lLV4grPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
    }

    .form-control-color {
        cursor: pointer;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #ec4899;
        background-color: #f8f9fa;
    }

    .upload-placeholder {
        cursor: pointer;
    }

    .upload-preview img {
        border-radius: 8px;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Dark mode styles */
    .dark {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
    }

    .dark .card {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
        color: #ffffff !important;
    }

    .dark .card-header {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }

    .dark .text-dark {
        color: #ffffff !important;
    }

    .dark .text-muted {
        color: #b0b0b0 !important;
    }

    .dark .form-label {
        color: #ffffff !important;
    }

    .dark .form-control {
        background-color: #3d3d3d !important;
        border-color: #404040 !important;
        color: #ffffff !important;
    }

    .dark .form-control:focus {
        background-color: #3d3d3d !important;
        border-color: #ec4899 !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25) !important;
    }

    .dark .form-control-color {
        background-color: #3d3d3d !important;
        border-color: #404040 !important;
    }

    .dark .upload-area {
        border-color: #404040 !important;
        background-color: #2d2d2d !important;
        color: #ffffff !important;
    }

    .dark .upload-area:hover {
        border-color: #ec4899 !important;
        background-color: #3d3d3d !important;
    }

    .dark .upload-placeholder {
        color: #ffffff !important;
    }

    .dark .upload-placeholder .text-muted {
        color: #b0b0b0 !important;
    }

    .dark .btn-secondary {
        background-color: #6b7280 !important;
        border-color: #6b7280 !important;
        color: #ffffff !important;
    }

    .dark .btn-secondary:hover {
        background-color: #4b5563 !important;
        border-color: #4b5563 !important;
        color: #ffffff !important;
    }

    .dark .alert-info {
        background-color: #1e3a8a !important;
        border-color: #3b82f6 !important;
        color: #dbeafe !important;
    }

    .dark .alert-success {
        background-color: #166534 !important;
        border-color: #16a34a !important;
        color: #dcfce7 !important;
    }

    .dark .alert-danger {
        background-color: #991b1b !important;
        border-color: #dc2626 !important;
        color: #fecaca !important;
    }

    .dark .alert-warning {
        background-color: #92400e !important;
        border-color: #d97706 !important;
        color: #fef3c7 !important;
    }

    .dark .form-check-label {
        color: #ffffff !important;
    }

    .dark .form-check-input:checked {
        background-color: #ec4899 !important;
        border-color: #ec4899 !important;
    }

    .dark .form-check-input {
        background-color: #3d3d3d !important;
        border-color: #404040 !important;
    }

    .dark .small {
        color: #b0b0b0 !important;
    }

    .dark .breadcrumb {
        background-color: transparent !important;
    }

    .dark .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .dark .breadcrumb-item.active {
        color: #ffffff !important;
    }

    /* Cropper Modal Styles */
    #cropModal .modal-body {
        position: relative;
        min-height: 400px;
    }

    #cropImage {
        max-width: 100%;
        display: block;
    }

    .cropper-container {
        direction: ltr !important;
    }

    .cropper-crop-box,
    .cropper-view-box {
        border-radius: 4px;
    }

    .cropper-face {
        background-color: inherit !important;
    }

    .cropper-line {
        background-color: #ec4899;
    }

    .cropper-point {
        background-color: #ec4899;
        border-color: #ec4899;
    }

    .cropper-point:hover,
    .cropper-point.point-se {
        background-color: #db2777;
    }
</style>

<script>
// Color preview functionality
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeColorPreview);
} else {
    initializeColorPreview();
}

function initializeColorPreview() {
    try {
        const primaryColor = document.querySelector('input[name="primary_color"]');
        const secondaryColor = document.querySelector('input[name="secondary_color"]');
        const preview = document.querySelector('.border');
        
        if (primaryColor && secondaryColor && preview) {
            function updatePreview() {
                preview.style.background = `linear-gradient(135deg, ${primaryColor.value} 0%, ${secondaryColor.value} 100%)`;
            }
            
            primaryColor.addEventListener('input', updatePreview);
            secondaryColor.addEventListener('input', updatePreview);
            
            // Initialize preview on page load
            updatePreview();
        }
    } catch (error) {
        console.warn('Color preview initialization failed:', error);
    }
}

// Global variables for cropper
window.cropper = null;
window.currentCropType = null;
window.originalImageData = null;

// Handle logo upload
function handleLogoUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            window.originalImageData = e.target.result;
            document.getElementById('logoPreviewImg').src = e.target.result;
            document.getElementById('logoPreview').classList.remove('d-none');
            document.querySelector('#logoUploadArea .upload-placeholder').classList.add('d-none');
            
            // Show crop button
            const cropBtn = document.querySelector('#logoPreview .btn-primary');
            if (cropBtn) cropBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFavicon(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('faviconPreviewImg').src = e.target.result;
            document.getElementById('faviconPreview').classList.remove('d-none');
            document.querySelector('#faviconUploadArea .upload-placeholder').classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeLogo() {
    document.getElementById('hospital_logo').value = '';
    document.getElementById('hospital_logo_cropped').value = '';
    document.getElementById('logoPreview').classList.add('d-none');
    document.querySelector('#logoUploadArea .upload-placeholder').classList.remove('d-none');
    window.originalImageData = null;
}

// Open crop modal with proper Cropper initialization
function openCropModal(type) {
    if (typeof Cropper === 'undefined') {
        alert('Image cropper library failed to load. Please refresh the page.');
        return;
    }
    
    window.currentCropType = type;
    const modalElement = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    
    // Set image source based on type
    if (type === 'logo') {
        cropImage.src = window.originalImageData || document.getElementById('logoPreviewImg').src;
    }
    
    const modal = new bootstrap.Modal(modalElement);
    
    // Clean up cropper when modal is hidden
    modalElement.addEventListener('hidden.bs.modal', function() {
        if (window.cropper) {
            window.cropper.destroy();
            window.cropper = null;
        }
    }, { once: true });
    
    modal.show();
    
    // Initialize cropper after modal is fully shown
    modalElement.addEventListener('shown.bs.modal', function() {
        // Destroy existing cropper if any
        if (window.cropper) {
            window.cropper.destroy();
        }
        
        // Wait a bit for image to load
        setTimeout(function() {
            try {
                window.cropper = new Cropper(cropImage, {
                    aspectRatio: type === 'logo' ? NaN : 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleable: true,
                    minCropBoxWidth: 50,
                    minCropBoxHeight: 50,
                    ready: function() {
                        console.log('Cropper ready');
                    }
                });
            } catch(e) {
                console.error('Cropper initialization error:', e);
                alert('Failed to initialize image cropper: ' + e.message);
            }
        }, 300);
    }, { once: true });
}

function removeFavicon() {
    document.getElementById('favicon').value = '';
    document.getElementById('faviconPreview').classList.add('d-none');
    document.querySelector('#faviconUploadArea .upload-placeholder').classList.remove('d-none');
}

// Handle cropped image in form submission
document.getElementById('themeForm').addEventListener('submit', function(e) {
    const saveBtn = document.getElementById('saveBtn');
    const form = this;

    // Convert base64 cropped image to blob if exists
    const croppedLogo = document.getElementById('hospital_logo_cropped').value;
    if (croppedLogo) {
        // Convert base64 to blob
        fetch(croppedLogo)
            .then(res => res.blob())
            .then(blob => {
                try {
                    const file = new File([blob], 'hospital-logo.png', { type: 'image/png' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('hospital_logo').files = dataTransfer.files;
                    submitForm();
                    // Actually submit the form after updating files
                    form.submit();
                } catch (err) {
                    restoreSaveBtn();
                    alert('There was an error uploading the logo.');
                }
            })
            .catch(() => {
                restoreSaveBtn();
                alert('There was an error processing the cropped logo.');
            });
        e.preventDefault();
        return false;
    } else {
        submitForm();
    }

    function submitForm() {
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';

        // Add hidden input for dark mode if unchecked
        const darkModeInput = document.getElementById('darkMode');
        if (!darkModeInput.checked) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'dark_mode';
            hiddenInput.value = '0';
            form.appendChild(hiddenInput);
        }

        // Re-enable button after 10 seconds as fallback
        setTimeout(restoreSaveBtn, 10000);
    }

    function restoreSaveBtn() {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Settings';
    }
});
</script>

@endsection

<script>
// Initialize crop and save button handler on page load
document.addEventListener('DOMContentLoaded', function() {
    const cropBtn = document.getElementById('cropAndSaveBtn');
    if (cropBtn && typeof Cropper !== 'undefined') {
        cropBtn.addEventListener('click', function() {
            if (!window.cropper) {
                alert('Cropper not initialized. Please try cropping again.');
                return;
            }
            
            try {
                const canvas = window.cropper.getCroppedCanvas({
                    width: window.currentCropType === 'logo' ? 400 : 256,
                    height: window.currentCropType === 'logo' ? 120 : 256,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                
                canvas.toBlob(function(blob) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const croppedData = e.target.result;
                        
                        if (window.currentCropType === 'logo') {
                            document.getElementById('logoPreviewImg').src = croppedData;
                            document.getElementById('hospital_logo_cropped').value = croppedData;
                            // Create a new File from blob to replace the original
                            const file = new File([blob], 'cropped-logo.png', { type: 'image/png' });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            document.getElementById('hospital_logo').files = dataTransfer.files;
                        }
                        
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
                        
                        if (window.cropper) {
                            window.cropper.destroy();
                            window.cropper = null;
                        }
                    };
                    reader.readAsDataURL(blob);
                }, 'image/png', 0.95);
            } catch(e) {
                console.error('Error cropping:', e);
                alert('Error cropping image: ' + e.message);
            }
        });
    }
});
</script>
