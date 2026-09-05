<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\QueueManagement;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class QueueManagementController extends Controller
{
    public function index(): View
    {
        $queues = QueueManagement::with(['patient', 'doctor'])
            ->latest('check_in_time')
            ->paginate(20);

        // Statistics
        $todayQueues = QueueManagement::whereDate('check_in_time', today())->count();
        $waitingCount = QueueManagement::where('status', 'waiting')->count();
        $calledCount = QueueManagement::where('status', 'called')->count();
        $inProgressCount = QueueManagement::where('status', 'in_progress')->count();
        
        // Department-wise counts
        $departmentCounts = QueueManagement::where('status', 'waiting')
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department');

        return view('hms.queue.index', compact(
            'queues', 
            'todayQueues', 
            'waitingCount', 
            'calledCount', 
            'inProgressCount',
            'departmentCounts'
        ));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no', 'phone']);
        $doctors = Doctor::with('department')->orderBy('first_name')->get();
        $departments = DoctorDepartment::orderBy('name')->get();

        return view('hms.queue.create', compact('patients', 'doctors', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'department' => 'required|string|max:100',
            'queue_type' => 'required|in:appointment,walk_in,emergency,follow_up',
            'priority' => 'nullable|in:low,normal,high,emergency',
            'notes' => 'nullable|string',
        ]);

        // Generate queue number
        $departmentCode = strtoupper(substr($data['department'], 0, 3));
        $date = date('Ymd');
        $lastQueue = QueueManagement::where('queue_number', 'like', $departmentCode . '-' . $date . '%')
            ->orderBy('queue_number', 'desc')
            ->first();

        $nextNumber = $lastQueue 
            ? intval(substr($lastQueue->queue_number, -4)) + 1 
            : 1;

        $data['queue_number'] = $departmentCode . '-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $data['status'] = 'waiting';
        $data['check_in_time'] = now();
        $data['priority'] = $data['priority'] ?? 'normal';

        // If patient_id is provided, fetch patient details
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            $data['patient_name'] = $patient->first_name . ' ' . $patient->last_name;
            $data['patient_phone'] = $patient->phone ?? $data['patient_phone'];
        }

        QueueManagement::create($data);

        return redirect()->route('hms.queue.index')
            ->with('success', "Queue ticket generated: {$data['queue_number']}");
    }

    public function callQueue(Request $request, QueueManagement $queue): JsonResponse
    {
        // Update queue status to called
        $queue->update([
            'status' => 'called',
            'called_time' => now(),
        ]);

        // Get location/room information if doctor assigned
        $location = 'Reception';
        if ($queue->doctor) {
            $location = $queue->doctor->department->name ?? $queue->department;
            // If doctor has room number, include it
            if (isset($queue->doctor->room_number)) {
                $location .= ' - Room ' . $queue->doctor->room_number;
            }
        }

        return response()->json([
            'success' => true,
            'queue_number' => $queue->queue_number,
            'patient_name' => $queue->patient_name,
            'department' => $queue->department,
            'location' => $location,
            'message' => "Queue {$queue->queue_number} called"
        ]);
    }

    public function startService(Request $request, QueueManagement $queue): JsonResponse
    {
        $queue->update([
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service started'
        ]);
    }

    public function completeQueue(Request $request, QueueManagement $queue): JsonResponse
    {
        $queue->update([
            'status' => 'completed',
            'completed_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Queue completed'
        ]);
    }

    public function cancelQueue(Request $request, QueueManagement $queue): RedirectResponse
    {
        $queue->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('hms.queue.index')
            ->with('success', 'Queue cancelled');
    }

    public function destroy(QueueManagement $queue): RedirectResponse
    {
        $queue->delete();

        return redirect()->route('hms.queue.index')
            ->with('success', 'Queue deleted');
    }

    public function show(QueueManagement $queue): View
    {
        $queue->load(['patient', 'doctor']);
        return view('hms.queue.show', compact('queue'));
    }

    public function edit(QueueManagement $queue): View
    {
        $queue->load(['patient', 'doctor']);
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::with('department')->orderBy('first_name')->get();
        $departments = DoctorDepartment::orderBy('name')->get();
        return view('hms.queue.edit', compact('queue', 'patients', 'doctors', 'departments'));
    }

    public function update(Request $request, QueueManagement $queue): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'department' => 'required|string|max:100',
            'queue_type' => 'required|in:appointment,walk_in,emergency,follow_up',
            'priority' => 'nullable|in:low,normal,high,emergency',
            'notes' => 'nullable|string',
        ]);

        $queue->update($data);
        return redirect()->route('hms.queue.index')->with('success', 'Queue updated');
    }

    public function displayBoard(): View
    {
        // Get waiting, called, and in_progress queues grouped by department
        $queues = QueueManagement::whereIn('status', ['waiting', 'called', 'in_progress'])
            ->with(['patient', 'doctor'])
            ->orderBy('priority', 'desc')
            ->orderBy('check_in_time', 'asc')
            ->get()
            ->groupBy('department');

        $currentlyCalled = QueueManagement::where('status', 'called')
            ->with(['patient', 'doctor'])
            ->orderBy('called_time', 'desc')
            ->limit(10)
            ->get();

        $recentlyCompleted = QueueManagement::where('status', 'completed')
            ->with(['patient', 'doctor'])
            ->whereDate('completed_time', today())
            ->orderBy('completed_time', 'desc')
            ->limit(5)
            ->get();

        return view('hms.queue.display-board', compact('queues', 'currentlyCalled', 'recentlyCompleted'));
    }

    public function getCurrentQueues(): JsonResponse
    {
        $queues = QueueManagement::whereIn('status', ['waiting', 'called', 'in_progress'])
            ->with(['patient', 'doctor'])
            ->orderBy('priority', 'desc')
            ->orderBy('check_in_time', 'asc')
            ->get();

        $currentlyCalled = QueueManagement::where('status', 'called')
            ->with(['patient', 'doctor'])
            ->orderBy('called_time', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'queues' => $queues,
            'currently_called' => $currentlyCalled
        ]);
    }

    public function tokenGeneration(): View
    {
        $departments = DoctorDepartment::orderBy('name')->get();
        
        return view('hms.queue.token-generation', compact('departments'));
    }

    public function generateToken(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:100',
            'queue_type' => 'required|in:appointment,walk_in,emergency,follow_up',
        ]);

        // Generate queue number
        $departmentCode = strtoupper(substr($data['department'], 0, 3));
        $date = date('Ymd');
        $lastQueue = QueueManagement::where('queue_number', 'like', $departmentCode . '-' . $date . '%')
            ->orderBy('queue_number', 'desc')
            ->first();

        $nextNumber = $lastQueue 
            ? intval(substr($lastQueue->queue_number, -4)) + 1 
            : 1;

        $data['queue_number'] = $departmentCode . '-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $data['status'] = 'waiting';
        $data['check_in_time'] = now();
        $data['priority'] = $data['queue_type'] === 'emergency' ? 'emergency' : 'normal';

        $queue = QueueManagement::create($data);

        return redirect()->route('hms.queue.token-success', $queue->id)
            ->with('success', 'Token generated successfully');
    }

    public function tokenSuccess(QueueManagement $queue): View
    {
        return view('hms.queue.token-success', compact('queue'));
    }

    /**
     * Kiosk mode for queue display
     */
    public function kioskMode(): View
    {
        $queues = QueueManagement::with(['patient', 'doctor'])
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->orderBy('token_number')
            ->get();
        
        $currentQueues = QueueManagement::where('status', 'in_progress')
            ->with(['patient', 'doctor'])
            ->get();
        
        return view('hms.queue.kiosk', compact('queues', 'currentQueues'));
    }

    /**
     * Smart display board with enhanced features
     */
    public function smartDisplay(): View
    {
        $queues = QueueManagement::with(['patient', 'doctor'])
            ->whereIn('status', ['waiting', 'called', 'in_progress'])
            ->orderBy('token_number')
            ->get();
        
        $stats = [
            'waiting' => QueueManagement::where('status', 'waiting')->count(),
            'called' => QueueManagement::where('status', 'called')->count(),
            'in_progress' => QueueManagement::where('status', 'in_progress')->count(),
        ];
        
        return view('hms.queue.smart-display', compact('queues', 'stats'));
    }
}

