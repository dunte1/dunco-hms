<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Display - Kiosk Mode</title>
    <meta http-equiv="refresh" content="30">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow: hidden;
        }
        .container { 
            height: 100vh; 
            display: flex; 
            flex-direction: column;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 3em; margin-bottom: 10px; }
        .header p { font-size: 1.5em; }
        .queue-display {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            flex: 1;
            overflow-y: auto;
        }
        .queue-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .queue-card:hover { transform: scale(1.05); }
        .queue-card.called {
            background: rgba(34, 197, 94, 0.3);
            border: 3px solid #22c55e;
            animation: pulse 2s infinite;
        }
        .queue-card.in-progress {
            background: rgba(59, 130, 246, 0.3);
            border: 3px solid #3b82f6;
        }
        .token-number {
            font-size: 4em;
            font-weight: bold;
            margin: 20px 0;
        }
        .patient-name {
            font-size: 1.5em;
            margin: 10px 0;
        }
        .doctor-name {
            font-size: 1.2em;
            opacity: 0.9;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        .current-section {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Queue Display</h1>
            <p>{{ now()->format('l, F d, Y - h:i A') }}</p>
        </div>

        @if($currentQueues->count() > 0)
            <div class="current-section">
                <h2 style="text-align: center; margin-bottom: 15px; font-size: 2em;">Currently Serving</h2>
                <div class="queue-display">
                    @foreach($currentQueues as $queue)
                        <div class="queue-card in-progress">
                            <div class="token-number">{{ $queue->token_number ?? $queue->queue_number }}</div>
                            <div class="patient-name">{{ $queue->patient->first_name ?? 'Walk-in' }} {{ $queue->patient->last_name ?? '' }}</div>
                            <div class="doctor-name">Dr. {{ $queue->doctor->first_name ?? 'N/A' }} {{ $queue->doctor->last_name ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <h2 style="text-align: center; margin: 20px 0; font-size: 2em;">Waiting Queue</h2>
        <div class="queue-display">
            @forelse($queues as $queue)
                <div class="queue-card {{ $queue->status }}">
                    <div class="token-number">{{ $queue->token_number ?? $queue->queue_number }}</div>
                    <div class="patient-name">{{ $queue->patient->first_name ?? 'Walk-in' }} {{ $queue->patient->last_name ?? '' }}</div>
                    <div class="doctor-name">Dr. {{ $queue->doctor->first_name ?? 'N/A' }} {{ $queue->doctor->last_name ?? '' }}</div>
                    <div style="margin-top: 15px; font-size: 1.1em;">
                        Status: <strong>{{ ucfirst($queue->status) }}</strong>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; font-size: 2em; padding: 50px;">
                    No queues at the moment
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>

