@extends('admin.layouts.app')

@section('title', 'Queue Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Enhanced Page Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-box p-4 rounded-4" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-ticket-alt me-3"></i>Queue Management
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="background: transparent;">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Dashboard</a></li>
                                <li class="breadcrumb-item text-white active">Queue Management</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-sm-0 d-flex gap-2">
                        <a href="{{ route('hms.queue.display-board') }}" target="_blank" class="btn btn-light btn-lg px-4" style="box-shadow: 0 4px 15px rgba(255,255,255,0.3);">
                            <i class="fas fa-tv me-2"></i>Display Board
                        </a>
                        <a href="{{ route('hms.queue.create') }}" class="btn btn-light btn-lg px-4" style="box-shadow: 0 4px 15px rgba(255,255,255,0.3);">
                            <i class="fas fa-plus-circle me-2"></i>Add to Queue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Today's Queues</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6366f1;">{{ $todayQueues ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-ticket-alt text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Waiting</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $waitingCount ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">Called</h6>
                            <h2 class="mb-0 fw-bold" style="color: #10b981;">{{ $calledCount ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bullhorn text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase mb-2 small">In Progress</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ec4899;">{{ $inProgressCount ?? 0 }}</h2>
                        </div>
                        <div class="stats-icon">
                            <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-spinner text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 me-3">
                                <i class="fas fa-list me-1"></i>
                            </span>
                            Queue List
                        </h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($queues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-dark">Queue Number</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Patient</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Department</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Type</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Priority</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Status</th>
                                        <th class="px-4 py-3 fw-semibold text-dark">Check-in Time</th>
                                        <th class="px-4 py-3 fw-semibold text-dark text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($queues as $queue)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-primary" style="font-size: 1.1rem;">
                                                    {{ $queue->queue_number }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $queue->patient_name }}</div>
                                                @if($queue->patient_phone)
                                                    <small class="text-muted">{{ $queue->patient_phone }}</small>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-info-subtle text-info">{{ $queue->department }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst(str_replace('_', ' ', $queue->queue_type)) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($queue->priority === 'emergency')
                                                    <span class="badge bg-danger">Emergency</span>
                                                @elseif($queue->priority === 'high')
                                                    <span class="badge bg-warning">High</span>
                                                @elseif($queue->priority === 'low')
                                                    <span class="badge bg-secondary">Low</span>
                                                @else
                                                    <span class="badge bg-info">Normal</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($queue->status === 'waiting')
                                                    <span class="badge bg-warning">Waiting</span>
                                                @elseif($queue->status === 'called')
                                                    <span class="badge bg-success">Called</span>
                                                @elseif($queue->status === 'in_progress')
                                                    <span class="badge bg-primary">In Progress</span>
                                                @elseif($queue->status === 'completed')
                                                    <span class="badge bg-secondary">Completed</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <small class="text-muted">
                                                    {{ $queue->check_in_time->format('M d, Y h:i A') }}
                                                </small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group" role="group">
                                                    @if($queue->status === 'waiting')
                                                        <button type="button" class="btn btn-sm btn-success call-queue-btn" 
                                                                data-queue-id="{{ $queue->id }}"
                                                                data-queue-number="{{ $queue->queue_number }}"
                                                                title="Call Patient">
                                                            <i class="fas fa-bullhorn"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary start-service-btn" 
                                                                data-queue-id="{{ $queue->id }}"
                                                                title="Start Service">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @elseif($queue->status === 'called')
                                                        <button type="button" class="btn btn-sm btn-primary start-service-btn" 
                                                                data-queue-id="{{ $queue->id }}"
                                                                title="Start Service">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @elseif($queue->status === 'in_progress')
                                                        <button type="button" class="btn btn-sm btn-success complete-queue-btn" 
                                                                data-queue-id="{{ $queue->id }}"
                                                                title="Complete">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <form action="{{ route('hms.queue.cancel', $queue) }}" method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to cancel this queue?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Cancel">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-top py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $queues->firstItem() }} to {{ $queues->lastItem() }} of {{ $queues->total() }} queues
                                </div>
                                <div>
                                    {{ $queues->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No queues found</p>
                            <a href="{{ route('hms.queue.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add to Queue
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audio Element for Announcements -->
<audio id="announcement-audio" preload="auto"></audio>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Call Queue Function
    document.querySelectorAll('.call-queue-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const queueId = this.dataset.queueId;
            const queueNumber = this.dataset.queueNumber;
            
            fetch(`/hms/queue/${queueId}/call`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Announce the queue number
                    announceQueueNumber(data.queue_number, data.location);
                    
                    // Show success message
                    alert(`Queue ${data.queue_number} called! Please proceed to ${data.location}`);
                    
                    // Reload page after a delay
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Start Service Function
    document.querySelectorAll('.start-service-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const queueId = this.dataset.queueId;
            
            fetch(`/hms/queue/${queueId}/start-service`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Complete Queue Function
    document.querySelectorAll('.complete-queue-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const queueId = this.dataset.queueId;
            
            fetch(`/hms/queue/${queueId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Announce Queue Number Function
    function announceQueueNumber(queueNumber, location) {
        // Use Web Speech API for text-to-speech
        if ('speechSynthesis' in window) {
            const message = `Queue number ${queueNumber}. Please proceed to ${location}`;
            
            // Cancel any ongoing speech
            window.speechSynthesis.cancel();
            
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = 'en-US';
            utterance.rate = 0.9;
            utterance.pitch = 1.0;
            utterance.volume = 1.0;
            
            window.speechSynthesis.speak(utterance);
            
            // Also log for debugging
            console.log('Announcing:', message);
        } else {
            console.warn('Speech synthesis not supported in this browser');
        }
    }
});
</script>
@endsection

