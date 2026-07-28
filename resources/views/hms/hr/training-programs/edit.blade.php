@extends('admin.layouts.app')

@section('title', 'Edit Training Program')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-edit me-3"></i>Edit Training Program</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.training-programs.update', $trainingProgram) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Program Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $trainingProgram->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Category</label>
                                <input type="text" name="category" class="form-control" value="{{ old('category', $trainingProgram->category) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $trainingProgram->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $trainingProgram->end_date?->format('Y-m-d')) }}">
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $trainingProgram->description) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Duration (Hours) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror" value="{{ old('duration_hours', $trainingProgram->duration_hours) }}" min="0" required>
                                @error('duration_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $trainingProgram->location) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Instructor</label>
                                <input type="text" name="instructor" class="form-control" value="{{ old('instructor', $trainingProgram->instructor) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Max Participants</label>
                                <input type="number" name="max_participants" class="form-control" value="{{ old('max_participants', $trainingProgram->max_participants) }}" min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="upcoming" {{ old('status', $trainingProgram->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('status', $trainingProgram->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $trainingProgram->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $trainingProgram->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="certificate_available" value="1" id="certificate" {{ old('certificate_available', $trainingProgram->certificate_available) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="certificate">Certificate Available</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Training Program
                                </button>
                                <a href="{{ route('hms.hr.training-programs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

