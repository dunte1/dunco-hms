<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DrugReturn;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DrugReturnController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dispensation_id' => 'nullable|exists:dispensations,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|exists:medicines,id',
            'batch_id' => 'nullable|exists:medicine_batches,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
            'return_type' => 'required|in:patient_return,expired,damaged,recalled',
        ]);

        $drugReturn = DrugReturn::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return back()->with('success', "Drug return request #{$drugReturn->id} submitted successfully.");
    }

    public function approve(Request $request, DrugReturn $return): RedirectResponse
    {
        if ($return->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be approved.');
        }

        $return->approve(auth()->user());

        return back()->with('success', "Drug return #{$return->id} approved successfully.");
    }

    public function process(Request $request, DrugReturn $return): RedirectResponse
    {
        if ($return->status !== 'approved') {
            return back()->with('error', 'Only approved returns can be processed.');
        }

        $return->process(auth()->user());

        if ($return->return_type === 'patient_return' || $return->return_type === 'recalled') {
            $medicine = Medicine::find($return->medicine_id);
            if ($medicine) {
                $medicine->increment('stock_quantity', $return->quantity);
            }
        }

        return back()->with('success', "Drug return #{$return->id} processed successfully.");
    }
}
