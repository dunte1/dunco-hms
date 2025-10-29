<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
            'photo' => null, // You can add photo support later
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
            'photo' => null,
        ]);
    }
}
