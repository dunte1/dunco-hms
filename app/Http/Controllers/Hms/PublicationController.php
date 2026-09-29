<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PublicationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'research_project_id' => 'nullable|exists:research_projects,id',
            'title' => 'required|string',
            'authors' => 'required|string',
            'journal_name' => 'required|string|max:255',
            'publication_date' => 'nullable|date',
            'doi' => 'nullable|string|max:200',
            'pubmed_id' => 'nullable|string|max:100',
            'publication_type' => 'required|in:journal_article,conference_poster,conference_paper,other',
        ]);

        $validated['status'] = 'submitted';

        Publication::create($validated);

        return redirect()->route('hms.research.publications.index')
            ->with('success', 'Publication recorded successfully.');
    }

    public function index(Request $request)
    {
        $query = Publication::with('researchProject');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $publications = $query->latest()->paginate(15);

        return view('hms.research.publications.index', compact('publications'));
    }
}
