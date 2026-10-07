<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\NutritionRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NutritionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $query = NutritionRecord::with(['patient', 'nutritionist']);

            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->patient_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return response()->json($query->latest()->paginate(15));
        }

        $query = NutritionRecord::with(['patient', 'nutritionist']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('diet_plan', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => NutritionRecord::count(),
            'active' => NutritionRecord::where('status', 'active')->count(),
            'completed' => NutritionRecord::where('status', 'completed')->count(),
            'high_risk' => NutritionRecord::where('malnutrition_risk', 'high')->count(),
        ];

        return view('hms.nutrition.records', compact('records', 'stats'));
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
