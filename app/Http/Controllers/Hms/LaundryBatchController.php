<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LaundryBatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaundryBatchController extends Controller
{
    public function index(): View
    {
        $batches = LaundryBatch::orderByDesc('created_at')->paginate(20);
        return view('hms.laundry-batches.index', compact('batches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'collected_date' => 'required|date',
            'total_items' => 'required|integer|min:1',
            'ward_ids' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $data['batch_number'] = 'LB-' . now()->format('Ym') . str_pad(LaundryBatch::count() + 1, 4, '0', STR_PAD_LEFT);
        $data['status'] = 'collected';
        $data['processed_by'] = auth()->id();

        LaundryBatch::create($data);

        return back()->with('status', 'Laundry batch created successfully');
    }

    public function complete(LaundryBatch $batch): RedirectResponse
    {
        $batch->update([
            'status' => 'complete',
            'processed_date' => now()->toDateString(),
        ]);

        return back()->with('status', 'Laundry batch completed');
    }
}
