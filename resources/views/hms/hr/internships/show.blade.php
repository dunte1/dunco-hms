@extends('admin.layouts.app')

@section('title', 'Internship Details')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 10px 30px rgba(249, 115, 22, 0.3);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-briefcase me-3"></i>Internship Details
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.internships.index') }}" class="text-white-50">Internships</a></li>
                                <li class="breadcrumb-item text-white active">{{ $internship->internship_number }}</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('hms.hr.internships.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Intern Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                    <i class="fas fa-user text-white fs-2"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold text-dark mb-1">{{ $internship->intern_name }}</h4>
                                <p class="text-muted mb-2">{{ $internship->email }} {{ $internship->phone ? '| ' . $internship->phone : '' }}</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="badge bg-info px-3 py-2">{{ $internship->institution }}</span>
                                    <span class="badge bg-secondary px-3 py-2">{{ $internship->program }}</span>
                                    <span class="badge bg-warning px-3 py-2">{{ $internship->internship_number }}</span>
                                </div>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'active' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'play-circle'],
                                'completed' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'check-circle'],
                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'times-circle'],
                            ];
                            $color = $statusColors[$internship->status] ?? $statusColors['active'];
                        @endphp
                        <span class="badge rounded-pill px-4 py-3 fs-6" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                            <i class="fas fa-{{ $color['icon'] }} me-1"></i>{{ ucfirst($internship->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Details -->
        <div class="col-lg-8">
            <!-- Internship Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>Internship Information
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1 small">Department</p>
                            <p class="fw-bold mb-0">{{ $internship->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1 small">Supervisor</p>
                            <p class="fw-bold mb-0">{{ $internship->supervisor->first_name ?? 'N/A' }} {{ $internship->supervisor->last_name ?? '' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1 small">Start Date</p>
                            <p class="fw-bold mb-0">{{ $internship->start_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1 small">End Date</p>
                            <p class="fw-bold mb-0">{{ $internship->end_date->format('F d, Y') }}</p>
                        </div>
                        @if($internship->description)
                        <div class="col-12">
                            <p class="text-muted mb-1 small">Description</p>
                            <p class="mb-0">{{ $internship->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Evaluation Section -->
            @if($internship->status === 'active')
                <!-- Complete & Evaluate Form -->
                <div class="card border-0 shadow-sm mb-4" id="evaluationForm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-clipboard-check text-warning me-2"></i>Complete Internship
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('hms.hr.internships.complete', $internship) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="score" class="form-label fw-bold text-dark">Score (1-100) <span class="text-danger">*</span></label>
                                    <input type="range" class="form-range" id="score" name="score" min="1" max="100" value="{{ old('score', 75) }}" oninput="document.getElementById('scoreValue').textContent = this.value">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">1</small>
                                        <span class="fw-bold fs-5" id="scoreValue" style="color: #f97316;">75</span>
                                        <small class="text-muted">100</small>
                                    </div>
                                    @error('score') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="rating" class="form-label fw-bold text-dark">Rating <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                        <option value="">Select Rating</option>
                                        <option value="excellent" {{ old('rating') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('rating') == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="satisfactory" {{ old('rating') == 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                        <option value="needs_improvement" {{ old('rating') == 'needs_improvement' ? 'selected' : '' }}>Needs Improvement</option>
                                        <option value="poor" {{ old('rating') == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label for="supervisor_comments" class="form-label fw-bold text-dark">Supervisor Comments</label>
                                    <textarea class="form-control form-control-lg" id="supervisor_comments" name="supervisor_comments" rows="4" placeholder="Provide detailed feedback...">{{ old('supervisor_comments') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label for="intern_feedback" class="form-label fw-bold text-dark">Intern Feedback</label>
                                    <textarea class="form-control form-control-lg" id="intern_feedback" name="intern_feedback" rows="3" placeholder="Intern self-assessment...">{{ old('intern_feedback') }}</textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-success btn-lg px-5" onclick="return confirm('Mark this internship as complete?')">
                                    <i class="fas fa-check-circle me-2"></i>Complete & Submit Evaluation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if($internship->status === 'completed' && $internship->score)
                <!-- Evaluation Results -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-star text-warning me-2"></i>Evaluation Results
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); border-radius: 12px;">
                                    <h1 class="fw-bold mb-2" style="color: #c2410c;">{{ $internship->score }}</h1>
                                    <p class="text-muted mb-0">Score out of 100</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4" style="background: #f8fafc; border-radius: 12px;">
                                    <p class="text-muted mb-2">Rating</p>
                                    <span class="badge px-4 py-3 fs-6"
                                          style="background: {{ $internship->rating === 'excellent' ? '#d1fae5' : ($internship->rating === 'good' ? '#fef3c7' : '#fee2e2') }};
                                                 color: {{ $internship->rating === 'excellent' ? '#065f46' : ($internship->rating === 'good' ? '#78350f' : '#991b1b') }};">
                                        {{ ucfirst(str_replace('_', ' ', $internship->rating)) }}
                                    </span>
                                </div>
                            </div>
                            @if($internship->supervisor_comments)
                            <div class="col-12">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-tie me-2"></i>Supervisor Comments</h6>
                                <div class="p-3 bg-light rounded">{{ $internship->supervisor_comments }}</div>
                            </div>
                            @endif
                            @if($internship->intern_feedback)
                            <div class="col-12">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-comment me-2"></i>Intern Feedback</h6>
                                <div class="p-3 bg-light rounded">{{ $internship->intern_feedback }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Status</p>
                        <span class="badge rounded-pill px-3 py-2" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                            {{ ucfirst($internship->status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Duration</p>
                        <p class="fw-bold mb-0">{{ $internship->start_date->diffInDays($internship->end_date) }} days</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Created</p>
                        <p class="fw-bold mb-0">{{ $internship->created_at->format('F d, Y') }}</p>
                    </div>
                    @if($internship->completed_at)
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Completed</p>
                        <p class="fw-bold mb-0">{{ $internship->completed_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover { transform: translateY(-2px); transition: all 0.3s ease; }
    .form-range::-webkit-slider-thumb { background: #f97316; }
    .form-range::-webkit-slider-runnable-track { background: #fed7aa; height: 8px; border-radius: 4px; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreInput = document.getElementById('score');
    const ratingSelect = document.getElementById('rating');
    if (scoreInput && ratingSelect && !ratingSelect.value) {
        scoreInput.addEventListener('input', function() {
            const score = parseInt(this.value);
            if (score >= 90) ratingSelect.value = 'excellent';
            else if (score >= 75) ratingSelect.value = 'good';
            else if (score >= 60) ratingSelect.value = 'satisfactory';
            else if (score >= 40) ratingSelect.value = 'needs_improvement';
            else ratingSelect.value = 'poor';
        });
    }
});
</script>
@endpush
@endsection
