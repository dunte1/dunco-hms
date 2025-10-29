@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-search mr-2"></i> SEO Settings</h4>
                    <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cms.seo.update') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="site_title" class="form-label">Site Title</label>
                            <input 
                                type="text" 
                                id="site_title" 
                                name="site_title" 
                                class="form-control @error('site_title') is-invalid @enderror" 
                                value="{{ old('site_title', $settings['site_title']) }}">
                            @error('site_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea 
                                id="site_description" 
                                name="site_description" 
                                class="form-control @error('site_description') is-invalid @enderror" 
                                rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site_keywords" class="form-label">Site Keywords</label>
                            <input 
                                type="text" 
                                id="site_keywords" 
                                name="site_keywords" 
                                class="form-control @error('site_keywords') is-invalid @enderror" 
                                value="{{ old('site_keywords', $settings['site_keywords']) }}"
                                placeholder="keyword1, keyword2, keyword3">
                            @error('site_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="google_analytics" class="form-label">Google Analytics ID</label>
                            <input 
                                type="text" 
                                id="google_analytics" 
                                name="google_analytics" 
                                class="form-control @error('google_analytics') is-invalid @enderror" 
                                value="{{ old('google_analytics', $settings['google_analytics']) }}"
                                placeholder="G-XXXXXXXXXX">
                            @error('google_analytics')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meta_tags" class="form-label">Additional Meta Tags</label>
                            <textarea 
                                id="meta_tags" 
                                name="meta_tags" 
                                class="form-control @error('meta_tags') is-invalid @enderror" 
                                rows="5"
                                placeholder='<meta name="author" content="Your Name">'>{!! old('meta_tags', $settings['meta_tags']) !!}</textarea>
                            @error('meta_tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter additional meta tags as HTML.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

