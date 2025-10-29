@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); box-shadow: 0 10px 30px rgba(168, 85, 247, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-star me-3"></i>Features Page CMS
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#" class="text-white-50">Frontend CMS</a></li>
                                <li class="breadcrumb-item text-white active">Features Page</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ route('features') }}" target="_blank" class="btn btn-light btn-lg px-4">
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
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                            <i class="fas fa-edit me-1"></i>
                        </span>
                        Edit Features Page Content
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cms.features.update') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold text-dark">Page Content</label>
                            <textarea 
                                id="content" 
                                name="content" 
                                class="form-control @error('content') is-invalid @enderror" 
                                rows="15"
                                placeholder="Enter your features page content here (HTML allowed)...">{{ old('content', $content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">You can use HTML tags for formatting.</small>
                        </div>

                        <hr class="my-5">

                        <h5 class="fw-bold text-dark mb-4"><i class="fas fa-search me-2"></i>SEO Settings</h5>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="meta_title" class="form-label fw-bold text-dark">Meta Title</label>
                                <input 
                                    type="text" 
                                    id="meta_title" 
                                    name="meta_title" 
                                    class="form-control form-control-lg @error('meta_title') is-invalid @enderror" 
                                    value="{{ old('meta_title', $metaTitle) }}"
                                    placeholder="Our Features - {{ config('app.name') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Recommended: 50-60 characters</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="meta_description" class="form-label fw-bold text-dark">Meta Description</label>
                                <textarea 
                                    id="meta_description" 
                                    name="meta_description" 
                                    class="form-control @error('meta_description') is-invalid @enderror" 
                                    rows="3"
                                    placeholder="A brief description of your features page...">{{ old('meta_description', $metaDescription) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> The Features page displays your hospital's key features and services. 
                            You can customize the content and SEO settings here. The frontend page will automatically reflect these changes.
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); border: none; color: white;">
                                <i class="fas fa-save me-2"></i>Save Changes
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
        border-color: #a855f7;
        box-shadow: 0 0 0 0.2rem rgba(168, 85, 247, 0.25);
    }
</style>
@endsection

