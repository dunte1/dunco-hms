<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Services\BiometricService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BiometricController extends Controller
{
    protected $biometricService;
    
    public function __construct(BiometricService $biometricService)
    {
        $this->biometricService = $biometricService;
    }
    
    /**
     * Show biometric enrollment page
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $patientId = $request->get('patient_id');
        $stats = null;
        
        // If enrolling for a patient, get patient info
        $patient = null;
        if ($patientId) {
            $patient = \App\Models\Patient::find($patientId);
        }
        
        // Get stats for logged-in user
        if ($user) {
            $stats = $this->biometricService->getBiometricStats($user->id);
        }
        
        return view('hms.security.biometric.index', compact('stats', 'patient'));
    }
    
    /**
     * Register biometric data
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'biometric_type' => 'required|in:fingerprint,facial,iris,voice',
            'biometric_data' => 'required|array',
            'patient_id' => 'nullable|exists:patients,id',
        ]);
        
        // Use patient ID if provided, otherwise use logged-in user ID
        $userId = $validated['patient_id'] ?? Auth::id();
        
        // If registering for a patient, we need to create a user account for them first
        if (isset($validated['patient_id'])) {
            $patient = \App\Models\Patient::find($validated['patient_id']);
            if ($patient) {
                // Create or get user account for patient
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $patient->email ?? 'patient_' . $patient->id . '@duncohms.com'],
                    [
                        'name' => $patient->full_name,
                        'password' => bcrypt('patient_' . $patient->id . '_' . time()),
                    ]
                );
                $userId = $user->id;
            }
        }
        
        $result = $this->biometricService->registerBiometric(
            $userId,
            $validated['biometric_type'],
            $validated['biometric_data']
        );
        
        if ($result['success']) {
            return response()->json($result, 201);
        }
        
        return response()->json($result, 400);
    }
    
    /**
     * Verify biometric
     */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'biometric_type' => 'required|in:fingerprint,facial,iris,voice',
            'biometric_data' => 'required|array',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        $userId = $validated['user_id'] ?? Auth::id();
        
        $result = $this->biometricService->verifyBiometric(
            $userId,
            $validated['biometric_type'],
            $validated['biometric_data']
        );
        
        // If verification successful, log the user in
        if ($result['success'] && $result['verified']) {
            Auth::loginUsingId($userId);
            $result['redirect'] = route('dashboard');
        }
        
        return response()->json($result);
    }
    
    /**
     * Delete biometric data
     */
    public function delete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'biometric_type' => 'nullable|in:fingerprint,facial,iris,voice',
        ]);
        
        $deleted = $this->biometricService->deleteBiometric(
            Auth::id(),
            $validated['biometric_type'] ?? null
        );
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Biometric data deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete biometric data'
        ], 400);
    }
    
    /**
     * Get biometric statistics
     */
    public function stats(): JsonResponse
    {
        $stats = $this->biometricService->getBiometricStats(Auth::id());
        
        return response()->json($stats);
    }
}

