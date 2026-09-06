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

    public function create(): View
    {
        return view('hms.pharmacy.medicine-categories.create');
    }

    public function show(MedicineCategory $category): View
    {
        $category->loadCount('medicines');
        return view('hms.pharmacy.medicine-categories.show', compact('category'));
    }

    public function edit(MedicineCategory $category): View
    {
        return view('hms.pharmacy.medicine-categories.edit', compact('category'));
    }

    public function update(Request $request, MedicineCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:medicine_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('hms.pharmacy.medicine-categories.index')->with('status', 'Category updated');
    }

    public function destroy(MedicineCategory $category): RedirectResponse
    {
        if ($category->medicines()->count() > 0) {
            return back()->with('error', 'Cannot delete category with medicines');
        }
        $category->delete();
        return redirect()->route('hms.pharmacy.medicine-categories.index')->with('status', 'Category deleted');
    }
}
