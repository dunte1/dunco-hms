<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\InstrumentSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstrumentSetController extends Controller
{
    public function index(): View
    {
        $instrumentSets = InstrumentSet::orderByDesc('created_at')->paginate(20);
        return view('hms.cssd.instrument-sets', compact('instrumentSets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:instrument_sets,code',
            'description' => 'nullable|string',
            'instrument_count' => 'nullable|integer|min:0',
        ]);
        InstrumentSet::create($data);
        return back()->with('status', 'Instrument set created successfully');
    }
}
