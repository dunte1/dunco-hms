<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StorePrescriptionRequest;
use App\Http\Requests\Api\V1\UpdatePrescriptionRequest;
use App\Models\Prescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrescriptionController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Prescription::with(['patient', 'doctor']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->input('doctor_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->where('prescription_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('prescription_date', '<=', $request->input('to_date'));
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('id', 'desc')->paginate($perPage);

        return $this->paginated($paginator);
    }

    public function store(StorePrescriptionRequest $request): JsonResponse
    {
        $prescription = Prescription::create($request->validated());

        return $this->created($prescription->load(['patient', 'doctor']), 'Prescription created successfully');
    }

    public function show(Prescription $prescription): JsonResponse
    {
        $prescription->load(['patient', 'doctor', 'opdVisit', 'items', 'signedBy']);

        return $this->success($prescription);
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription): JsonResponse
    {
        $prescription->update($request->validated());

        return $this->success($prescription->fresh(['patient', 'doctor']), 'Prescription updated successfully');
    }
}
