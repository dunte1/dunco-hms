<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ResearchProjectController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'objectives' => 'required|string',
            'methodology' => 'nullable|string',
            'findings' => 'nullable|string',
        ]);

        $validated['principal_investigator_id'] = $request->user()->id;
        $validated['status'] = 'proposal';

        ResearchProject::create($validated);

        return redirect()->route('hms.research.projects.index')
            ->with('success', 'Research project created successfully.');
    }

    public function index(Request $request)
    {
        $query = ResearchProject::with('principalInvestigator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(15);

        return view('hms.research.projects.index', compact('projects'));
    }

    public function update(Request $request, ResearchProject $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:proposal,ethics_review,approved,active,completed,withdrawn',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'objectives' => 'required|string',
            'methodology' => 'nullable|string',
            'findings' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('hms.research.projects.index')
            ->with('success', 'Research project updated successfully.');
    }
}
