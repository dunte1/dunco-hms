<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicHolidaysController extends Controller
{
    /**
     * Display a listing of public holidays.
     */
    public function index(Request $request): View
    {
        $query = PublicHoliday::query();
        
        if ($request->has('year')) {
            $query->whereYear('date', $request->year);
        } else {
            $query->whereYear('date', now()->year);
        }
        
        $publicHolidays = $query->orderBy('date')->paginate(15);
        
        return view('hms.hr.public-holidays.index', compact('publicHolidays'));
    }

    /**
     * Show the form for creating a new public holiday.
     */
    public function create(): View
    {
        return view('hms.hr.public-holidays.create');
    }

    /**
     * Store a newly created public holiday.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'is_active' => 'boolean',
        ]);

        PublicHoliday::create($validated);

        return redirect()->route('hms.hr.public-holidays.index')
            ->with('success', 'Public holiday created successfully.');
    }

    /**
     * Display the specified public holiday.
     */
    public function show(PublicHoliday $publicHoliday): View
    {
        return view('hms.hr.public-holidays.show', compact('publicHoliday'));
    }

    /**
     * Show the form for editing the specified public holiday.
     */
    public function edit(PublicHoliday $publicHoliday): View
    {
        return view('hms.hr.public-holidays.edit', compact('publicHoliday'));
    }

    /**
     * Update the specified public holiday.
     */
    public function update(Request $request, PublicHoliday $publicHoliday): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $publicHoliday->update($validated);

        return redirect()->route('hms.hr.public-holidays.index')
            ->with('success', 'Public holiday updated successfully.');
    }

    /**
     * Remove the specified public holiday.
     */
    public function destroy(PublicHoliday $publicHoliday): RedirectResponse
    {
        $publicHoliday->delete();

        return redirect()->route('hms.hr.public-holidays.index')
            ->with('success', 'Public holiday deleted successfully.');
    }
}
