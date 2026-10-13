<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\KpiDefinition;
use App\Models\KpiSnapshot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KpiController extends Controller
{
    public function index(): View
    {
        $kpis = KpiDefinition::where('is_active', true)
            ->with('snapshots', function ($q) {
                $q->latest('snapshot_date')->limit(1);
            })
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => KpiDefinition::count(),
            'active' => KpiDefinition::where('is_active', true)->count(),
            'snapshots' => KpiSnapshot::count(),
        ];

        return view('hms.dashboard.kpis.index', compact('kpis', 'stats'));
    }

    public function record(Request $request, KpiDefinition $kpi)
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

        KpiSnapshot::updateOrCreate(
            ['kpi_definition_id' => $kpi->id, 'snapshot_date' => $data['snapshot_date']],
            [
                'actual_value' => $data['actual_value'],
                'target_value' => $kpi->target_value,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
                'created_at' => now(),
            ]
        );

        return back()->with('status', 'KPI snapshot recorded.');
    }

    public function trend(KpiDefinition $kpi, Request $request): View
    {
        $query = $kpi->snapshots()->orderBy('snapshot_date');
        if ($request->filled('date_from')) {
            $query->where('snapshot_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('snapshot_date', '<=', $request->date_to);
        }

        return view('hms.dashboard.kpis.trend', [
            'kpi' => $kpi,
            'snapshots' => $query->get(),
        ]);
    }
}
