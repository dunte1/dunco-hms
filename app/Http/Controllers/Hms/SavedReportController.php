<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SavedReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SavedReportController extends Controller
{
    public function index(): JsonResponse
    {
        $reports = SavedReport::with('owner')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|string|in:patient,billing,clinical,operational,financial,custom',
            'parameters' => 'required|array',
            'is_public' => 'boolean',
        ]);

        $data['owner_id'] = auth()->id();

        $report = SavedReport::create($data);

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Saved report created successfully',
        ], 201);
    }

    public function run(SavedReport $report): JsonResponse
    {
        $report->update(['last_run_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => [
                'report' => $report,
                'results' => $this->executeReport($report),
            ],
            'message' => 'Report executed successfully',
        ]);
    }

    private function executeReport(SavedReport $report): array
    {
        $params = $report->parameters;

        return [
            'type' => $report->report_type,
            'generated_at' => now()->toISOString(),
            'parameters' => $params,
            'row_count' => 0,
        ];
    }
}
