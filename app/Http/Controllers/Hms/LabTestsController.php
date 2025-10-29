<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\LabCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabTestsController extends Controller
{
    public function index(): View
    {
        $labTests = LabTest::with('category')->orderBy('test_name')->paginate(10);
        return view('hms.laboratory.tests.index', compact('labTests'));
    }

    public function create(): View
    {
        $categories = LabCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.laboratory.tests.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_name' => 'required|string',
            'category_id' => 'required|exists:lab_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_range' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        LabTest::create($data);
        return redirect()->route('hms.laboratory.tests.index')->with('status', 'Lab test added');
    }

    public function show(LabTest $labTest): View
    {
        $labTest->load('category');
        return view('hms.laboratory.tests.show', compact('labTest'));
    }

    public function edit(LabTest $labTest): View
    {
        $categories = LabCategory::orderBy('name')->pluck('name', 'id');
        return view('hms.laboratory.tests.edit', compact('labTest', 'categories'));
    }

    public function update(Request $request, LabTest $labTest): RedirectResponse
    {
        $data = $request->validate([
            'test_name' => 'required|string',
            'category_id' => 'required|exists:lab_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_range' => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $labTest->update($data);
        return redirect()->route('hms.laboratory.tests.index')->with('status', 'Lab test updated successfully');
    }

    public function destroy(LabTest $labTest): RedirectResponse
    {
        $labTest->delete();
        return redirect()->route('hms.laboratory.tests.index')->with('status', 'Lab test deleted successfully');
    }
}
