@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-file-alt mr-2"></i> Job Applications</h4>
                    <a href="{{ route('cms.careers.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('cms.careers.applications') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search by applicant name or email..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="job" class="form-control">
                                    <option value="">All Jobs</option>
                                    @foreach($jobs as $jobOption)
                                        <option value="{{ $jobOption->id }}" {{ request('job') == $jobOption->id ? 'selected' : '' }}>
                                            {{ $jobOption->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Applications Table -->
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Position</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>
                                            </td>
                                            <td>
                                                @if($application->jobPosting)
                                                    {{ $application->jobPosting->title }}
                                                @else
                                                    <span class="text-muted">Job deleted</span>
                                                @endif
                                            </td>
                                            <td>{{ $application->email }}</td>
                                            <td>{{ $application->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($application->status == 'hired')
                                                    <span class="badge badge-success">Hired</span>
                                                @elseif($application->status == 'shortlisted')
                                                    <span class="badge badge-info">Shortlisted</span>
                                                @elseif($application->status == 'reviewed')
                                                    <span class="badge badge-primary">Reviewed</span>
                                                @elseif($application->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $application->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($application->resume_path)
                                                        <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn btn-sm btn-info" title="View Resume">
                                                            <i class="fa fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#viewModal{{ $application->id }}" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $application->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Application Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <p><strong>Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</p>
                                                                <p><strong>Email:</strong> {{ $application->email }}</p>
                                                                <p><strong>Phone:</strong> {{ $application->phone ?? 'N/A' }}</p>
                                                                <p><strong>Address:</strong> {{ $application->address ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p><strong>Job:</strong> {{ $application->jobPosting->title ?? 'N/A' }}</p>
                                                                <p><strong>Status:</strong> 
                                                                    @if($application->status == 'hired')
                                                                        <span class="badge badge-success">Hired</span>
                                                                    @elseif($application->status == 'shortlisted')
                                                                        <span class="badge badge-info">Shortlisted</span>
                                                                    @elseif($application->status == 'reviewed')
                                                                        <span class="badge badge-primary">Reviewed</span>
                                                                    @elseif($application->status == 'rejected')
                                                                        <span class="badge badge-danger">Rejected</span>
                                                                    @else
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    @endif
                                                                </p>
                                                                <p><strong>Applied:</strong> {{ $application->created_at->format('Y-m-d H:i:s') }}</p>
                                                            </div>
                                                        </div>
                                                        @if($application->cover_letter_text)
                                                            <div class="mb-3">
                                                                <strong>Cover Letter:</strong>
                                                                <p class="border p-3 rounded">{{ $application->cover_letter_text }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            @if($application->resume_path)
                                                                <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn btn-info">
                                                                    <i class="fa fa-file-pdf mr-1"></i> View Resume
                                                                </a>
                                                            @endif
                                                            @if($application->cover_letter_path)
                                                                <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="btn btn-secondary">
                                                                    <i class="fa fa-file-alt mr-1"></i> View Cover Letter
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No job applications found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

