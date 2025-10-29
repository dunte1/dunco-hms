@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-edit mr-2"></i> Edit Testimonial</h4>
                    <a href="{{ route('cms.testimonials.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if($testimonial->patient_photo)
                        <div class="mb-3">
                            <label class="form-label">Current Photo</label>
                            <div>
                                <img 
                                    src="{{ asset('storage/' . $testimonial->patient_photo) }}" 
                                    alt="{{ $testimonial->patient_name }}" 
                                    class="img-thumbnail" 
                                    style="max-width: 150px;">
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cms.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_name" class="form-label">Patient Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        id="patient_name" 
                                        name="patient_name" 
                                        class="form-control @error('patient_name') is-invalid @enderror" 
                                        value="{{ old('patient_name', $testimonial->patient_name) }}"
                                        required>
                                    @error('patient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="patient_email" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        id="patient_email" 
                                        name="patient_email" 
                                        class="form-control @error('patient_email') is-invalid @enderror" 
                                        value="{{ old('patient_email', $testimonial->patient_email) }}">
                                    @error('patient_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="patient_phone" class="form-label">Phone</label>
                                    <input 
                                        type="text" 
                                        id="patient_phone" 
                                        name="patient_phone" 
                                        class="form-control @error('patient_phone') is-invalid @enderror" 
                                        value="{{ old('patient_phone', $testimonial->patient_phone) }}">
                                    @error('patient_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="treatment_received" class="form-label">Treatment Received</label>
                                    <input 
                                        type="text" 
                                        id="treatment_received" 
                                        name="treatment_received" 
                                        class="form-control @error('treatment_received') is-invalid @enderror" 
                                        value="{{ old('treatment_received', $testimonial->treatment_received) }}">
                                    @error('treatment_received')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="doctor_name" class="form-label">Doctor Name</label>
                                    <input 
                                        type="text" 
                                        id="doctor_name" 
                                        name="doctor_name" 
                                        class="form-control @error('doctor_name') is-invalid @enderror" 
                                        value="{{ old('doctor_name', $testimonial->doctor_name) }}">
                                    @error('doctor_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="patient_photo" class="form-label">Patient Photo</label>
                                    <input 
                                        type="file" 
                                        id="patient_photo" 
                                        name="patient_photo" 
                                        class="form-control-file @error('patient_photo') is-invalid @enderror" 
                                        accept="image/*">
                                    @error('patient_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to keep current photo. Max 2MB</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                    <select 
                                        id="rating" 
                                        name="rating" 
                                        class="form-control @error('rating') is-invalid @enderror" 
                                        required>
                                        <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star</option>
                                        <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars</option>
                                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars</option>
                                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars</option>
                                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars</option>
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="testimonial" class="form-label">Testimonial <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="testimonial" 
                                        name="testimonial" 
                                        class="form-control @error('testimonial') is-invalid @enderror" 
                                        rows="8"
                                        required
                                        minlength="50">{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                                    @error('testimonial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 50 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select 
                                        id="status" 
                                        name="status" 
                                        class="form-control @error('status') is-invalid @enderror" 
                                        required>
                                        <option value="pending" {{ old('status', $testimonial->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $testimonial->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $testimonial->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                            {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Testimonial
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Update Testimonial
                            </button>
                            <a href="{{ route('cms.testimonials.index') }}" class="btn btn-secondary">
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

