@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-eye mr-2"></i> Testimonial Details</h4>
                    <div>
                        <a href="{{ route('cms.testimonials.edit', $testimonial) }}" class="btn btn-warning mr-2">
                            <i class="fa fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('cms.testimonials.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-4">
                                @if($testimonial->patient_photo)
                                    <img 
                                        src="{{ asset('storage/' . $testimonial->patient_photo) }}" 
                                        alt="{{ $testimonial->patient_name }}" 
                                        class="rounded-circle mr-3" 
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 100px; height: 100px; font-size: 2rem;">
                                        {{ substr($testimonial->patient_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="mb-1">{{ $testimonial->patient_name }}</h3>
                                    @if($testimonial->doctor_name)
                                        <p class="text-muted mb-0">Patient of Dr. {{ $testimonial->doctor_name }}</p>
                                    @endif
                                    <div class="mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $testimonial->rating)
                                                <i class="fa fa-star text-warning fa-lg"></i>
                                            @else
                                                <i class="fa fa-star text-muted fa-lg"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-2">({{ $testimonial->rating }}/5)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>Testimonial</h5>
                                <p class="text-muted">{{ $testimonial->testimonial }}</p>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Contact Information</h6>
                                    <p><strong>Email:</strong> {{ $testimonial->patient_email ?? 'N/A' }}</p>
                                    <p><strong>Phone:</strong> {{ $testimonial->patient_phone ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Treatment Details</h6>
                                    <p><strong>Treatment:</strong> {{ $testimonial->treatment_received ?? 'N/A' }}</p>
                                    <p><strong>Doctor:</strong> {{ $testimonial->doctor_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Status & Settings</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Status:</strong> 
                                        @if($testimonial->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($testimonial->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </p>
                                    <p><strong>Featured:</strong> 
                                        @if($testimonial->is_featured)
                                            <i class="fa fa-star text-warning"></i> Yes
                                        @else
                                            <i class="fa fa-star text-muted"></i> No
                                        @endif
                                    </p>
                                    <hr>
                                    <p><strong>Created:</strong> {{ $testimonial->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>Updated:</strong> {{ $testimonial->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

