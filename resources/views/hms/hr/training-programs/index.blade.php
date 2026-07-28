@extends('admin.layouts.app')

@section('title', 'Training Programs')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-graduation-cap me-3"></i>Training Programs</h2>
                    <a href="{{ route('hms.hr.training-programs.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Program
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($trainingPrograms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Title</th>
                                        <th class="px-4 py-3">Category</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">Duration</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainingPrograms as $program)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $program->title }}</h6>
                                                @if($program->description)
                                                    <small class="text-muted">{{ Str::limit($program->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($program->category)
                                                    <span class="badge bg-secondary">{{ $program->category }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <small>{{ $program->start_date->format('M d, Y') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info">{{ $program->duration_hours }} hours</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge 
                                                    @if($program->status === 'upcoming') bg-info
                                                    @elseif($program->status === 'ongoing') bg-primary
                                                    @elseif($program->status === 'completed') bg-success
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($program->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.training-programs.show', $program) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.training-programs.edit', $program) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('hms.hr.training-programs.destroy', $program) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            {{ $trainingPrograms->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                            <h4>No Training Programs Found</h4>
                            <a href="{{ route('hms.hr.training-programs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Program
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

