<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreLabRequestRequest;
use App\Http\Requests\Api\V1\UpdateLabRequestRequest;
use App\Models\LabRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabRequestController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = LabRequest::with(['patient', 'doctor']);

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
            $query->where('request_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('request_date', '<=', $request->input('to_date'));
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('id', 'desc')->paginate($perPage);

        return $this->paginated($paginator);
    }

    public function store(StoreLabRequestRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['request_number'] = 'LAB-' . str_pad(LabRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        if (empty($data['opd_visit_id']) && ! empty($data['patient_id'])) {
            $data['opd_visit_id'] = \App\Models\OpdVisit::where('patient_id', $data['patient_id'])
                ->whereNotIn('status', ['completed', 'discharged'])
                ->latest('id')
                ->value('id');
        }

        $labRequest = LabRequest::create($data);

        try {
            app(\App\Services\PatientFlowService::class)->afterLabRequest($labRequest);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('API lab request handoff failed', [
                'lab_request_id' => $labRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $this->created($labRequest->load(['patient', 'doctor']), 'Lab request created successfully');
    }

    public function show(LabRequest $labRequest): JsonResponse
    {
        $labRequest->load(['patient', 'doctor', 'opdVisit', 'items']);

        return $this->success($labRequest);
    }

    public function update(UpdateLabRequestRequest $request, LabRequest $labRequest): JsonResponse
    {
        $labRequest->update($request->validated());

        return $this->success($labRequest->fresh(['patient', 'doctor']), 'Lab request updated successfully');
    }
}
