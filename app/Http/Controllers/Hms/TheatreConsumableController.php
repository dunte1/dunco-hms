<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OtSchedule;
use App\Models\TheatreConsumable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TheatreConsumableController extends Controller
{
    public function store(Request $request, OtSchedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'medicine_id' => 'nullable|exists:medicines,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string',
        ]);

        $data['ot_schedule_id'] = $schedule->id;
        $data['added_by'] = auth()->id();
        $data['added_at'] = now();

        $consumable = TheatreConsumable::create($data);

        // Deduct pharmacy stock + write movement when medicine is linked
        if (!empty($data['medicine_id']) && !empty($data['quantity'])) {
            try {
                $medicine = \App\Models\Medicine::findOrFail($data['medicine_id']);
                $before = (int) $medicine->stock_quantity;
                $after = max(0, $before - (int) $data['quantity']);
                $medicine->update(['stock_quantity' => $after]);

                \App\Models\StockMovement::create([
                    'movement_number' => 'OTC-' . $consumable->id,
                    'medicine_id' => $medicine->id,
                    'user_id' => auth()->id(),
                    'batch_number' => $data['batch_number'] ?? null,
                    'movement_type' => 'theatre_consumable',
                    'direction' => 'out',
                    'quantity' => (int) $data['quantity'],
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'unit_cost' => $data['unit_cost'] ?? 0,
                    'total_cost' => (int) $data['quantity'] * (float) ($data['unit_cost'] ?? 0),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Theatre consumable stock deduct failed', [
                    'consumable_id' => $consumable->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('status', 'Consumable item added to theatre');
    }
}
