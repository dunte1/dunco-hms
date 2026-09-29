<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StorePatientRequest;
use App\Http\Requests\Api\V1\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('patient_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('id', 'desc')->paginate($perPage);

        return $this->paginated($paginator);
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());

        return $this->created($patient, 'Patient created successfully');
    }

    public function show(Patient $patient): JsonResponse
    {
        $patient->load([
            'appointments.doctor',
            'ipdAdmissions',
            'opdVisits',
            'prescriptions.doctor',
        ]);

        return $this->success($patient);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());

        return $this->success($patient, 'Patient updated successfully');
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $id = $patient->id;
        $deleted = \DB::table('patients')->where('id', $id)->delete();

        if (!$deleted) {
            return $this->error('Failed to delete patient.', 500);
        }

        return $this->noContent('Patient deleted successfully');
    }
}
