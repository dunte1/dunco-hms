@extends('admin.layouts.app')

@section('title', 'Shifts Management')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-clock me-3"></i>Shifts Management</h2>
                    <a href="{{ route('hms.hr.shifts.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Shift
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($shifts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Shift Name</th>
                                        <th class="px-4 py-3">Start Time</th>
                                        <th class="px-4 py-3">End Time</th>
                                        <th class="px-4 py-3">Duration</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shifts as $shift)
                                        @php
                                            $start = \Carbon\Carbon::parse($shift->start_time);
                                            $end = \Carbon\Carbon::parse($shift->end_time);
                                            if ($end < $start) {
                                                $end->addDay(); // Handle overnight shifts
                                            }
                                            $duration = $start->diffInHours($end);
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $shift->name }}</h6>
                                                @if($shift->description)
                                                    <small class="text-muted">{{ Str::limit($shift->description, 40) }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary">{{ $start->format('H:i') }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-danger">{{ $end->format('H:i') }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info">{{ $duration }} hours</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($shift->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.shifts.show', $shift) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.shifts.roster', $shift) }}" class="btn btn-outline-info" title="View Roster">
                                                        <i class="fas fa-calendar-week"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.shifts.edit', $shift) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('hms.hr.shifts.destroy', $shift) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            {{ $shifts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-4x text-muted mb-3"></i>
                            <h4>No Shifts Found</h4>
                            <a href="{{ route('hms.hr.shifts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Shift
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

