<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RehabSessionRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RehabController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = RehabSessionRecord::with(['patient', 'therapist']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        $records = $query->latest()->paginate(15);

        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'session_type' => 'required|in:physiotherapy,occupational_therapy',
            'treatment_area' => 'required|string',
            'session_notes' => 'required|string',
            'exercises_performed' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'next_session_date' => 'nullable|date',
        ]);

        $data['therapist_id'] = auth()->id();
        $record = RehabSessionRecord::create($data);

        return response()->json([
            'message' => 'Rehab session record created successfully.',
            'record' => $record->load(['patient', 'therapist']),
        ], 201);
    }
}
