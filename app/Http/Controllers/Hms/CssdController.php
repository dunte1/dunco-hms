<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CssdInstrument;
use App\Models\CssdBatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CssdController extends Controller
{
    public function index(): View
    {
        $instruments = CssdInstrument::orderBy('name')->paginate(20);
        $batches = CssdBatch::orderByDesc('created_at')->paginate(10);
        return view('hms.cssd.index', compact('instruments', 'batches'));
    }

    public function storeInstrument(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);
        CssdInstrument::create($data);
        return back()->with('status', 'Instrument added successfully');
    }

    public function updateInstrument(Request $request, CssdInstrument $instrument): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:available,in_use,sterilizing,defective',
            'location' => 'nullable|string',
        ]);
        $instrument->update($data);
        return back()->with('status', 'Instrument updated');
    }

    public function storeBatch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'instrument_ids' => 'required|array',
            'sterilization_method' => 'required|in:autoclave,chemical,ethylene_oxide',
            'temperature' => 'nullable|numeric',
            'pressure' => 'nullable|numeric',
            'duration_minutes' => 'nullable|integer',
        ]);
        $data['batch_number'] = 'CSSD-' . now()->format('Ym') . str_pad(CssdBatch::count() + 1, 4, '0', STR_PAD_LEFT);
        $data['started_at'] = now();
        $data['performed_by'] = auth()->id();
        $data['status'] = 'processing';
        CssdBatch::create($data);
        return back()->with('status', 'Sterilization batch started');
    }

    public function completeBatch(CssdBatch $batch): RedirectResponse
    {
        $batch->update(['status' => 'sterilized', 'completed_at' => now(), 'expiry_date' => now()->addHours(24)]);
        return back()->with('status', 'Batch marked as sterilized');
    }
}
