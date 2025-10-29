@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-briefcase mr-2"></i> Job Postings Management</h4>
                    <div>
                        <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary mr-2">
                            <i class="fa fa-arrow-left mr-1"></i> Back
                        </a>
                        <a href="{{ route('cms.careers.create') }}" class="btn btn-primary mr-2">
                            <i class="fa fa-plus mr-1"></i> New Job
                        </a>
                        <a href="{{ route('cms.careers.applications') }}" class="btn btn-info">
                            <i class="fa fa-file-alt mr-1"></i> Applications
                        </a>
                    </div>
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
                    <form method="GET" action="{{ route('cms.careers.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Search by title, department, or description..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
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

                    <!-- Jobs Table -->
                    @if($jobs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Category</th>
                                        <th>Department</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                        <tr>
                                            <td>
                                                <strong>{{ $job->title ?? $job->job_title }}</strong>
                                                @if($job->vacancies ?? null)
                                                    <br><small class="text-muted">{{ $job->vacancies }} vacancies</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->category)
                                                    <span class="badge badge-info">{{ $job->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Uncategorized</span>
                                                @endif
                                            </td>
                                            <td>{{ $job->department }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</span>
                                            </td>
                                            <td>{{ $job->location }}</td>
                                            <td>
                                                {{ $job->application_deadline ? $job->application_deadline->format('Y-m-d') : 'N/A' }}
                                                @if($job->application_deadline && $job->application_deadline->isPast())
                                                    <br><small class="text-danger">Expired</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($job->status == 'draft')
                                                    <span class="badge badge-warning">Draft</span>
                                                @else
                                                    <span class="badge badge-secondary">Closed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->is_featured)
                                                    <i class="fa fa-star text-warning"></i>
                                                @else
                                                    <i class="fa fa-star text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('cms.careers.edit', $job) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('cms.careers.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job posting?');">
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
                            {{ $jobs->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i> No job postings found. 
                            <a href="{{ route('cms.careers.create') }}">Create your first job posting</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

