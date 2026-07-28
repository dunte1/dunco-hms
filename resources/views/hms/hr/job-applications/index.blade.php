@extends('admin.layouts.app')

@section('title', 'Job Applications')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <h2 class="text-white mb-0 fw-bold"><i class="fas fa-file-alt me-3"></i>Job Applications</h2>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <select class="form-select" onchange="window.location.href=this.value">
                <option value="{{ route('hms.hr.job-applications.index') }}">All Applications</option>
                <option value="{{ route('hms.hr.job-applications.index', ['status' => 'pending']) }}" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="{{ route('hms.hr.job-applications.index', ['status' => 'shortlisted']) }}" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                <option value="{{ route('hms.hr.job-applications.index', ['status' => 'hired']) }}" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                <option value="{{ route('hms.hr.job-applications.index', ['status' => 'rejected']) }}" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-6">
            <select class="form-select" onchange="window.location.href='{{ route('hms.hr.job-applications.index') }}?job_posting_id='+this.value">
                <option value="">All Job Postings</option>
                @foreach($jobPostings as $posting)
                    <option value="{{ $posting->id }}" {{ request('job_posting_id') == $posting->id ? 'selected' : '' }}>{{ $posting->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Applicant</th>
                                        <th class="px-4 py-3">Job Posting</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Phone</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Applied</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $application->first_name }} {{ $application->last_name }}</h6>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary">{{ $application->jobPosting->title }}</span>
                                            </td>
                                            <td class="px-4 py-3">{{ $application->email }}</td>
                                            <td class="px-4 py-3">{{ $application->phone }}</td>
                                            <td class="px-4 py-3">
                                                <span class="badge 
                                                    @if($application->status === 'pending') bg-warning
                                                    @elseif($application->status === 'shortlisted') bg-info
                                                    @elseif($application->status === 'hired') bg-success
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small>{{ $application->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.job-applications.show', $application) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($application->status === 'pending')
                                                        <form action="{{ route('hms.hr.job-applications.shortlist', $application) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-info" title="Shortlist">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-4 border-top">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h4>No Applications Found</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

