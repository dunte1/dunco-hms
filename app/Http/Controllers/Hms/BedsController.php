<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\BedType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BedsController extends Controller
{
    public function index(): View
    {
        $beds = Bed::with('bedType')->orderBy('ward_name')->orderBy('bed_number')->paginate(10);
        return view('hms.beds.index', compact('beds'));
    }

    public function create(): View
    {
        $bedTypes = BedType::orderBy('name')->pluck('name', 'id');
        return view('hms.beds.create', compact('bedTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bed_number' => 'required|string|unique:beds,bed_number',
            'ward_name' => 'required|string',
            'bed_type_id' => 'required|exists:bed_types,id',
        ]);
        Bed::create($data);
        return redirect()->route('hms.beds.index')->with('status', 'Bed created');
    }
}