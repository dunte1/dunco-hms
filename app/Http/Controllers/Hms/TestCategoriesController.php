<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabCategory;
use App\Models\RadiologyCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TestCategoriesController extends Controller
{
    public function index(): View
    {
        $labCategories = LabCategory::withCount('labTests')->get();
        $radiologyCategories = RadiologyCategory::withCount('radiologyTests')->get();

        return view('hms.test-categories.index', compact('labCategories', 'radiologyCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_type' => 'required|in:lab,radiology',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($data['category_type'] === 'lab') {
            LabCategory::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        } else {
            RadiologyCategory::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        }

        return back()->with('success', 'Test category added successfully!');
    }

    public function investigationReports(Request $request): View
    {
        $labRequests = \App\Models\LabRequest::with(['patient', 'doctor'])
            ->latest()
            ->limit(10)
            ->get();

        $radiologyRequests = \App\Models\RadiologyRequest::with(['patient', 'doctor', 'radiologyTest'])
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'total_lab_requests' => \App\Models\LabRequest::count(),
            'total_radiology_requests' => \App\Models\RadiologyRequest::count(),
            'completed_lab' => \App\Models\LabRequest::where('status', 'completed')->count(),
            'completed_radiology' => \App\Models\RadiologyRequest::where('status', 'completed')->count(),
        ];

        return view('hms.investigation-reports.index', compact('labRequests', 'radiologyRequests', 'stats'));
    }

    public function create(): View
    {
        return view('hms.test-categories.create');
    }

    public function show($id): View
    {
        $category = LabCategory::findOrFail($id);
        return view('hms.test-categories.show', compact('category'));
    }

    public function edit($id): View
    {
        $category = LabCategory::findOrFail($id);
        return view('hms.test-categories.edit', compact('category'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $category = LabCategory::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|unique:lab_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('hms.test-categories.index')->with('status', 'Category updated');
    }

    public function destroy($id): RedirectResponse
    {
        LabCategory::findOrFail($id)->delete();
        return redirect()->route('hms.test-categories.index')->with('status', 'Category deleted');
    }
}
