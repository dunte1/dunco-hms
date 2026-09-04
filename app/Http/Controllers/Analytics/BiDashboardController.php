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

class BiDashboardController extends Controller
{
    public function index(): View
    {
        $metrics = $this->getDashboardMetrics();
        $analytics = BiAnalytic::latest()->take(10)->get();
        
        return view('hms.analytics.bi-dashboard', compact('metrics', 'analytics'));
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

    public function getRevenueAnalytics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $revenueData = $this->calculateRevenueMetrics($dateFrom, $dateTo);
        $predictions = $this->generateRevenuePredictions($revenueData);

        return response()->json([
            'success' => true,
            'data' => [
                'revenue' => $revenueData,
                'predictions' => $predictions
            ]
        ]);
    }

    public function getPatientAnalytics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $patientData = $this->calculatePatientMetrics($dateFrom, $dateTo);
        $predictions = $this->generatePatientPredictions($patientData);

        return response()->json([
            'success' => true,
            'data' => [
                'patients' => $patientData,
                'predictions' => $predictions
            ]
        ]);
    }

    public function getOccupancyAnalytics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subMonth());
        $dateTo = $request->get('date_to', now());

        $occupancyData = $this->calculateOccupancyMetrics($dateFrom, $dateTo);
        $predictions = $this->generateOccupancyPredictions($occupancyData);

        return response()->json([
            'success' => true,
            'data' => [
                'occupancy' => $occupancyData,
                'predictions' => $predictions
            ]
        ]);
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
