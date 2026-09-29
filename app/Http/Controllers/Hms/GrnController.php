<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceivedNote;
use App\Models\GoodsReceivedItem;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'store_id' => 'required|exists:stores,id',
            'received_at' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.batch_number' => 'required|string',
            'items.*.quantity_received' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.expiry_date' => 'required|date|after:today',
            'items.*.manufactured_date' => 'nullable|date',
        ]);

        $grn = DB::transaction(function () use ($validated) {
            $totalAmount = collect($validated['items'])->sum(fn ($item) => $item['quantity_received'] * $item['unit_cost']);

            $grn = GoodsReceivedNote::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'purchase_order_id' => $validated['purchase_order_id'] ?? null,
                'store_id' => $validated['store_id'],
                'received_by' => auth()->id(),
                'received_at' => $validated['received_at'],
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                GoodsReceivedItem::create([
                    'grn_id' => $grn->id,
                    'medicine_id' => $item['medicine_id'],
                    'batch_number' => $item['batch_number'],
                    'quantity_received' => $item['quantity_received'],
                    'unit_cost' => $item['unit_cost'],
                    'expiry_date' => $item['expiry_date'],
                    'manufactured_date' => $item['manufactured_date'] ?? null,
                ]);

                $medicine = Medicine::findOrFail($item['medicine_id']);
                $medicine->increment('stock_quantity', $item['quantity_received']);

                $existingBatch = MedicineBatch::where('medicine_id', $item['medicine_id'])
                    ->where('store_id', $validated['store_id'])
                    ->where('batch_number', $item['batch_number'])
                    ->first();

                if ($existingBatch) {
                    $existingBatch->update([
                        'quantity' => $existingBatch->quantity + $item['quantity_received'],
                        'unit_cost' => $item['unit_cost'],
                        'expiry_date' => $item['expiry_date'],
                        'manufacturing_date' => $item['manufactured_date'] ?? null,
                        'status' => 'active',
                    ]);
                } else {
                    MedicineBatch::create([
                        'medicine_id' => $item['medicine_id'],
                        'store_id' => $validated['store_id'],
                        'batch_number' => $item['batch_number'],
                        'quantity' => $item['quantity_received'],
                        'unit_cost' => $item['unit_cost'],
                        'expiry_date' => $item['expiry_date'],
                        'manufacturing_date' => $item['manufactured_date'] ?? null,
                        'status' => 'active',
                    ]);
                }
            }

            return $grn;
        });

        return back()->with('success', "GRN {$grn->grn_number} created successfully.");
    }

    public function verify(Request $request, GoodsReceivedNote $grn): RedirectResponse
    {
        if ($grn->status !== 'draft') {
            return back()->with('error', 'Only draft GRNs can be verified.');
        }

        $grn->verify(auth()->user());

        return back()->with('success', "GRN {$grn->grn_number} verified successfully.");
    }
}
