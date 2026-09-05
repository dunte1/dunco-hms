<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BedType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BedTypesController extends Controller
{
    public function index(): View
    {
        $bedTypes = BedType::orderBy('name')->paginate(10);
        return view('hms.beds.types.index', compact('bedTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:bed_types,name',
            'charge_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);
        BedType::create($data);
        return back()->with('status', 'Bed type created');
    }

    public function show(BedType $bedType): View
    {
        return view('hms.beds.types.show', compact('bedType'));
    }

    public function edit(BedType $bedType): View
    {
        return view('hms.beds.types.edit', compact('bedType'));
    }

    public function update(Request $request, BedType $bedType): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:bed_types,name,' . $bedType->id,
            'charge_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);
        $bedType->update($data);
        return back()->with('status', 'Bed type updated');
    }

    public function destroy(BedType $bedType): RedirectResponse
    {
        $bedType->delete();
        return back()->with('status', 'Bed type deleted');
    }
}