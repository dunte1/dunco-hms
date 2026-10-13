<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\BiAnalytic;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class BiDashboardController extends Controller
{
    public function index(): View
    {
        $metrics = $this->getDashboardMetrics();
        $analytics = BiAnalytic::latest()->take(10)->get();

        return view('hms.analytics.bi-dashboard', compact('metrics', 'analytics'));
    }

    /**
     * Show a single analytics report (was blank — route was missing).
     */
    public function show(BiAnalytic $analytic): View
    {
        $dataPoints = is_array($analytic->data_points ?? null) ? $analytic->data_points : [];
        $predictions = is_array($analytic->predictions ?? null) ? $analytic->predictions : [];

        return view('hms.analytics.show', compact('analytic', 'dataPoints', 'predictions'));
    }

    /**
     * Download a single analytics report as branded PDF.
     */
    public function exportPdf(BiAnalytic $analytic)
    {
        $dataPoints = is_array($analytic->data_points ?? null) ? $analytic->data_points : [];
        $predictions = is_array($analytic->predictions ?? null) ? $analytic->predictions : [];
        $insights = is_string($analytic->insights ?? null) ? $analytic->insights : '';

        $pdf = Pdf::loadView('hms.analytics.analytic-pdf', compact('analytic', 'dataPoints', 'predictions', 'insights'))
            ->setPaper('a4');

        $name = preg_replace('/[^A-Za-z0-9\-_]/', '-', $analytic->metric_name ?: 'analytics');
        return $pdf->download("Analytics-{$analytic->id}-{$name}.pdf");
    }

    /**
     * Download full BI dashboard as branded PDF.
     */
    public function dashboardPdf()
    {
        $metrics = $this->getDashboardMetrics();
        $analytics = BiAnalytic::latest()->take(15)->get();

        $pdf = Pdf::loadView('hms.analytics.bi-dashboard-pdf', compact('metrics', 'analytics'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('BI-Dashboard-' . now()->format('Y-m-d') . '.pdf');
    }

    public function generateAnalytics(Request $request): JsonResponse
    {
        $data = $request->validate([
            'metric_type' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'granularity' => 'required|string|in:daily,weekly,monthly,yearly',
        ]);

        $analytics = $this->generateMetricAnalytics($data);

        return response()->json([
            'success' => true,
            'data' => $analytics,
            'message' => 'Analytics generated successfully'
        ]);
    }

    public function getRevenueAnalytics(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $revenueData = $this->calculateRevenueMetrics($dateFrom, $dateTo);
        $predictions = $this->generateRevenuePredictions($revenueData);
        $insights = 'Average daily collection $' . number_format((float)($revenueData['daily_average'] ?? 0), 2)
            . '. Growth ' . number_format((float)($revenueData['growth_rate'] ?? 0), 1) . '%.';

        return view('hms.analytics.revenue', compact('revenueData', 'predictions', 'insights'))
            ->with('revenue', $revenueData);
    }

    public function getPatientAnalytics(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $patientData = $this->calculatePatientMetrics($dateFrom, $dateTo);
        $predictions = $this->generatePatientPredictions($patientData);
        $insights = number_format((int)($patientData['total_patients'] ?? 0))
            . ' patients in range. New: ' . number_format((int)($patientData['new_patients'] ?? 0))
            . '. Returning: ' . number_format((int)($patientData['returning_patients'] ?? 0)) . '.';

        return view('hms.analytics.patients', compact('patientData', 'predictions', 'insights'))
            ->with('patients', $patientData);
    }

    public function getOccupancyAnalytics(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $occupancyData = $this->calculateOccupancyMetrics($dateFrom, $dateTo);
        $predictions = $this->generateOccupancyPredictions($occupancyData);
        $insights = 'Average occupancy ' . number_format((float)($occupancyData['average_occupancy'] ?? 0), 1)
            . '%. Peak ' . number_format((float)($occupancyData['peak_occupancy'] ?? 0), 1)
            . '%. Lowest ' . number_format((float)($occupancyData['lowest_occupancy'] ?? 0), 1) . '%.';

        return view('hms.analytics.occupancy', compact('occupancyData', 'predictions', 'insights'))
            ->with('occupancy', $occupancyData);
    }

    private function getDashboardMetrics(): array
    {
        return [
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'total_revenue' => Payment::sum('amount'),
            'bed_occupancy' => $this->calculateBedOccupancy(),
            'patient_satisfaction' => $this->calculatePatientSatisfaction(),
            'average_wait_time' => $this->calculateAverageWaitTime(),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'patient_growth' => $this->calculatePatientGrowth(),
        ];
    }

    private function generateMetricAnalytics(array $data): BiAnalytic
    {
        $dataPoints = $this->getDataPoints($data['metric_type'], $data['date_from'], $data['date_to'], $data['granularity']);
        $predictions = $this->generatePredictions($dataPoints);
        $insights = $this->generateInsights($dataPoints, $predictions);

        return BiAnalytic::create([
            'metric_name' => ucfirst($data['metric_type']) . ' Analytics',
            'metric_type' => $data['metric_type'],
            'data_points' => $dataPoints,
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'granularity' => $data['granularity'],
            'predictions' => $predictions,
            'insights' => $insights,
        ]);
    }

    private function getDataPoints(string $metricType, string $dateFrom, string $dateTo, string $granularity): array
    {
        switch ($metricType) {
            case 'revenue':
                return $this->getRevenueDataPoints($dateFrom, $dateTo, $granularity);
            case 'patient_count':
                return $this->getPatientDataPoints($dateFrom, $dateTo, $granularity);
            case 'occupancy':
                return $this->getOccupancyDataPoints($dateFrom, $dateTo, $granularity);
            default:
                return [];
        }
    }

    private function getRevenueDataPoints(string $dateFrom, string $dateTo, string $granularity): array
    {
        $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw($this->getDateSelect($granularity) . ' as period, SUM(amount) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $payments->map(function ($payment) {
            return [
                'date' => $payment->period,
                'value' => $payment->total,
                'type' => 'revenue'
            ];
        })->toArray();
    }

    private function getPatientDataPoints(string $dateFrom, string $dateTo, string $granularity): array
    {
        $patients = Patient::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw($this->getDateSelect($granularity) . ' as period, COUNT(*) as total')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $patients->map(function ($patient) {
            return [
                'date' => $patient->period,
                'value' => $patient->total,
                'type' => 'patient_count'
            ];
        })->toArray();
    }

    private function getOccupancyDataPoints(string $dateFrom, string $dateTo, string $granularity): array
    {
        $dataPoints = [];
        $currentDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        while ($currentDate->lte($endDate)) {
            $occupiedBeds = $this->getOccupiedBedsForDate($currentDate);
            $totalBeds = $this->getTotalBeds();
            $occupancyRate = $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;

            $dataPoints[] = [
                'date' => $currentDate->format($this->getDateFormat($granularity)),
                'value' => $occupancyRate,
                'type' => 'occupancy'
            ];

            $currentDate->add($this->getGranularityInterval($granularity));
        }

        return $dataPoints;
    }

    private function getDateSelect(string $granularity): string
    {
        switch ($granularity) {
            case 'daily':
                return 'DATE(created_at)';
            case 'weekly':
                return 'YEARWEEK(created_at)';
            case 'monthly':
                return 'DATE_FORMAT(created_at, "%Y-%m")';
            case 'yearly':
                return 'YEAR(created_at)';
            default:
                return 'DATE(created_at)';
        }
    }

    private function getDateFormat(string $granularity): string
    {
        switch ($granularity) {
            case 'daily':
                return 'Y-m-d';
            case 'weekly':
                return 'Y-W';
            case 'monthly':
                return 'Y-m';
            case 'yearly':
                return 'Y';
            default:
                return 'Y-m-d';
        }
    }

    private function getGranularityInterval(string $granularity): string
    {
        switch ($granularity) {
            case 'daily':
                return '1 day';
            case 'weekly':
                return '1 week';
            case 'monthly':
                return '1 month';
            case 'yearly':
                return '1 year';
            default:
                return '1 day';
        }
    }

    private function generatePredictions(array $dataPoints): array
    {
        if (count($dataPoints) < 2) {
            return [];
        }

        $lastValue = end($dataPoints)['value'];
        $secondLastValue = $dataPoints[count($dataPoints) - 2]['value'];
        $trend = $lastValue - $secondLastValue;

        $predictions = [];
        for ($i = 1; $i <= 7; $i++) {
            $predictions[] = [
                'period' => $i,
                'predicted_value' => $lastValue + ($trend * $i),
                'confidence' => max(0, 100 - ($i * 10))
            ];
        }

        return $predictions;
    }

    private function generateInsights(array $dataPoints, array $predictions): string
    {
        if (empty($dataPoints)) {
            return 'No data available for analysis.';
        }

        $totalValue = array_sum(array_column($dataPoints, 'value'));
        $averageValue = $totalValue / count($dataPoints);
        $maxValue = max(array_column($dataPoints, 'value'));
        $minValue = min(array_column($dataPoints, 'value'));

        $insights = [];
        $insights[] = "Average value: " . number_format($averageValue, 2);
        $insights[] = "Peak value: " . number_format($maxValue, 2);
        $insights[] = "Lowest value: " . number_format($minValue, 2);

        if (!empty($predictions)) {
            $trend = $predictions[0]['predicted_value'] > $dataPoints[count($dataPoints) - 1]['value'] ? 'increasing' : 'decreasing';
            $insights[] = "Trend: " . $trend;
        }

        return implode('. ', $insights) . '.';
    }

    private function calculateBedOccupancy(): float
    {
        $totalBeds = $this->getTotalBeds();
        $occupiedBeds = $this->getOccupiedBedsForDate(now());
        
        return $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;
    }

    private function calculatePatientSatisfaction(): float
    {
        $totalAppointments = Appointment::count();
        if ($totalAppointments === 0) {
            return 0;
        }

        $completedAppointments = Appointment::where('status', 'completed')->count();
        return round(($completedAppointments / $totalAppointments) * 100, 1);
    }

    private function calculateAverageWaitTime(): int
    {
        $appointments = Appointment::whereNotNull('scheduled_at')
            ->where('status', 'completed')
            ->whereDate('scheduled_at', '>=', now()->subDays(30))
            ->get();

        if ($appointments->isEmpty()) {
            return 0;
        }

        $totalWaitMinutes = 0;
        $count = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->created_at && $appointment->scheduled_at) {
                $waitMinutes = $appointment->created_at->diffInMinutes($appointment->scheduled_at);
                $totalWaitMinutes += $waitMinutes;
                $count++;
            }
        }

        return $count > 0 ? (int) round($totalWaitMinutes / $count) : 0;
    }

    private function calculateRevenueGrowth(): float
    {
        $currentMonth = Payment::whereMonth('created_at', now()->month)->sum('amount');
        $lastMonth = Payment::whereMonth('created_at', now()->subMonth()->month)->sum('amount');
        
        return $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;
    }

    private function calculatePatientGrowth(): float
    {
        $currentMonth = Patient::whereMonth('created_at', now()->month)->count();
        $lastMonth = Patient::whereMonth('created_at', now()->subMonth()->month)->count();
        
        return $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;
    }

    private function getTotalBeds(): int
    {
        return \App\Models\Bed::count();
    }

    private function getOccupiedBedsForDate($date): int
    {
        return \App\Models\BedAssignment::whereDate('assigned_at', $date)
            ->whereNull('discharged_at')
            ->count();
    }

    private function calculateRevenueMetrics($dateFrom, $dateTo): array
    {
        return [
            'total_revenue' => Payment::whereBetween('created_at', [$dateFrom, $dateTo])->sum('amount'),
            'daily_average' => Payment::whereBetween('created_at', [$dateFrom, $dateTo])->avg('amount'),
            'growth_rate' => $this->calculateRevenueGrowth(),
        ];
    }

    private function calculatePatientMetrics($dateFrom, $dateTo): array
    {
        return [
            'total_patients' => Patient::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'new_patients' => Patient::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'returning_patients' => Patient::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];
    }

    private function calculateOccupancyMetrics($dateFrom, $dateTo): array
    {
        $occupancies = [];
        $currentDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        while ($currentDate->lte($endDate)) {
            $occupiedBeds = $this->getOccupiedBedsForDate($currentDate);
            $totalBeds = $this->getTotalBeds();
            $occupancyRate = $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;
            $occupancies[] = $occupancyRate;
            $currentDate->addDay();
        }

        return [
            'average_occupancy' => $this->calculateBedOccupancy(),
            'peak_occupancy' => !empty($occupancies) ? max($occupancies) : 0,
            'lowest_occupancy' => !empty($occupancies) ? min($occupancies) : 0,
        ];
    }

    private function generateRevenuePredictions(array $revenueData): array
    {
        return [
            'next_month' => $revenueData['total_revenue'] * 1.05,
            'next_quarter' => $revenueData['total_revenue'] * 3.15,
            'confidence' => 85
        ];
    }

    private function generatePatientPredictions(array $patientData): array
    {
        return [
            'next_month' => $patientData['total_patients'] * 1.08,
            'next_quarter' => $patientData['total_patients'] * 3.24,
            'confidence' => 78
        ];
    }

    private function generateOccupancyPredictions(array $occupancyData): array
    {
        return [
            'next_month' => $occupancyData['average_occupancy'] * 1.02,
            'next_quarter' => $occupancyData['average_occupancy'] * 1.06,
            'confidence' => 92
        ];
    }
}
