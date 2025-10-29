<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Display Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .display-board {
            padding: 20px;
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            animation: fadeInDown 1s;
        }

        .header-section h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }

        .header-section .clock {
            font-size: 2rem;
            font-weight: 300;
        }

        .current-call-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: pulse 2s infinite;
            text-align: center;
        }

        .current-call-section h2 {
            color: #6366f1;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .called-number {
            font-size: 8rem;
            font-weight: bold;
            color: #10b981;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            animation: scaleIn 0.5s;
        }

        .called-location {
            font-size: 2rem;
            color: #6366f1;
            margin-top: 20px;
            font-weight: 500;
        }

        .queues-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .department-queue {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .department-queue h3 {
            color: #6366f1;
            font-size: 1.8rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #6366f1;
        }

        .queue-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .queue-item.waiting {
            background: #fef3c7;
            border-left: 5px solid #f59e0b;
        }

        .queue-item.called {
            background: #d1fae5;
            border-left: 5px solid #10b981;
            animation: highlight 1s;
        }

        .queue-item.in-progress {
            background: #ddd6fe;
            border-left: 5px solid #8b5cf6;
        }

        .queue-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }

        .queue-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .recently-completed {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-top: 20px;
        }

        .recently-completed h3 {
            color: #6366f1;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes highlight {
            0% {
                background: #fef3c7;
            }
            50% {
                background: #10b981;
            }
            100% {
                background: #d1fae5;
            }
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-waiting {
            background: #fef3c7;
            color: #92400e;
        }

        .status-called {
            background: #d1fae5;
            color: #065f46;
        }

        .status-in-progress {
            background: #ddd6fe;
            color: #5b21b6;
        }
    </style>
</head>
<body>
    <div class="display-board">
        <!-- Header -->
        <div class="header-section">
            <h1><i class="fas fa-hospital me-3"></i>Hospital Queue Management</h1>
            <div class="clock" id="clock"></div>
        </div>

        <!-- Currently Called Section -->
        <div class="current-call-section" id="currentCallSection">
            <h2>Currently Serving</h2>
            <div class="called-number" id="calledNumber">
                @if($currentlyCalled->count() > 0)
                    {{ $currentlyCalled->first()->queue_number }}
                @else
                    ---
                @endif
            </div>
            <div class="called-location" id="calledLocation">
                @if($currentlyCalled->count() > 0)
                    @php
                        $queue = $currentlyCalled->first();
                        $location = $queue->doctor ? ($queue->doctor->department->name ?? $queue->department) : $queue->department;
                    @endphp
                    {{ $location }}
                @else
                    Waiting...
                @endif
            </div>
        </div>

        <!-- Department Queues -->
        <div class="queues-section" id="queuesSection">
            @forelse($queues as $department => $departmentQueues)
                <div class="department-queue">
                    <h3><i class="fas fa-building me-2"></i>{{ $department }}</h3>
                    @foreach($departmentQueues->take(10) as $queue)
                        <div class="queue-item {{ $queue->status }}" data-queue-id="{{ $queue->id }}">
                            <div>
                                <div class="queue-number">{{ $queue->queue_number }}</div>
                                <small class="text-muted">{{ $queue->patient_name }}</small>
                            </div>
                            <span class="status-badge status-{{ $queue->status }}">
                                {{ ucfirst(str_replace('_', ' ', $queue->status)) }}
                            </span>
                        </div>
                    @endforeach
                    @if($departmentQueues->count() > 10)
                        <div class="text-center mt-2">
                            <small class="text-muted">+{{ $departmentQueues->count() - 10 }} more</small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-12">
                    <div class="department-queue text-center">
                        <h3>No Active Queues</h3>
                        <p class="text-muted">Waiting for patients...</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Recently Completed -->
        @if($recentlyCompleted->count() > 0)
            <div class="recently-completed">
                <h3><i class="fas fa-check-circle me-2"></i>Recently Completed</h3>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($recentlyCompleted as $queue)
                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 10px 15px;">
                            {{ $queue->queue_number }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        let lastCalledQueue = null;

        // Update Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            document.getElementById('clock').innerHTML = `
                <div>${timeString}</div>
                <div style="font-size: 1.2rem; margin-top: 5px;">${dateString}</div>
            `;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Fetch Queue Updates
        function fetchQueueUpdates() {
            fetch('/hms/queue/current')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateDisplayBoard(data);
                    }
                })
                .catch(error => console.error('Error fetching queues:', error));
        }

        // Update Display Board
        function updateDisplayBoard(data) {
            // Update currently called
            if (data.currently_called) {
                const queue = data.currently_called;
                const calledNumberEl = document.getElementById('calledNumber');
                const calledLocationEl = document.getElementById('calledLocation');
                
                // If it's a new call, announce it
                if (lastCalledQueue !== queue.id) {
                    const location = queue.doctor 
                        ? (queue.doctor.department?.name || queue.department) 
                        : queue.department;
                    
                    calledNumberEl.textContent = queue.queue_number;
                    calledLocationEl.textContent = location;
                    
                    // Announce the queue number
                    announceQueueNumber(queue.queue_number, location);
                    
                    lastCalledQueue = queue.id;
                }
            } else {
                document.getElementById('calledNumber').textContent = '---';
                document.getElementById('calledLocation').textContent = 'Waiting...';
            }

            // Update queues by department
            updateQueuesByDepartment(data.queues);
        }

        // Update Queues by Department
        function updateQueuesByDepartment(queues) {
            const queuesSection = document.getElementById('queuesSection');
            const groupedQueues = {};
            
            // Group by department
            queues.forEach(queue => {
                if (!groupedQueues[queue.department]) {
                    groupedQueues[queue.department] = [];
                }
                groupedQueues[queue.department].push(queue);
            });

            // Update HTML
            let html = '';
            for (const [department, deptQueues] of Object.entries(groupedQueues)) {
                html += `
                    <div class="department-queue">
                        <h3><i class="fas fa-building me-2"></i>${department}</h3>
                `;
                
                deptQueues.slice(0, 10).forEach(queue => {
                    const status = queue.status.replace('_', '-');
                    html += `
                        <div class="queue-item ${queue.status}" data-queue-id="${queue.id}">
                            <div>
                                <div class="queue-number">${queue.queue_number}</div>
                                <small class="text-muted">${queue.patient_name}</small>
                            </div>
                            <span class="status-badge status-${queue.status}">
                                ${queue.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                            </span>
                        </div>
                    `;
                });

                if (deptQueues.length > 10) {
                    html += `
                        <div class="text-center mt-2">
                            <small class="text-muted">+${deptQueues.length - 10} more</small>
                        </div>
                    `;
                }

                html += '</div>';
            }

            if (!html) {
                html = `
                    <div class="col-12">
                        <div class="department-queue text-center">
                            <h3>No Active Queues</h3>
                            <p class="text-muted">Waiting for patients...</p>
                        </div>
                    </div>
                `;
            }

            queuesSection.innerHTML = html;
        }

        // Announce Queue Number (Text-to-Speech)
        function announceQueueNumber(queueNumber, location) {
            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();

                // Create announcement
                const message = `Queue number ${queueNumber}. Please proceed to ${location}.`;
                
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.lang = 'en-US';
                utterance.rate = 0.85;
                utterance.pitch = 1.0;
                utterance.volume = 1.0;

                // Speak the announcement
                window.speechSynthesis.speak(utterance);

                console.log('Announced:', message);

                // Repeat after 10 seconds
                setTimeout(() => {
                    if (window.speechSynthesis) {
                        const repeatUtterance = new SpeechSynthesisUtterance(message);
                        repeatUtterance.lang = 'en-US';
                        repeatUtterance.rate = 0.85;
                        repeatUtterance.pitch = 1.0;
                        repeatUtterance.volume = 1.0;
                        window.speechSynthesis.speak(repeatUtterance);
                    }
                }, 10000);
            } else {
                console.warn('Speech synthesis not supported in this browser');
            }
        }

        // Auto-refresh every 5 seconds
        setInterval(fetchQueueUpdates, 5000);
        
        // Initial fetch
        fetchQueueUpdates();
    </script>
</body>
</html>

