<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RadiologyTest;
use App\Models\RadiologyCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadiologyTestsController extends Controller
{
    public function index(): View
    {
        $radiologyTests = RadiologyTest::with('category')->orderBy('test_name')->paginate(10);
        return view('hms.radiology.tests.index', compact('radiologyTests'));
    }

    public function create(): View
    {
        $categories = RadiologyCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.radiology.tests.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_name' => 'required|string',
            'category_id' => 'required|exists:radiology_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'preparation_instructions' => 'nullable|string',
        ]);

        RadiologyTest::create($data);
        return redirect()->route('hms.radiology.tests.index')->with('status', 'Radiology test added');
    }

    public function show(RadiologyTest $radiologyTest): View
    {
        $radiologyTest->load('category');
        return view('hms.radiology.tests.show', compact('radiologyTest'));
    }

    public function edit(RadiologyTest $radiologyTest): View
    {
        $categories = RadiologyCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.radiology.tests.edit', compact('radiologyTest', 'categories'));
    }

    public function update(Request $request, RadiologyTest $radiologyTest): RedirectResponse
    {
        $data = $request->validate([
            'test_name' => 'required|string',
            'category_id' => 'required|exists:radiology_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'preparation_instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $radiologyTest->update($data);
        return redirect()->route('hms.radiology.tests.index')->with('status', 'Radiology test updated successfully');
    }

    public function destroy(RadiologyTest $radiologyTest): RedirectResponse
    {
        $radiologyTest->delete();
        return redirect()->route('hms.radiology.tests.index')->with('status', 'Radiology test deleted successfully');
    }
}
