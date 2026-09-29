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

        TheatreConsumable::create($data);

        return back()->with('status', 'Consumable item added to theatre');
    }
}
