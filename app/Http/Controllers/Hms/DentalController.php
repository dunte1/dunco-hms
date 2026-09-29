<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DentalRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DentalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DentalRecord::with(['patient', 'dentist']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate(15);

        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tooth_number' => 'nullable|string',
            'procedure_type' => 'required|in:extraction,filling,scaling,root_canal,crown,prosthetic,other',
            'diagnosis' => 'nullable|string',
            'treatment_notes' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:planned,in_progress,completed',
        ]);

        $data['dentist_id'] = auth()->id();
        $record = DentalRecord::create($data);

        return response()->json([
            'message' => 'Dental record created successfully.',
            'record' => $record->load(['patient', 'dentist']),
        ], 201);
    }

    public function show(DentalRecord $record): JsonResponse
    {
        return response()->json($record->load(['patient', 'dentist']));
    }
}
