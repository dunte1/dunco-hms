<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ICD10Code;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ICD10Controller extends Controller
{
    public function index(): View
    {
        $query = ICD10Code::query();

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if (request('category')) {
            $query->where('category', request('category'));
        }

        $codes = $query->latest('code')->paginate(25)->withQueryString();
        $categories = ICD10Code::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('hms.icd10.index', compact('codes', 'categories'));
    }

    public function create(): View
    {
        return view('hms.icd10.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|unique:icd10_codes,code',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'is_chapter_heading' => 'nullable|boolean',
        ]);

        ICD10Code::create([
            'code' => strtoupper($data['code']),
            'description' => $data['description'],
            'category' => $data['category'] ?? null,
            'is_chapter_heading' => $request->boolean('is_chapter_heading'),
        ]);

        return redirect()->route('hms.icd10.index')->with('success', 'ICD-10 code added successfully.');
    }

    public function edit(ICD10Code $code): View
    {
        return view('hms.icd10.edit', compact('code'));
    }

    public function update(Request $request, ICD10Code $code): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|unique:icd10_codes,code,' . $code->id,
            'description' => 'required|string',
            'category' => 'nullable|string',
        ]);

        $code->update([
            'code' => strtoupper($data['code']),
            'description' => $data['description'],
            'category' => $data['category'] ?? null,
        ]);

        return redirect()->route('hms.icd10.index')->with('success', 'ICD-10 code updated successfully.');
    }

    public function destroy(ICD10Code $code): RedirectResponse
    {
        $code->delete();
        return redirect()->route('hms.icd10.index')->with('success', 'ICD-10 code deleted.');
    }
}
