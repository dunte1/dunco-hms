<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\PatientPortalAccount;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller
{
    public function login(): View
    {
        return view('patient-portal.login');
    }

    public function authenticate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $account = PatientPortalAccount::where('username', $data['username'])
            ->where('is_active', true)
            ->first();

        if (!$account || !Hash::check($data['password'], $account->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $account->update(['last_login' => now()]);

        // Set session for patient portal
        session(['patient_portal_user' => $account->id]);

        return response()->json([
            'success' => true,
            'data' => $account,
            'message' => 'Login successful'
        ]);
    }

    public function dashboard(): View
    {
        $account = $this->getCurrentAccount();
        $patient = $account->patient;

        $stats = [
            'total_appointments' => Appointment::where('patient_id', $patient->id)->count(),
            'upcoming_appointments' => Appointment::where('patient_id', $patient->id)
                ->where('appointment_date', '>=', now())
                ->count(),
            'total_prescriptions' => Prescription::where('patient_id', $patient->id)->count(),
            'pending_lab_results' => LabRequest::where('patient_id', $patient->id)
                ->where('status', 'pending')
                ->count(),
        ];

        $recentAppointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->take(5)
            ->get();

        $recentPrescriptions = Prescription::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->take(5)
            ->get();

        return view('patient-portal.dashboard', compact('stats', 'recentAppointments', 'recentPrescriptions'));
    }

    public function appointments(): View
    {
        $account = $this->getCurrentAccount();
        $appointments = Appointment::with('doctor.department')
            ->where('patient_id', $account->patient_id)
            ->latest()
            ->paginate(15);

        return view('patient-portal.appointments', compact('appointments'));
    }

    public function bookAppointment(Request $request): JsonResponse
    {
        $account = $this->getCurrentAccount();
        
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $account->patient_id,
            'doctor_id' => $data['doctor_id'],
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $appointment,
            'message' => 'Appointment request submitted successfully'
        ], 201);
    }

    public function prescriptions(): View
    {
        $account = $this->getCurrentAccount();
        $prescriptions = Prescription::with(['doctor', 'items.medicine'])
            ->where('patient_id', $account->patient_id)
            ->latest()
            ->paginate(15);

        return view('patient-portal.prescriptions', compact('prescriptions'));
    }

    public function labResults(): View
    {
        $account = $this->getCurrentAccount();
        $labRequests = LabRequest::with(['items.labTest'])
            ->where('patient_id', $account->patient_id)
            ->latest()
            ->paginate(15);

        return view('patient-portal.lab-results', compact('labRequests'));
    }

    public function medicalHistory(): View
    {
        $account = $this->getCurrentAccount();
        $patient = $account->patient;

        $medicalHistory = [
            'allergies' => $patient->allergies ?? 'None recorded',
            'medications' => $patient->medications ?? 'None recorded',
            'medical_conditions' => $patient->medical_conditions ?? 'None recorded',
            'surgical_history' => $patient->surgical_history ?? 'None recorded',
        ];

        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        $prescriptions = Prescription::with(['doctor', 'items.medicine'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('patient-portal.medical-history', compact('medicalHistory', 'appointments', 'prescriptions'));
    }

    public function billing(): View
    {
        $account = $this->getCurrentAccount();
        $invoices = Invoice::with(['items'])
            ->where('patient_id', $account->patient_id)
            ->latest()
            ->paginate(15);

        return view('patient-portal.billing', compact('invoices'));
    }

    public function profile(): View
    {
        $account = $this->getCurrentAccount();
        $patient = $account->patient;

        return view('patient-portal.profile', compact('account', 'patient'));
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $account = $this->getCurrentAccount();
        $patient = $account->patient;

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $patient->update($data);

        // Update portal account email if changed
        if ($data['email'] !== $account->email) {
            $account->update(['email' => $data['email']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $account = $this->getCurrentAccount();

        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $account->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $account->update([
            'password_hash' => Hash::make($data['new_password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function enableTwoFactor(Request $request): JsonResponse
    {
        $account = $this->getCurrentAccount();

        // Generate 2FA secret (simplified)
        $secret = base32_encode(random_bytes(20));
        
        $account->update([
            'two_factor_secret' => $secret,
            'two_factor_enabled' => true
        ]);

        return response()->json([
            'success' => true,
            'secret' => $secret,
            'qr_code' => $this->generateQRCode($secret, $account->email),
            'message' => 'Two-factor authentication enabled'
        ]);
    }

    public function disableTwoFactor(Request $request): JsonResponse
    {
        $account = $this->getCurrentAccount();

        $account->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication disabled'
        ]);
    }

    public function logout(): JsonResponse
    {
        session()->forget('patient_portal_user');

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    private function getCurrentAccount(): PatientPortalAccount
    {
        $accountId = session('patient_portal_user');
        
        if (!$accountId) {
            abort(401, 'Not authenticated');
        }

        $account = PatientPortalAccount::with('patient')->find($accountId);
        
        if (!$account || !$account->is_active) {
            abort(401, 'Account not found or inactive');
        }

        return $account;
    }

    private function generateQRCode(string $secret, string $email): string
    {
        // Generate QR code for 2FA setup
        $issuer = config('app.name');
        $accountName = $email;
        $otpAuthUrl = "otpauth://totp/{$issuer}:{$accountName}?secret={$secret}&issuer={$issuer}";
        
        // In a real implementation, you would use a QR code library
        return "data:image/png;base64," . base64_encode("QR Code for: {$otpAuthUrl}");
    }
}
