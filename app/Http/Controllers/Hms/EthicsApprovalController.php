<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EthicsApproval;
use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EthicsApprovalController extends Controller
{
    public function store(Request $request, ResearchProject $project): RedirectResponse
    {
        $validated = $request->validate([
            'submission_date' => 'required|date',
            'committee_name' => 'required|string',
            'approval_number' => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:approval_date',
            'decision_notes' => 'nullable|string',
        ]);

        $validated['research_project_id'] = $project->id;
        $validated['status'] = 'pending';

        EthicsApproval::create($validated);

        if ($project->status === 'proposal') {
            $project->update(['status' => 'ethics_review']);
        }

        return redirect()->route('hms.research.projects.index')
            ->with('success', 'Ethics approval submitted successfully.');
    }

    public function update(Request $request, EthicsApproval $approval): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,expired,withdrawn',
            'approval_number' => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'decision_notes' => 'nullable|string',
        ]);

        $approval->update($validated);

        if ($validated['status'] === 'approved') {
            $project = $approval->researchProject;
            if ($project->status === 'ethics_review') {
                $project->update(['status' => 'approved']);
            }
        }

        return redirect()->route('hms.research.projects.index')
            ->with('success', 'Ethics approval updated successfully.');
    }
}
