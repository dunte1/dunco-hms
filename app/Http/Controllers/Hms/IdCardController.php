<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class IdCardController extends Controller
{
    /**
     * Generate and download patient ID card
     */
    public function patientCard(Patient $patient): Response
    {
        $data = [
            'patient' => $patient,
            'type' => 'Patient',
            'id' => $patient->patient_no,
            'name' => $patient->full_name,
            'dob' => $patient->dob,
            'gender' => $patient->gender,
            'photo' => null, // You can add photo support later
        ];

        $pdf = Pdf::loadView('hms.id-cards.patient-card', $data);
        $pdf->setPaper([0, 0, 370, 240], 'portrait'); // Business card size
        
        return $pdf->download('Patient-ID-' . $patient->patient_no . '.pdf');
    }

    /**
     * Generate and download employee ID card
     */
    public function employeeCard(Employee $employee): Response
    {
        // Get theme settings for logo
        $themeSettings = [
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', 'DuncoHMS'),
        ];

        // Get employee photo if available
        $photoPath = null;
        if ($employee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->photo)) {
            $photoPath = storage_path('app/public/' . $employee->photo);
        }

        $data = [
            'employee' => $employee,
            'type' => 'Staff',
            'id' => $employee->employee_id,
            'name' => $employee->full_name,
            'dob' => $employee->date_of_birth,
            'gender' => $employee->gender,
            'department' => $employee->department->name ?? 'N/A',
            'position' => $employee->position,
            'hire_date' => $employee->hire_date,
            'photo' => $photoPath,
            'themeSettings' => $themeSettings,
        ];

        $pdf = Pdf::loadView('hms.id-cards.employee-card', $data);
        $pdf->setPaper([0, 0, 370, 240], 'portrait'); // Business card size
        
        return $pdf->download('Employee-ID-' . $employee->employee_id . '.pdf');
    }

    /**
     * Preview patient ID card
     */
    public function previewPatient(Patient $patient): View
    {
        return view('hms.id-cards.patient-card', [
            'patient' => $patient,
            'type' => 'Patient',
            'id' => $patient->patient_no,
            'name' => $patient->full_name,
            'dob' => $patient->dob,
            'gender' => $patient->gender,
            'photo' => null,
        ]);
    }

    /**
     * Preview employee ID card
     */
    public function previewEmployee(Employee $employee): View
    {
        // Get theme settings for logo
        $themeSettings = [
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', 'DuncoHMS'),
        ];

        // Get employee photo if available
        $photoPath = null;
        if ($employee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->photo)) {
            $photoPath = \Illuminate\Support\Facades\Storage::disk('public')->url($employee->photo);
        }

        return view('hms.id-cards.employee-card', [
            'employee' => $employee,
            'type' => 'Staff',
            'id' => $employee->employee_id,
            'name' => $employee->full_name,
            'dob' => $employee->date_of_birth,
            'gender' => $employee->gender,
            'department' => $employee->department->name ?? 'N/A',
            'position' => $employee->position,
            'hire_date' => $employee->hire_date,
            'photo' => $photoPath,
            'themeSettings' => $themeSettings,
        ]);
    }

    /**
     * Generate QR code for patient ID
     */
    public function generatePatientQR(Patient $patient)
    {
        $qrData = json_encode([
            'type' => 'patient',
            'id' => $patient->id,
            'patient_no' => $patient->patient_no,
            'name' => $patient->full_name,
        ]);

        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($qrData);

        return response($qrCode)->header('Content-Type', 'image/png');
    }

    /**
     * Generate QR code for employee ID
     */
    public function generateEmployeeQR(Employee $employee)
    {
        $qrData = json_encode([
            'type' => 'employee',
            'id' => $employee->id,
            'employee_id' => $employee->employee_id,
            'name' => $employee->full_name,
        ]);

        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($qrData);

        return response($qrCode)->header('Content-Type', 'image/png');
    }

    /**
     * Scan QR code and retrieve information
     */
    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $data = json_decode($request->qr_data, true);

        if (!$data || !isset($data['type'], $data['id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code data',
            ], 400);
        }

        if ($data['type'] === 'patient') {
            $patient = Patient::find($data['id']);
            if ($patient) {
                return response()->json([
                    'success' => true,
                    'type' => 'patient',
                    'data' => [
                        'id' => $patient->id,
                        'patient_no' => $patient->patient_no,
                        'name' => $patient->full_name,
                        'dob' => $patient->dob,
                        'gender' => $patient->gender,
                    ],
                ]);
            }
        } elseif ($data['type'] === 'employee') {
            $employee = Employee::find($data['id']);
            if ($employee) {
                return response()->json([
                    'success' => true,
                    'type' => 'employee',
                    'data' => [
                        'id' => $employee->id,
                        'employee_id' => $employee->employee_id,
                        'name' => $employee->full_name,
                        'department' => $employee->department->name ?? 'N/A',
                        'position' => $employee->position,
                    ],
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Record not found',
        ], 404);
    }

    /**
     * Generate and download user ID card
     */
    public function userCard(User $user): Response
    {
        // Get theme settings for logo
        $themeSettings = [
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', 'DuncoHMS'),
        ];

        // Get user photo if available (from employee relation if exists)
        $photoPath = null;
        $employee = $user->employee ?? null;
        
        if ($employee && $employee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->photo)) {
            $photoPath = storage_path('app/public/' . $employee->photo);
        }

        // Get primary role
        $primaryRole = $user->roles->first();
        
        $data = [
            'user' => $user,
            'employee' => $employee,
            'type' => 'User',
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $primaryRole ? $primaryRole->name : 'User',
            'department' => $employee && $employee->department ? $employee->department->name : ($primaryRole ? $primaryRole->name : 'N/A'),
            'position' => $employee ? $employee->position : ($primaryRole ? $primaryRole->name : 'Staff'),
            'photo' => $photoPath,
            'themeSettings' => $themeSettings,
        ];

        $pdf = Pdf::loadView('hms.id-cards.employee-card', $data);
        $pdf->setPaper([0, 0, 370, 240], 'portrait'); // Business card size
        
        return $pdf->download('User-ID-' . $user->id . '.pdf');
    }

    /**
     * Preview user ID card
     */
    public function previewUser(User $user): View
    {
        // Get theme settings for logo
        $themeSettings = [
            'hospital_logo' => \App\Models\SystemSetting::get('hospital_logo', ''),
            'hospital_name' => \App\Models\SystemSetting::get('hospital_name', 'DuncoHMS'),
        ];

        // Get user photo if available
        $photoPath = null;
        $employee = $user->employee ?? null;
        
        if ($employee && $employee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($employee->photo)) {
            $photoPath = \Illuminate\Support\Facades\Storage::disk('public')->url($employee->photo);
        }

        // Get primary role
        $primaryRole = $user->roles->first();

        return view('hms.id-cards.employee-card', [
            'user' => $user,
            'employee' => $employee,
            'type' => 'User',
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $primaryRole ? $primaryRole->name : 'User',
            'department' => $employee && $employee->department ? $employee->department->name : ($primaryRole ? $primaryRole->name : 'N/A'),
            'position' => $employee ? $employee->position : ($primaryRole ? $primaryRole->name : 'Staff'),
            'photo' => $photoPath,
            'themeSettings' => $themeSettings,
        ]);
    }
}
