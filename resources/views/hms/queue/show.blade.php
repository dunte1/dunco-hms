@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('hms.queue.index') }}" class="hover:text-primary">Queue Management</a>
            <i class="fa fa-chevron-right text-xs"></i>
            <span>{{ $queue->queue_number }}</span>
        </div>
        <h1 class="h3 mb-0"><i class="fa fa-ticket-alt text-primary me-2"></i>{{ $queue->queue_number }}</h1>
    </div>

    @if(session('status'))
        <div class="alert alert-success"><i class="fa fa-check-circle me-2"></i>{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Queue Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Queue Number:</div>
                        <div class="col-sm-8 fw-bold text-primary fs-5">{{ $queue->queue_number }}</div>
                    </div>
                    @if($queue->token_number)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Token Number:</div>
                        <div class="col-sm-8 fw-bold">{{ $queue->token_number }}</div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Status:</div>
                        <div class="col-sm-8">
                            @php
                                $statusClass = match($queue->status) {
                                    'waiting' => 'warning',
                                    'called' => 'success',
                                    'in_progress' => 'info',
                                    'completed' => 'secondary',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $queue->status)) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Patient:</div>
                        <div class="col-sm-8 fw-bold">{{ $queue->patient_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Phone:</div>
                        <div class="col-sm-8">{{ $queue->patient_phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Department:</div>
                        <div class="col-sm-8">{{ $queue->department }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Type:</div>
                        <div class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $queue->queue_type)) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Priority:</div>
                        <div class="col-sm-8">
                            @php
                                $priClass = match($queue->priority) {
                                    'emergency' => 'danger',
                                    'high' => 'warning',
                                    'normal' => 'primary',
                                    'low' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $priClass }}">{{ ucfirst($queue->priority) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Check-in Time:</div>
                        <div class="col-sm-8">{{ $queue->check_in_time->format('M d, Y h:i A') }}</div>
                    </div>
                    @if($queue->called_time)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Called Time:</div>
                        <div class="col-sm-8">{{ $queue->called_time->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                    @if($queue->completed_time)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Completed Time:</div>
                        <div class="col-sm-8">{{ $queue->completed_time->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                    @if($queue->notes)
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Notes:</div>
                        <div class="col-sm-8">{{ $queue->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('hms.queue.edit', $queue) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fa fa-edit me-1"></i> Edit Queue
                    </a>
                    @if($queue->status === 'waiting')
                        <form action="{{ route('hms.queue.call', $queue) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block w-100"><i class="fa fa-bullhorn me-1"></i> Call Patient</button>
                        </form>
                    @endif
                    @if($queue->status === 'called')
                        <form action="{{ route('hms.queue.start-service', $queue) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info btn-block w-100"><i class="fa fa-play me-1"></i> Start Service</button>
                        </form>
                    @endif
                    @if(in_array($queue->status, ['called', 'in_progress']))
                        <form action="{{ route('hms.queue.complete', $queue) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block w-100"><i class="fa fa-check me-1"></i> Complete</button>
                        </form>
                    @endif
                    <a href="{{ route('hms.queue.index') }}" class="btn btn-secondary btn-block">
                        <i class="fa fa-arrow-left me-1"></i> Back to Queue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
