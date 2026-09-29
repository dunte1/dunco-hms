<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MealOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealOrderController extends Controller
{
    public function index(): JsonResponse
    {
        $meals = MealOrder::with(['patient', 'ward', 'orderedByUser'])->orderByDesc('created_at')->paginate(20);
        return response()->json($meals);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'ward_id' => 'nullable|exists:wards,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner,supplement',
            'diet_type' => 'required|in:regular,soft,liquid,NPO,diabetic,cardiac,renal',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'required|date',
            'order_time' => 'required',
        ]);

        $data['status'] = 'ordered';
        $data['ordered_by'] = auth()->id();

        MealOrder::create($data);

        return back()->with('status', 'Meal order placed successfully');
    }

    public function deliver(MealOrder $meal): RedirectResponse
    {
        $meal->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return back()->with('status', 'Meal marked as delivered');
    }
}
