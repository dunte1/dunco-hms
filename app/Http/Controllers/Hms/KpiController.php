<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\KpiDefinition;
use App\Models\KpiSnapshot;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KpiController extends Controller
{
    public function index(): JsonResponse
    {
        $kpis = KpiDefinition::where('is_active', true)
            ->with('snapshots', function ($q) {
                $q->latest('snapshot_date')->limit(1);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kpis,
        ]);
    }

    public function record(Request $request, KpiDefinition $kpi): JsonResponse
    {
        $data = $request->validate([
            'actual_value' => 'required|numeric',
            'snapshot_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $status = 'insufficient_data';
        if ($kpi->target_value !== null) {
            $status = $data['actual_value'] >= $kpi->target_value ? 'met' : 'not_met';
        }

        $snapshot = KpiSnapshot::updateOrCreate(
            ['kpi_definition_id' => $kpi->id, 'snapshot_date' => $data['snapshot_date']],
            [
                'actual_value' => $data['actual_value'],
                'target_value' => $kpi->target_value,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
                'created_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $snapshot,
            'message' => 'KPI snapshot recorded successfully',
        ], 201);
    }

    public function trend(KpiDefinition $kpi, Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'sometimes|string|in:daily,weekly,monthly,yearly',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date',
        ]);

        $query = $kpi->snapshots()->orderBy('snapshot_date');

        if ($request->filled('date_from')) {
            $query->where('snapshot_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('snapshot_date', '<=', $request->date_to);
        }

        $snapshots = $query->get();

        return response()->json([
            'success' => true,
            'data' => [
                'kpi' => $kpi,
                'trend' => $snapshots,
            ],
        ]);
    }
}
