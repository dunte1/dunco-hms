<?php

namespace App\Http\Controllers\Telemedicine;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineSession;
use App\Models\Patient;
use App\Models\Doctor;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TelemedicineController extends Controller
{
    public function __construct(
        protected ZoomService $zoomService
    ) {}

    public function index(): View
    {
        $sessions = TelemedicineSession::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);
        
        return view('hms.telemedicine.index', compact('sessions'));
    }

    public function create(): View
    {
        $patients = Patient::latest()->get();
        $doctors = Doctor::with('department')->latest()->get();
        
        return view('hms.telemedicine.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'scheduled_time' => 'required|date|after:now',
            'session_type' => 'required|string|in:video,audio,chat',
            'platform' => 'required|string|in:zoom,teams,custom',
            'notes' => 'nullable|string',
        ]);

        $sessionId = 'TEL-' . strtoupper(uniqid());
        
        // Generate meeting details based on platform
        $meetingDetails = $this->generateMeetingDetails($data['platform'], $data);

        $session = TelemedicineSession::create([
            'session_id' => $sessionId,
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'scheduled_time' => $data['scheduled_time'],
            'session_type' => $data['session_type'],
            'platform' => $data['platform'],
            'meeting_url' => $meetingDetails['url'],
            'meeting_id' => $meetingDetails['id'],
            'notes' => $data['notes'],
        ]);

        // Send notifications
        $this->sendSessionNotifications($session);

        return response()->json([
            'success' => true,
            'data' => $session,
            'message' => 'Telemedicine session scheduled successfully'
        ], 201);
    }

    public function startSession(TelemedicineSession $session): JsonResponse
    {
        if ($session->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Session cannot be started'
            ], 400);
        }

        $session->update([
            'status' => 'active',
            'start_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $session,
            'message' => 'Session started successfully'
        ]);
    }

    public function endSession(TelemedicineSession $session, Request $request): JsonResponse
    {
        if ($session->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Session is not active'
            ], 400);
        }

        $data = $request->validate([
            'session_data' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $session->update([
            'status' => 'completed',
            'end_time' => now(),
            'session_data' => $data['session_data'] ?? [],
            'notes' => $data['notes'] ?? $session->notes,
        ]);

        return response()->json([
            'success' => true,
            'data' => $session,
            'message' => 'Session completed successfully'
        ]);
    }

    public function joinSession(TelemedicineSession $session): View
    {
        if ($session->status !== 'active') {
            abort(403, 'Session is not active');
        }

        return view('hms.telemedicine.join', compact('session'));
    }

    public function getSessionDetails(TelemedicineSession $session): JsonResponse
    {
        $session->load(['patient', 'doctor.department']);

        return response()->json([
            'success' => true,
            'data' => $session
        ]);
    }

    public function getUpcomingSessions(Request $request): JsonResponse
    {
        $doctorId = $request->get('doctor_id');
        $patientId = $request->get('patient_id');

        $query = TelemedicineSession::with(['patient', 'doctor'])
            ->where('status', 'scheduled')
            ->where('scheduled_time', '>=', now());

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        $sessions = $query->orderBy('scheduled_time')->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    private function generateMeetingDetails(string $platform, array $data = []): array
    {
        switch ($platform) {
            case 'zoom':
                // Use ZoomService if configured, otherwise fallback
                if ($this->zoomService->isConfigured()) {
                    $patient = Patient::find($data['patient_id'] ?? null);
                    $doctor = Doctor::find($data['doctor_id'] ?? null);
                    
                    $meetingData = [
                        'topic' => 'Telemedicine Consultation - ' . ($patient ? $patient->first_name . ' ' . $patient->last_name : 'Patient'),
                        'start_time' => $data['scheduled_time'] ?? now()->addHour()->format('Y-m-d\TH:i:s\Z'),
                        'duration' => 30,
                        'timezone' => config('app.timezone', 'UTC'),
                    ];
                    
                    $result = $this->zoomService->createMeeting($meetingData);
                    
                    if ($result['success']) {
                        return [
                            'url' => $result['join_url'],
                            'id' => $result['meeting_id'],
                            'password' => $result['password'] ?? null,
                            'start_url' => $result['start_url'] ?? null,
                        ];
                    }
                    
                    // Fallback if Zoom API fails
                    Log::warning('Zoom API failed, using fallback', ['error' => $result['message'] ?? 'Unknown error']);
                }
                
                // Fallback URL generation when Zoom is not configured
                return [
                    'url' => 'https://zoom.us/j/' . rand(100000000, 999999999),
                    'id' => (string) rand(100000000, 999999999),
                ];
                
            case 'teams':
                return [
                    'url' => 'https://teams.microsoft.com/l/meetup-join/' . uniqid(),
                    'id' => uniqid(),
                ];
            case 'custom':
                return [
                    'url' => route('telemedicine.join', ['session' => 'TEMP-' . uniqid()]),
                    'id' => 'TEMP-' . uniqid(),
                ];
            default:
                return [
                    'url' => '#',
                    'id' => uniqid(),
                ];
        }
    }

    private function sendSessionNotifications(TelemedicineSession $session): void
    {
        // Send email notifications to patient and doctor
        // This would integrate with your notification system
        // For now, we'll just log the action
        
        \Log::info('Telemedicine session scheduled', [
            'session_id' => $session->session_id,
            'patient_id' => $session->patient_id,
            'doctor_id' => $session->doctor_id,
            'scheduled_time' => $session->scheduled_time,
        ]);
    }
}
