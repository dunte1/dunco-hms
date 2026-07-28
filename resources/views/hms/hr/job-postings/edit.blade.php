@extends('admin.layouts.app')

@section('title', 'Edit Job Posting')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-edit me-3"></i>Edit Job Posting</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('hms.hr.job-postings.update', $jobPosting) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $jobPosting->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                                    <option value="full-time" {{ old('employment_type', $jobPosting->employment_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part-time" {{ old('employment_type', $jobPosting->employment_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type', $jobPosting->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                                @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $jobPosting->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Designation</label>
                                <select name="designation_id" class="form-select">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id', $jobPosting->designation_id) == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $jobPosting->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Requirements</label>
                                <textarea name="requirements" class="form-control" rows="4">{{ old('requirements', $jobPosting->requirements) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Responsibilities</label>
                                <textarea name="responsibilities" class="form-control" rows="4">{{ old('responsibilities', $jobPosting->responsibilities) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Min Salary</label>
                                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min', $jobPosting->salary_min) }}" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Max Salary</label>
                                <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max', $jobPosting->salary_max) }}" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Vacancies <span class="text-danger">*</span></label>
                                <input type="number" name="vacancies" class="form-control @error('vacancies') is-invalid @enderror" value="{{ old('vacancies', $jobPosting->vacancies) }}" min="1" required>
                                @error('vacancies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $jobPosting->location) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Application Deadline</label>
                                <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', $jobPosting->application_deadline?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', $jobPosting->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $jobPosting->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="closed" {{ old('status', $jobPosting->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Job Posting
                                </button>
                                <a href="{{ route('hms.hr.job-postings.index') }}" class="btn btn-secondary">
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

