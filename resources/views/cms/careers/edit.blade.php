@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-edit mr-2"></i> Edit Job Posting</h4>
                    <a href="{{ route('cms.careers.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cms.careers.update', $job) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="job_title" 
                                        name="job_title" 
                                        class="form-control @error('job_title') is-invalid @enderror" 
                                        value="{{ old('job_title', $job->title) }}"
                                        required>
                                    @error('job_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        rows="6"
                                        required>{{ old('description', $job->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Requirements <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="requirements" 
                                        name="requirements" 
                                        class="form-control @error('requirements') is-invalid @enderror" 
                                        rows="6"
                                        required>{{ old('requirements', $job->requirements) }}</textarea>
                                    @error('requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="responsibilities" class="form-label">Responsibilities <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="responsibilities" 
                                        name="responsibilities" 
                                        class="form-control @error('responsibilities') is-invalid @enderror" 
                                        rows="6"
                                        required>{{ old('responsibilities', $job->responsibilities) }}</textarea>
                                    @error('responsibilities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="benefits" class="form-label">Benefits & Perks</label>
                                    <textarea 
                                        id="benefits" 
                                        name="benefits" 
                                        class="form-control @error('benefits') is-invalid @enderror" 
                                        rows="4">{{ old('benefits', $job->benefits ?? '') }}</textarea>
                                    @error('benefits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="job_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select 
                                        id="job_category_id" 
                                        name="job_category_id" 
                                        class="form-control @error('job_category_id') is-invalid @enderror" 
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('job_category_id', $job->job_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="department" 
                                        name="department" 
                                        class="form-control @error('department') is-invalid @enderror" 
                                        value="{{ old('department', $job->department) }}"
                                        required>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                    <select 
                                        id="employment_type" 
                                        name="employment_type" 
                                        class="form-control @error('employment_type') is-invalid @enderror" 
                                        required>
                                        <option value="full-time" {{ old('employment_type', $job->employment_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="part-time" {{ old('employment_type', $job->employment_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                        <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="temporary" {{ old('employment_type', $job->employment_type) == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="experience_level" class="form-label">Experience Level <span class="text-danger">*</span></label>
                                    <select 
                                        id="experience_level" 
                                        name="experience_level" 
                                        class="form-control @error('experience_level') is-invalid @enderror" 
                                        required>
                                        <option value="entry" {{ old('experience_level', $job->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                        <option value="junior" {{ old('experience_level', $job->experience_level) == 'junior' ? 'selected' : '' }}>Junior</option>
                                        <option value="mid" {{ old('experience_level', $job->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                        <option value="senior" {{ old('experience_level', $job->experience_level) == 'senior' ? 'selected' : '' }}>Senior</option>
                                        <option value="executive" {{ old('experience_level', $job->experience_level) == 'executive' ? 'selected' : '' }}>Executive</option>
                                    </select>
                                    @error('experience_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="location" 
                                        name="location" 
                                        class="form-control @error('location') is-invalid @enderror" 
                                        value="{{ old('location', $job->location) }}"
                                        required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="salary_range_min" class="form-label">Min Salary</label>
                                        <input 
                                            type="number" 
                                            id="salary_range_min" 
                                            name="salary_range_min" 
                                            class="form-control @error('salary_range_min') is-invalid @enderror" 
                                            value="{{ old('salary_range_min', $job->salary_min) }}"
                                            step="0.01">
                                        @error('salary_range_min')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="salary_range_max" class="form-label">Max Salary</label>
                                        <input 
                                            type="number" 
                                            id="salary_range_max" 
                                            name="salary_range_max" 
                                            class="form-control @error('salary_range_max') is-invalid @enderror" 
                                            value="{{ old('salary_range_max', $job->salary_max) }}"
                                            step="0.01">
                                        @error('salary_range_max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="vacancies" class="form-label">Number of Vacancies <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        id="vacancies" 
                                        name="vacancies" 
                                        class="form-control @error('vacancies') is-invalid @enderror" 
                                        value="{{ old('vacancies', $job->vacancies ?? 1) }}"
                                        min="1"
                                        required>
                                    @error('vacancies')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="application_deadline" class="form-label">Application Deadline <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        id="application_deadline" 
                                        name="application_deadline" 
                                        class="form-control @error('application_deadline') is-invalid @enderror" 
                                        value="{{ old('application_deadline', $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}"
                                        required>
                                    @error('application_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select 
                                        id="status" 
                                        name="status" 
                                        class="form-control @error('status') is-invalid @enderror" 
                                        required>
                                        <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            id="is_featured" 
                                            name="is_featured" 
                                            class="form-check-input" 
                                            value="1"
                                            {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Job
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Update Job Posting
                            </button>
                            <a href="{{ route('cms.careers.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

