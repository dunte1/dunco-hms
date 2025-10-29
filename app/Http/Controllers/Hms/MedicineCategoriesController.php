<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicineCategoriesController extends Controller
{
    public function index(): View
    {
        $categories = MedicineCategory::withCount('medicines')->get();
        
        $stats = [
            'total_categories' => $categories->count(),
            'total_medicines' => \App\Models\Medicine::count(),
        ];
        
        return view('hms.pharmacy.medicine-categories.index', compact('categories', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MedicineCategory::create($data);

        return back()->with('success', 'Medicine category added successfully!');
    }
}
