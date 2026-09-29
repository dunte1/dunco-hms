<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Dispensation;
use App\Models\DispensationItem;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispensationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'items' => 'required|array|min:1',
            'items.*.prescription_item_id' => 'required|exists:prescription_items,id',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.batch_id' => 'nullable|exists:medicine_batches,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        $prescription = Prescription::findOrFail($validated['prescription_id']);

        $dispensation = DB::transaction(function () use ($validated, $prescription) {
            $totalAmount = collect($validated['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_price']);
            $discount = $validated['discount_amount'] ?? 0;
            $tax = $validated['tax_amount'] ?? 0;

            $dispensation = Dispensation::create([
                'prescription_id' => $validated['prescription_id'],
                'patient_id' => $prescription->patient_id,
                'pharmacist_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'net_amount' => $totalAmount - $discount + $tax,
                'payment_status' => 'unpaid',
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                DispensationItem::create([
                    'dispensation_id' => $dispensation->id,
                    'prescription_item_id' => $item['prescription_item_id'],
                    'medicine_id' => $item['medicine_id'],
                    'batch_id' => $item['batch_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                $medicine = Medicine::findOrFail($item['medicine_id']);
                $newStock = $medicine->stock_quantity - $item['quantity'];
                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for {$medicine->name}");
                }
                $medicine->update(['stock_quantity' => $newStock]);

                if (!empty($item['batch_id'])) {
                    $batch = MedicineBatch::findOrFail($item['batch_id']);
                    $batch->increment('quantity_sold', $item['quantity']);
                }
            }

            return $dispensation;
        });

        return redirect()->route('hms.pharmacy.medicines.index')
            ->with('success', "Dispensation {$dispensation->dispensation_number} created successfully.");
    }

    public function verify(Request $request, Dispensation $dispensation): RedirectResponse
    {
        if ($dispensation->status !== 'pending') {
            return back()->with('error', 'Only pending dispensations can be verified.');
        }

        $dispensation->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', "Dispensation {$dispensation->dispensation_number} verified successfully.");
    }
}
