<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ImagingSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImagingScheduleController extends ApiController
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'radiology_request_id' => 'required|exists:radiology_requests,id',
            'patient_id' => 'required|exists:patients,id',
            'radiology_test_id' => 'required|exists:radiology_tests,id',
            'modality' => 'required|in:xray,ultrasound,ct,mri,mammography,fluoroscopy',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled';
        $validated['scheduled_by'] = auth()->id();

        $schedule = ImagingSchedule::create($validated);

        return $this->created($schedule->load(['patient', 'radiologyRequest', 'radiologyTest']), 'Imaging study scheduled successfully');
    }

    public function index(Request $request): JsonResponse
    {
        $query = ImagingSchedule::with(['patient', 'radiologyRequest', 'radiologyTest']);

        if ($request->filled('modality')) {
            $query->where('modality', $request->input('modality'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->where('scheduled_date', $request->input('date'));
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('scheduled_date')->paginate($perPage);

        return $this->paginated($paginator);
    }
}
