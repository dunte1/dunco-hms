@extends('admin.layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-history me-3"></i>Audit Logs
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('hms.settings.index') }}" class="text-white-50">Settings</a></li>
                                <li class="breadcrumb-item text-white active">Audit Logs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <span class="badge bg-purple-subtle text-purple px-3 py-2 me-3">
                            <i class="fas fa-list me-1"></i>
                        </span>
                        System Activity Logs
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Date & Time</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">User</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Action</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Details</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-medium text-dark">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                                            <i class="fas fa-user text-white small"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold small">{{ $log->user->name ?? 'System' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-purple-subtle text-purple px-3 py-2">
                                                    {{ $log->action ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-muted">{{ Str::limit($log->description ?? 'N/A', 50) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">{{ $log->ip_address ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($logs->hasPages())
                        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                            <div class="text-muted small">
                                Showing <strong>{{ $logs->firstItem() }}</strong> to <strong>{{ $logs->lastItem() }}</strong> of <strong>{{ $logs->total() }}</strong> entries
                            </div>
                            <div>{{ $logs->links() }}</div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5 my-5">
                            <div class="mb-4">
                                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);">
                                    <i class="fas fa-history" style="font-size: 3rem; color: #8b5cf6;"></i>
                                </div>
                            </div>
                            <h4 class="text-dark mb-3 fw-bold">No Audit Logs</h4>
                            <p class="text-muted mb-4">No system activity logs available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    * {
        transition: all 0.3s ease;
    }
</style>
@endsection
