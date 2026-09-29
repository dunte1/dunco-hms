<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MortuaryRecord;
use App\Models\MortuarySlotAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MortuarySlotController extends Controller
{
    public function assign(Request $request, MortuaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'slot_number' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $data['mortuary_record_id'] = $record->id;
        $data['assigned_at'] = now();

        MortuarySlotAssignment::create($data);
        $record->update(['slot_number' => $data['slot_number']]);

        return back()->with('status', 'Slot assigned to body');
    }

    public function release(MortuarySlotAssignment $slot): RedirectResponse
    {
        $slot->update(['removed_at' => now()]);
        $slot->mortuaryRecord->update(['slot_number' => null]);

        return back()->with('status', 'Slot released');
    }
}
