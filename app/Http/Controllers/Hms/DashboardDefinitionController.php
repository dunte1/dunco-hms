<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DashboardDefinition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardDefinitionController extends Controller
{
    public function index(): View
    {
        $dashboards = DashboardDefinition::with('owner')->latest()->get();
        $stats = [
            'total' => DashboardDefinition::count(),
            'published' => DashboardDefinition::where('status', 'published')->count(),
            'drafts' => DashboardDefinition::where('status', 'draft')->count(),
        ];

        return view('hms.dashboard.definitions.index', compact('dashboards', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dashboard_definitions,slug',
            'description' => 'nullable|string',
            'layout' => 'nullable|array',
            'is_default' => 'boolean',
            'status' => 'required|string|in:draft,published',
        ]);

        $data['owner_id'] = auth()->id();

        DashboardDefinition::create($data);

        return redirect()->route('hms.dashboard.definitions.index')
            ->with('status', 'Dashboard definition created.');
    }

    public function show(DashboardDefinition $dashboard): View
    {
        $dashboard->load('owner');

        return view('hms.dashboard.definitions.show', compact('dashboard'));
    }
}
