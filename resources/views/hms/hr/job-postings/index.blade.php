@extends('admin.layouts.app')

@section('title', 'Job Postings')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-0 fw-bold"><i class="fas fa-clipboard-list me-3"></i>Job Postings</h2>
                    </div>
                    <a href="{{ route('hms.hr.job-postings.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Post New Job
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($jobPostings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Department</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Vacancies</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Deadline</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobPostings as $posting)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $posting->title }}</h6>
                                                @if($posting->designation)
                                                    <small class="text-muted">{{ $posting->designation->name }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($posting->department)
                                                    <span class="badge bg-primary">{{ $posting->department->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info">{{ ucfirst($posting->employment_type) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary">{{ $posting->vacancies }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge 
                                                    @if($posting->status === 'published') bg-success
                                                    @elseif($posting->status === 'draft') bg-warning
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($posting->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($posting->application_deadline)
                                                    <small>{{ $posting->application_deadline->format('M d, Y') }}</small>
                                                @else
                                                    <span class="text-muted">No deadline</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.job-postings.show', $posting) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.job-postings.edit', $posting) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($posting->status === 'draft')
                                                        <form action="{{ route('hms.hr.job-postings.publish', $posting) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-info" title="Publish">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('hms.hr.job-postings.destroy', $posting) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-4 border-top">
                            {{ $jobPostings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h4>No Job Postings Found</h4>
                            <p class="text-muted">Create your first job posting to get started.</p>
                            <a href="{{ route('hms.hr.job-postings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Post New Job
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

