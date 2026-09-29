<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabWorklist;
use App\Models\LabRequestItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LabWorklistController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'department' => 'required|in:haematology,biochemistry,microbiology,immunology,serology,histopathology,parasitology,clinical_microscopy',
        ]);

        $worklist = LabWorklist::create([
            ...$data,
            'status' => 'active',
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('hms.laboratory.requests.index')
            ->with('status', "Worklist {$worklist->name} created successfully");
    }

    public function addItem(Request $request, LabWorklist $worklist): RedirectResponse
    {
        if ($worklist->status !== 'active') {
            return back()->withErrors(['status' => 'Can only add items to active worklists.']);
        }

        $data = $request->validate([
            'lab_request_item_ids' => 'required|array|min:1',
            'lab_request_item_ids.*' => 'exists:lab_request_items,id',
            'priorities' => 'nullable|array',
            'priorities.*' => 'in:routine,urgent',
        ]);

        $maxSort = $worklist->items()->max('sort_order') ?? 0;

        foreach ($data['lab_request_item_ids'] as $index => $itemId) {
            $item = LabRequestItem::with('labRequest')->findOrFail($itemId);
            $priority = $data['priorities'][$index] ?? 'routine';

            $exists = $worklist->items()
                ->where('lab_request_item_id', $itemId)
                ->exists();

            if (!$exists) {
                $worklist->items()->create([
                    'lab_request_id' => $item->lab_request_id,
                    'lab_request_item_id' => $itemId,
                    'priority' => $priority,
                    'sort_order' => ++$maxSort,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('hms.laboratory.requests.index')
            ->with('status', 'Items added to worklist successfully');
    }

    public function complete(LabWorklist $worklist): RedirectResponse
    {
        if ($worklist->status !== 'active') {
            return back()->withErrors(['status' => 'Worklist is already completed.']);
        }

        $worklist->update(['status' => 'completed']);

        return redirect()->route('hms.laboratory.requests.index')
            ->with('status', "Worklist {$worklist->name} completed");
    }
}
