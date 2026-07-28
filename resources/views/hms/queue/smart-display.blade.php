@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0" style="background: #000; min-height: 100vh;">
    <div class="row g-0">
        <!-- Main Display -->
        <div class="col-md-9">
            <div class="p-4">
                <div class="text-center mb-4" style="color: #fff;">
                    <h1 class="display-4 mb-2">🏥 Queue Display</h1>
                    <p class="lead">{{ now()->format('l, F d, Y - h:i A') }}</p>
                </div>

                <!-- Currently Serving -->
                <div class="mb-4">
                    <h3 class="text-white mb-3 text-center">Currently Serving</h3>
                    <div class="row">
                        @forelse($queues->where('status', 'in_progress') as $queue)
                            <div class="col-md-6 mb-3">
                                <div class="card border-success" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                                    <div class="card-body text-center text-white p-4">
                                        <div class="display-1 mb-3">{{ $queue->token_number ?? $queue->queue_number }}</div>
                                        <h4>{{ $queue->patient->first_name ?? 'Walk-in' }} {{ $queue->patient->last_name ?? '' }}</h4>
                                        <p class="mb-0">Dr. {{ $queue->doctor->first_name ?? 'N/A' }} {{ $queue->doctor->last_name ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <h4>No active queues</h4>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Waiting Queue -->
                <div>
                    <h3 class="text-white mb-3 text-center">Waiting Queue</h3>
                    <div class="row">
                        @forelse($queues->where('status', 'waiting') as $queue)
                            <div class="col-md-3 mb-3">
                                <div class="card border-primary" style="background: rgba(59, 130, 246, 0.2);">
                                    <div class="card-body text-center text-white">
                                        <div class="h2 mb-2">{{ $queue->token_number ?? $queue->queue_number }}</div>
                                        <p class="mb-0 small">{{ $queue->patient->first_name ?? 'Walk-in' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-white">
                                <h4>No waiting queues</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="col-md-3" style="background: rgba(255,255,255,0.1); padding: 20px;">
            <h4 class="text-white mb-4">Statistics</h4>
            <div class="mb-3">
                <div class="card bg-warning">
                    <div class="card-body text-center">
                        <h2>{{ $stats['waiting'] }}</h2>
                        <p class="mb-0">Waiting</p>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="card bg-info">
                    <div class="card-body text-center">
                        <h2>{{ $stats['called'] }}</h2>
                        <p class="mb-0">Called</p>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="card bg-success">
                    <div class="card-body text-center">
                        <h2>{{ $stats['in_progress'] }}</h2>
                        <p class="mb-0">In Progress</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh every 15 seconds
    setInterval(() => {
        location.reload();
    }, 15000);
</script>
@endpush
@endsection

