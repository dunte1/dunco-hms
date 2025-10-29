@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-star mr-2"></i> Testimonials Management</h4>
                    <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <form method="GET" action="{{ route('cms.testimonials.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search by name, testimonial, or treatment..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Testimonials Table -->
                    @if($testimonials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Rating</th>
                                        <th>Testimonial</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testimonials as $testimonial)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($testimonial->patient_photo)
                                                        <img 
                                                            src="{{ asset('storage/' . $testimonial->patient_photo) }}" 
                                                            alt="{{ $testimonial->patient_name }}" 
                                                            class="rounded-circle mr-2" 
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px;">
                                                            {{ substr($testimonial->patient_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $testimonial->patient_name }}</strong>
                                                        @if($testimonial->doctor_name)
                                                            <br><small class="text-muted">Dr. {{ $testimonial->doctor_name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        <i class="fa fa-star text-warning"></i>
                                                    @else
                                                        <i class="fa fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </td>
                                            <td>
                                                {{ Str::limit($testimonial->testimonial, 100) }}
                                            </td>
                                            <td>{{ $testimonial->treatment_received ?? 'N/A' }}</td>
                                            <td>
                                                @if($testimonial->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($testimonial->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($testimonial->is_featured)
                                                    <i class="fa fa-star text-warning"></i>
                                                @else
                                                    <i class="fa fa-star text-muted"></i>
                                                @endif
                                            </td>
                                            <td>{{ $testimonial->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('cms.testimonials.show', $testimonial) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('cms.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('cms.testimonials.destroy', $testimonial) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $testimonials->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No testimonials found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

