<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function patients(Request $request): JsonResponse
    {
        $term = (string) $request->query('q', '');

        $patients = Patient::query()
            ->search($term)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit((int) $request->query('limit', 20))
            ->get(['id', 'first_name', 'last_name', 'patient_no', 'phone', 'national_id'])
            ->map(fn ($patient) => [
                'id' => $patient->id,
                'label' => trim("{$patient->first_name} {$patient->last_name}")
                    . ($patient->patient_no ? " ({$patient->patient_no})" : '')
                    . ($patient->national_id ? " · ID {$patient->national_id}" : ''),
                'patient_no' => $patient->patient_no,
                'phone' => $patient->phone,
            ]);

        return response()->json(['data' => $patients]);
    }

    public function doctors(Request $request): JsonResponse
    {
        $term = (string) $request->query('q', '');

        $doctors = Doctor::query()
            ->search($term)
            ->with('department')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit((int) $request->query('limit', 20))
            ->get(['id', 'first_name', 'last_name', 'doctor_department_id'])
            ->map(fn ($doctor) => [
                'id' => $doctor->id,
                'label' => trim("{$doctor->first_name} {$doctor->last_name}")
                    . ($doctor->department?->name ? " — {$doctor->department->name}" : ''),
            ]);

        return response()->json(['data' => $doctors]);
    }

    public function employees(Request $request): JsonResponse
    {
        $term = (string) $request->query('q', '');

        $employees = Employee::query()
            ->search($term)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit((int) $request->query('limit', 20))
            ->get(['id', 'employee_id', 'first_name', 'last_name', 'position'])
            ->map(fn ($employee) => [
                'id' => $employee->id,
                'label' => trim("{$employee->first_name} {$employee->last_name}")
                    . ($employee->employee_id ? " ({$employee->employee_id})" : '')
                    . ($employee->position ? " — {$employee->position}" : ''),
            ]);

        return response()->json(['data' => $employees]);
    }
}
