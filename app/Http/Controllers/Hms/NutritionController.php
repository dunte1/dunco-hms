<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\NutritionRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NutritionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = NutritionRecord::with(['patient', 'nutritionist']);

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
            'assessment_type' => 'required|in:screening,assessment,follow_up',
            'bmi' => 'nullable|numeric|min:0|max:100',
            'malnutrition_risk' => 'required|in:none,low,moderate,high',
            'diet_plan' => 'required|string',
            'calorie_target' => 'nullable|integer|min:0',
            'protein_target' => 'nullable|numeric|min:0',
            'notes' => 'required|string',
            'status' => 'nullable|in:active,completed',
        ]);

        $data['nutritionist_id'] = auth()->id();
        $record = NutritionRecord::create($data);

        return response()->json([
            'message' => 'Nutrition record created successfully.',
            'record' => $record->load(['patient', 'nutritionist']),
        ], 201);
    }
}
