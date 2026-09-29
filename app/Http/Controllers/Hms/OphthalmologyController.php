<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EyeExaminationRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OphthalmologyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = EyeExaminationRecord::with(['patient', 'examiner']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $records = $query->latest()->paginate(15);

        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visual_acuity_right' => 'nullable|numeric|min:0|max:10',
            'visual_acuity_left' => 'nullable|numeric|min:0|max:10',
            'iop_right' => 'nullable|string',
            'iop_left' => 'nullable|string',
            'refraction_right' => 'nullable|string',
            'refraction_left' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'glasses_prescribed' => 'nullable|boolean',
        ]);

        $data['examiner_id'] = auth()->id();
        $record = EyeExaminationRecord::create($data);

        return response()->json([
            'message' => 'Eye examination record created successfully.',
            'record' => $record->load(['patient', 'examiner']),
        ], 201);
    }
}
