<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MedicineBrand;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicineBrandsController extends Controller
{
    public function index(): View
    {
        $brands = MedicineBrand::withCount('medicines')->get();
        
        $stats = [
            'total_brands' => $brands->count(),
            'total_medicines' => \App\Models\Medicine::count(),
        ];
        
        return view('hms.pharmacy.medicine-brands.index', compact('brands', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        MedicineBrand::create($data);

        return back()->with('success', 'Medicine brand added successfully!');
    }
}
