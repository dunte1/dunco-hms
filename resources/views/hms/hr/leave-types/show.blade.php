@extends('admin.layouts.app')

@section('title', 'Leave Type Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">{{ $leaveType->name }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('hms.hr.leave-types.index') }}" class="text-white-50">Leave Types</a></li>
                                <li class="breadcrumb-item text-white active">Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('hms.hr.leave-types.edit', $leaveType) }}" class="btn btn-light me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hms.hr.leave-types.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Default Days Per Year</label>
                            <p class="mb-0">
                                <span class="badge bg-primary fs-6">{{ $leaveType->default_days }} days</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Color</label>
                            <p class="mb-0">
                                <span class="badge rounded-pill" style="background-color: {{ $leaveType->color ?? '#f59e0b' }}; width: 40px; height: 20px; display: inline-block;"></span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Carry Forward</label>
                            <p class="mb-0">
                                @if($leaveType->carry_forward)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Requires Approval</label>
                            <p class="mb-0">
                                @if($leaveType->requires_approval)
                                    <span class="badge bg-warning">Required</span>
                                @else
                                    <span class="badge bg-info">Not Required</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($leaveType->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        @if($leaveType->description)
                        <div class="col-12">
                            <label class="text-muted small">Description</label>
                            <p class="mb-0">{{ $leaveType->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Leave Requests ({{ $leaveType->leaveRequests->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($leaveType->leaveRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">Employee</th>
                                        <th class="px-4 py-3">Start Date</th>
                                        <th class="px-4 py-3">End Date</th>
                                        <th class="px-4 py-3">Days</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveType->leaveRequests->take(10) as $request)
                                        <tr>
                                            <td class="px-4 py-3">{{ $request->employee->full_name }}</td>
                                            <td class="px-4 py-3">{{ $request->start_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">{{ $request->end_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">{{ $request->total_days }} days</td>
                                            <td class="px-4 py-3">
                                                <span class="badge 
                                                    @if($request->status === 'approved') bg-success
                                                    @elseif($request->status === 'pending') bg-warning
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($leaveType->leaveRequests->count() > 10)
                            <div class="p-3 text-center">
                                <a href="{{ route('hms.hr.leave-requests.index', ['leave_type_id' => $leaveType->id]) }}" class="btn btn-sm btn-primary">
                                    View All ({{ $leaveType->leaveRequests->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No leave requests for this leave type yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

