@extends('admin.layouts.app')

@section('title', 'Queue Token Generated')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-check fa-4x text-white"></i>
                        </div>
                    </div>

                    <!-- Queue Number Display -->
                    <h1 class="display-3 fw-bold text-primary mb-3">{{ $queue->queue_number }}</h1>
                    
                    <h4 class="mb-4">{{ $queue->patient_name }}</h4>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-building me-2"></i>
                        <strong>Department:</strong> {{ $queue->department }}
                    </div>

                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Status:</strong> Waiting
                        <br>
                        <small>Please wait for your number to be called</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button onclick="window.print()" class="btn btn-primary btn-lg">
                            <i class="fas fa-print me-2"></i>Print Token
                        </button>
                        <a href="{{ route('hms.queue.token-generation') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-plus me-2"></i>Generate Another Token
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .page-header-box, .btn, nav, footer {
        display: none !important;
    }
    .card {
        border: 2px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

