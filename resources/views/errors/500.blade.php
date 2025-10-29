@extends('admin.layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <div class="error-code mb-4">
                    <h1 class="display-1 fw-bold text-danger" style="font-size: 120px;">500</h1>
                </div>
                <div class="error-message mb-4">
                    <h2 class="h3 mb-3">Internal Server Error</h2>
                    <p class="text-muted mb-4">
                        We're sorry, but something went wrong on our end. Our team has been notified and is working to fix the issue.
                    </p>
                </div>
                <div class="error-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-2"></i>Go to Dashboard
                    </a>
                    <a href="javascript:location.reload()" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-redo me-2"></i>Reload Page
                    </a>
                    @if(config('app.debug'))
                    <div class="mt-4">
                        <small class="text-muted">Error details are only shown in debug mode.</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

