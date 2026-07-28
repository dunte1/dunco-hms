@extends('admin.layouts.app')

@section('title', 'View Performance Appraisal')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-eye me-3"></i>Performance Appraisal Details
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.appraisals.index') }}" class="text-white-50">Appraisals</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hms.hr.appraisals.edit', $appraisal) }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.appraisals.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                                <i class="fas fa-user text-white fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="fw-bold text-dark mb-1">{{ $appraisal->employee->first_name }} {{ $appraisal->employee->last_name }}</h4>
                            <p class="text-muted mb-2">{{ $appraisal->employee->position }}</p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-info px-3 py-2">{{ $appraisal->employee->department->name ?? 'N/A' }}</span>
                                <span class="badge {{ $appraisal->promotion_recommended ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                    {{ $appraisal->promotion_recommended ? 'Promotion Recommended' : 'Standard Review' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $appraisal->status == 'approved' ? 'bg-success' : ($appraisal->status == 'reviewed' ? 'bg-info' : ($appraisal->status == 'submitted' ? 'bg-warning' : 'bg-secondary')) }} px-4 py-3 fs-6">
                                {{ ucfirst($appraisal->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appraisal Details -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Overall Rating Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                        <i class="fas fa-star text-warning me-2"></i>
                        Overall Rating
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="text-center p-4" style="background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%); border-radius: 12px;">
                                @if($appraisal->overall_score)
                                    <h1 class="fw-bold mb-2" style="color: #6d28d9;">{{ $appraisal->overall_score }}</h1>
                                    <p class="text-muted mb-0">Out of 100</p>
                                @else
                                    <p class="text-muted mb-0">Not rated</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-4" style="background: #f8fafc; border-radius: 12px;">
                                <p class="text-muted mb-2">Rating</p>
                                @if($appraisal->overall_rating)
                                    <span class="badge px-4 py-3 fs-6" 
                                          style="background: {{ $appraisal->overall_rating == 'excellent' ? '#d1fae5' : ($appraisal->overall_rating == 'good' ? '#fef3c7' : '#fee2e2') }}; 
                                                 color: {{ $appraisal->overall_rating == 'excellent' ? '#065f46' : ($appraisal->overall_rating == 'good' ? '#78350f' : '#991b1b') }};">
                                        {{ ucfirst(str_replace('_', ' ', $appraisal->overall_rating)) }}
                                    </span>
                                @else
                                    <span class="text-muted">Not rated</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strengths Card -->
            @if($appraisal->strengths)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Strengths
                    </h5>
                    <p class="mb-0">{{ $appraisal->strengths }}</p>
                </div>
            </div>
            @endif

            <!-- Areas for Improvement Card -->
            @if($appraisal->areas_for_improvement)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Areas for Improvement
                    </h5>
                    <p class="mb-0">{{ $appraisal->areas_for_improvement }}</p>
                </div>
            </div>
            @endif

            <!-- Goals Card -->
            @if($appraisal->goals_for_next_period)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                        <i class="fas fa-bullseye text-primary me-2"></i>
                        Goals for Next Period
                    </h5>
                    <p class="mb-0">{{ $appraisal->goals_for_next_period }}</p>
                </div>
            </div>
            @endif

            <!-- Comments -->
            @if($appraisal->appraiser_comments || $appraisal->hr_comments)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Comments</h5>
                    
                    @if($appraisal->appraiser_comments)
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-user-tie me-2"></i>
                            Appraiser Comments
                        </h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $appraisal->appraiser_comments }}</p>
                            @if($appraisal->appraiser)
                                <small class="text-muted">By: {{ $appraisal->appraiser->name }}</small>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($appraisal->hr_comments)
                    <div>
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-building me-2"></i>
                            HR Comments
                        </h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $appraisal->hr_comments }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Appraisal Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Appraisal Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Review Period</p>
                        <p class="fw-bold mb-0">{{ $appraisal->review_period }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Appraisal Date</p>
                        <p class="fw-bold mb-0">{{ $appraisal->appraisal_date->format('F d, Y') }}</p>
                    </div>
                    
                    @if($appraisal->period_start && $appraisal->period_end)
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Period</p>
                        <p class="fw-bold mb-0">
                            {{ $appraisal->period_start->format('M d, Y') }} - {{ $appraisal->period_end->format('M d, Y') }}
                        </p>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Status</p>
                        <span class="badge {{ $appraisal->status == 'approved' ? 'bg-success' : ($appraisal->status == 'reviewed' ? 'bg-info' : ($appraisal->status == 'submitted' ? 'bg-warning' : 'bg-secondary')) }} px-3 py-2">
                            {{ ucfirst($appraisal->status) }}
                        </span>
                    </div>
                    
                    @if($appraisal->promotion_recommended)
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Recommendation</p>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-trophy me-1"></i>Promotion Recommended
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Promotion Notes -->
            @if($appraisal->promotion_recommended && $appraisal->promotion_notes)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="fas fa-trophy me-2"></i>Promotion Notes
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0">{{ $appraisal->promotion_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Created</p>
                        <p class="fw-bold mb-0">{{ $appraisal->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    
                    @if($appraisal->submitted_at)
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Submitted</p>
                        <p class="fw-bold mb-0">{{ $appraisal->submitted_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                    
                    @if($appraisal->reviewed_at)
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Reviewed</p>
                        <p class="fw-bold mb-0">{{ $appraisal->reviewed_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                    
                    @if($appraisal->approved_at)
                    <div>
                        <p class="text-muted mb-1 small">Approved</p>
                        <p class="fw-bold mb-0">{{ $appraisal->approved_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
</style>
@endsection

