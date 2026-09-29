<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Pregnancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pregnancy_id' => 'required|exists:pregnancies,id',
            'patient_id' => 'required|exists:patients,id',
            'labour_record_id' => 'nullable|exists:labour_records,id',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'mode' => 'required|in:normal_assisted,vacuum,forceps,caesarean,episiotomy',
            'baby_sex' => 'required|in:male,female',
            'birth_weight_grams' => 'required|integer|min:500|max:8000',
            'apgar_1_min' => 'nullable|integer|min:0|max:10',
            'apgar_5_min' => 'nullable|integer|min:0|max:10',
            'alive' => 'boolean',
            'complications' => 'nullable|string',
            'placenta_delivered_time' => 'nullable|date',
            'placenta_complete' => 'nullable|boolean',
            'blood_loss_ml' => 'nullable|integer|min:0',
        ]);

        $data['delivered_by'] = auth()->id();
        $data['alive'] = $data['alive'] ?? true;

        Delivery::create($data);

        // Mark pregnancy as completed
        Pregnancy::where('id', $data['pregnancy_id'])
            ->where('status', 'active')
            ->update(['status' => 'completed']);

        return back()->with('success', 'Delivery recorded successfully!');
    }
}
