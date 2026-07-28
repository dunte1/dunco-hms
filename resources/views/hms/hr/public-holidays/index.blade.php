@extends('admin.layouts.app')

@section('title', 'Public Holidays')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-white mb-0 fw-bold"><i class="fas fa-calendar-day me-3"></i>Public Holidays</h2>
                    <a href="{{ route('hms.hr.public-holidays.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Add Holiday
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($publicHolidays->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Holiday Name</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Recurring</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publicHolidays as $holiday)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <h6 class="mb-0 fw-bold">{{ $holiday->name }}</h6>
                                                @if($holiday->description)
                                                    <small class="text-muted">{{ Str::limit($holiday->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary">{{ $holiday->date->format('M d, Y') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $holiday->date->format('l') }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($holiday->is_recurring)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($holiday->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('hms.hr.public-holidays.show', $holiday) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hms.hr.public-holidays.edit', $holiday) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('hms.hr.public-holidays.destroy', $holiday) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            {{ $publicHolidays->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-day fa-4x text-muted mb-3"></i>
                            <h4>No Public Holidays Found</h4>
                            <a href="{{ route('hms.hr.public-holidays.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Holiday
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

