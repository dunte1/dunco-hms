<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EntRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = EntRecord::with(['patient', 'examiner']);

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
            'ear_findings' => 'nullable|string',
            'nose_findings' => 'nullable|string',
            'throat_findings' => 'nullable|string',
            'hearing_test' => 'nullable|string',
            'endoscopy_findings' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
        ]);

        $data['examiner_id'] = auth()->id();
        $record = EntRecord::create($data);

        return response()->json([
            'message' => 'ENT record created successfully.',
            'record' => $record->load(['patient', 'examiner']),
        ], 201);
    }
}
