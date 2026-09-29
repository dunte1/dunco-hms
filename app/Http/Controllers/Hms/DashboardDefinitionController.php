<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DashboardDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardDefinitionController extends Controller
{
    public function index(): JsonResponse
    {
        $dashboards = DashboardDefinition::with('owner')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $dashboards,
        ]);
    }

    public function store(Request $request): JsonResponse
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

        $dashboard = DashboardDefinition::create($data);

        return response()->json([
            'success' => true,
            'data' => $dashboard,
            'message' => 'Dashboard definition created successfully',
        ], 201);
    }

    public function show(DashboardDefinition $dashboard): JsonResponse
    {
        $dashboard->load('owner');

        return response()->json([
            'success' => true,
            'data' => $dashboard,
        ]);
    }
}
