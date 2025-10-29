<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\MarketingCampaign;
use App\Models\Marketing\MarketingPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $query = MarketingCampaign::with(['creator', 'manager']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $campaigns = $query->latest()->paginate(20);
        $managers = User::role('Marketing Manager')->orWhere('id', auth()->id())->get();

        return view('marketing.campaigns.index', compact('campaigns', 'managers'));
    }

    public function create(): View
    {
        $managers = User::role('Marketing Manager')->orWhere('id', auth()->id())->get();
        return view('marketing.campaigns.create', compact('managers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:health_awareness,blood_drive,event,promotional',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'target_audience' => 'nullable|array',
            'platforms' => 'nullable|array',
            'manager_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'planning';
        $validated['spent'] = 0;

        MarketingCampaign::create($validated);

        return redirect()->route('marketing.campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function show(MarketingCampaign $campaign): View
    {
        $campaign->load(['creator', 'manager', 'posts', 'graphicAssets', 'analytics']);
        return view('marketing.campaigns.show', compact('campaign'));
    }

    public function edit(MarketingCampaign $campaign): View
    {
        $managers = User::role('Marketing Manager')->orWhere('id', auth()->id())->get();
        return view('marketing.campaigns.edit', compact('campaign', 'managers'));
    }

    public function update(Request $request, MarketingCampaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:health_awareness,blood_drive,event,promotional',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'spent' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed,cancelled',
            'target_audience' => 'nullable|array',
            'platforms' => 'nullable|array',
            'manager_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $campaign->update($validated);

        return redirect()->route('marketing.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(MarketingCampaign $campaign): RedirectResponse
    {
        $campaign->delete();
        return redirect()->route('marketing.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
