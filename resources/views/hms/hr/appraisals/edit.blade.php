@extends('admin.layouts.app')

@section('title', 'Edit Performance Appraisal')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-edit me-3"></i>Edit Performance Appraisal
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.appraisals.index') }}" class="text-white-50">Appraisals</a></li>
                                <li class="breadcrumb-item text-white active">Edit</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('hms.hr.appraisals.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.appraisals.update', $appraisal) }}" method="POST" id="appraisalForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 me-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                </span>
                                Basic Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="employee_id" class="form-label fw-bold text-dark">
                                        Employee <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('employee_id') is-invalid @enderror" 
                                            id="employee_id" name="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_id', $appraisal->employee_id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->position }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="review_period" class="form-label fw-bold text-dark">
                                        Review Period <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('review_period') is-invalid @enderror" 
                                           id="review_period" name="review_period" value="{{ old('review_period', $appraisal->review_period) }}" 
                                           placeholder="e.g., Q1 2025" required>
                                    @error('review_period')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="appraisal_date" class="form-label fw-bold text-dark">
                                        Appraisal Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control form-control-lg @error('appraisal_date') is-invalid @enderror" 
                                           id="appraisal_date" name="appraisal_date" value="{{ old('appraisal_date', $appraisal->appraisal_date->format('Y-m-d')) }}" required>
                                    @error('appraisal_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="period_start" class="form-label fw-bold text-dark">Period Start</label>
                                    <input type="date" class="form-control form-control-lg @error('period_start') is-invalid @enderror" 
                                           id="period_start" name="period_start" value="{{ old('period_start', $appraisal->period_start?->format('Y-m-d')) }}">
                                    @error('period_start')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="period_end" class="form-label fw-bold text-dark">Period End</label>
                                    <input type="date" class="form-control form-control-lg @error('period_end') is-invalid @enderror" 
                                           id="period_end" name="period_end" value="{{ old('period_end', $appraisal->period_end?->format('Y-m-d')) }}">
                                    @error('period_end')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Overall Rating -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success px-3 py-2 me-2">
                                    <i class="fas fa-star me-1"></i>
                                </span>
                                Overall Rating
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="overall_score" class="form-label fw-bold text-dark">Overall Score (0-100)</label>
                                    <input type="number" class="form-control form-control-lg @error('overall_score') is-invalid @enderror" 
                                           id="overall_score" name="overall_score" value="{{ old('overall_score', $appraisal->overall_score) }}" 
                                           min="0" max="100" step="0.01">
                                    @error('overall_score')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="overall_rating" class="form-label fw-bold text-dark">Overall Rating</label>
                                    <select class="form-select form-select-lg @error('overall_rating') is-invalid @enderror" 
                                            id="overall_rating" name="overall_rating">
                                        <option value="">Select Rating</option>
                                        <option value="excellent" {{ old('overall_rating', $appraisal->overall_rating) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('overall_rating', $appraisal->overall_rating) == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="satisfactory" {{ old('overall_rating', $appraisal->overall_rating) == 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                        <option value="needs_improvement" {{ old('overall_rating', $appraisal->overall_rating) == 'needs_improvement' ? 'selected' : '' }}>Needs Improvement</option>
                                        <option value="poor" {{ old('overall_rating', $appraisal->overall_rating) == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('overall_rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Assessment -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-info-subtle text-info px-3 py-2 me-2">
                                    <i class="fas fa-clipboard-list me-1"></i>
                                </span>
                                Detailed Assessment
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="strengths" class="form-label fw-bold text-dark">Strengths</label>
                                    <textarea class="form-control form-control-lg @error('strengths') is-invalid @enderror" 
                                              id="strengths" name="strengths" rows="4" 
                                              placeholder="List key strengths...">{{ old('strengths', $appraisal->strengths) }}</textarea>
                                    @error('strengths')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="areas_for_improvement" class="form-label fw-bold text-dark">Areas for Improvement</label>
                                    <textarea class="form-control form-control-lg @error('areas_for_improvement') is-invalid @enderror" 
                                              id="areas_for_improvement" name="areas_for_improvement" rows="4" 
                                              placeholder="List areas needing improvement...">{{ old('areas_for_improvement', $appraisal->areas_for_improvement) }}</textarea>
                                    @error('areas_for_improvement')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="goals_for_next_period" class="form-label fw-bold text-dark">Goals for Next Period</label>
                                    <textarea class="form-control form-control-lg @error('goals_for_next_period') is-invalid @enderror" 
                                              id="goals_for_next_period" name="goals_for_next_period" rows="4" 
                                              placeholder="Define clear goals...">{{ old('goals_for_next_period', $appraisal->goals_for_next_period) }}</textarea>
                                    @error('goals_for_next_period')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 me-2">
                                    <i class="fas fa-comments me-1"></i>
                                </span>
                                Comments
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="appraiser_comments" class="form-label fw-bold text-dark">Appraiser Comments</label>
                                    <textarea class="form-control form-control-lg @error('appraiser_comments') is-invalid @enderror" 
                                              id="appraiser_comments" name="appraiser_comments" rows="5" 
                                              placeholder="Write your assessment comments...">{{ old('appraiser_comments', $appraisal->appraiser_comments) }}</textarea>
                                    @error('appraiser_comments')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="hr_comments" class="form-label fw-bold text-dark">HR Comments</label>
                                    <textarea class="form-control form-control-lg @error('hr_comments') is-invalid @enderror" 
                                              id="hr_comments" name="hr_comments" rows="4" 
                                              placeholder="HR department comments...">{{ old('hr_comments', $appraisal->hr_comments) }}</textarea>
                                    @error('hr_comments')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status & Recommendations -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-bold d-flex align-items-center">
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 me-2">
                                    <i class="fas fa-flag me-1"></i>
                                </span>
                                Status & Recommendations
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold text-dark">Status</label>
                                    <select class="form-select form-select-lg @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="draft" {{ old('status', $appraisal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ old('status', $appraisal->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="reviewed" {{ old('status', $appraisal->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        <option value="approved" {{ old('status', $appraisal->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Promotion Recommendation</label>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="promotion_recommended" 
                                               name="promotion_recommended" value="1" {{ old('promotion_recommended', $appraisal->promotion_recommended) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="promotion_recommended">
                                            Recommend for Promotion
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12" id="promotion_notes_group" style="display: {{ $appraisal->promotion_recommended ? 'block' : 'none' }};">
                                    <label for="promotion_notes" class="form-label fw-bold text-dark">Promotion Notes</label>
                                    <textarea class="form-control form-control-lg @error('promotion_notes') is-invalid @enderror" 
                                              id="promotion_notes" name="promotion_notes" rows="3" 
                                              placeholder="Explain promotion recommendation...">{{ old('promotion_notes', $appraisal->promotion_notes) }}</textarea>
                                    @error('promotion_notes')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('hms.hr.appraisals.index') }}" class="btn btn-light btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Update Appraisal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle promotion notes
    const promotionCheckbox = document.getElementById('promotion_recommended');
    const promotionNotesGroup = document.getElementById('promotion_notes_group');
    
    if (promotionCheckbox) {
        promotionCheckbox.addEventListener('change', function() {
            promotionNotesGroup.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Auto-calculate rating based on score
    const overallScore = document.getElementById('overall_score');
    const overallRating = document.getElementById('overall_rating');
    
    if (overallScore && overallRating) {
        overallScore.addEventListener('change', function() {
            if (!overallRating.value) {
                const score = parseFloat(this.value);
                if (!isNaN(score)) {
                    if (score >= 90) overallRating.value = 'excellent';
                    else if (score >= 75) overallRating.value = 'good';
                    else if (score >= 60) overallRating.value = 'satisfactory';
                    else if (score >= 40) overallRating.value = 'needs_improvement';
                    else overallRating.value = 'poor';
                }
            }
        });
    }
});
</script>
@endpush
@endsection

