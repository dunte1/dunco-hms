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
}