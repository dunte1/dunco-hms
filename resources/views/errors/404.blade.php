@extends('admin.layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <div class="error-code mb-4">
                    <h1 class="display-1 fw-bold text-primary" style="font-size: 120px;">404</h1>
                </div>
                <div class="error-message mb-4">
                    <h2 class="h3 mb-3">Page Not Found</h2>
                    <p class="text-muted mb-4">
                        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                    </p>
                </div>
                <div class="error-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-2"></i>Go to Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

