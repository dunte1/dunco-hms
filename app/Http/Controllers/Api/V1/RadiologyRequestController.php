<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreRadiologyRequestRequest;
use App\Http\Requests\Api\V1\UpdateRadiologyRequestRequest;
use App\Models\RadiologyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RadiologyRequestController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = RadiologyRequest::with(['patient', 'doctor', 'radiologyTest']);

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

    public function store(StoreRadiologyRequestRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['request_number'] = 'RAD-' . str_pad(RadiologyRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        $radiologyRequest = RadiologyRequest::create($data);

        return $this->created($radiologyRequest->load(['patient', 'doctor', 'radiologyTest']), 'Radiology request created successfully');
    }

    public function show(RadiologyRequest $radiologyRequest): JsonResponse
    {
        $radiologyRequest->load(['patient', 'doctor', 'radiologyTest']);

        return $this->success($radiologyRequest);
    }

    public function update(UpdateRadiologyRequestRequest $request, RadiologyRequest $radiologyRequest): JsonResponse
    {
        $radiologyRequest->update($request->validated());

        return $this->success($radiologyRequest->fresh(['patient', 'doctor', 'radiologyTest']), 'Radiology request updated successfully');
    }
}
