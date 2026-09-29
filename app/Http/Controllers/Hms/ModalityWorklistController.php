<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ModalityWorklistItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModalityWorklistController extends Controller
{
    public function index(Request $request): View
    {
        $query = ModalityWorklistItem::with(['patient', 'radiologyRequest']);

        if ($request->filled('modality')) {
            $query->where('modality', $request->input('modality'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $items = $query->orderBy('scheduled_time')->paginate(20);

        return view('hms.radiology.worklist', compact('items'));
    }

    public function claim(ModalityWorklistItem $item): RedirectResponse
    {
        if ($item->status !== 'queued') {
        return redirect()->route('hms.radiology.worklist.index')
            ->with('error', 'This item cannot be claimed.');
        }

        $item->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('hms.radiology.worklist.index')
            ->with('success', 'Worklist item claimed successfully.');
    }

    public function complete(ModalityWorklistItem $item): RedirectResponse
    {
        if ($item->status !== 'in_progress') {
            return redirect()->route('hms.radiology.worklist.index')
                ->with('error', 'This item is not in progress.');
        }

        $item->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('hms.radiology.worklist.index')
            ->with('success', 'Worklist item completed.');
    }
}
