@extends('admin.layouts.app')

@section('title', 'HR Announcements')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-bullhorn me-3"></i>HR Announcements</h2>
                    <a href="{{ route('hms.hr.announcements.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>New Announcement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($announcements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Target Audience</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">End Date</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $announcement->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($announcement->content, 50) }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary">{{ ucfirst($announcement->target_audience) }}</span>
                                                @if($announcement->department)
                                                    <br><small class="text-muted">{{ $announcement->department->name }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <small>{{ $announcement->start_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small>{{ $announcement->end_date ? $announcement->end_date->format('M d, Y') : 'No end date' }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($announcement->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.announcements.show', $announcement) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.announcements.edit', $announcement) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('hms.hr.announcements.destroy', $announcement) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
                            <h4>No Announcements Found</h4>
                            <a href="{{ route('hms.hr.announcements.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Announcement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

