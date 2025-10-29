<?php

namespace App\Http\Controllers\Iot;

use App\Http\Controllers\Controller;
use App\Models\IotBedSensor;
use App\Models\Bed;
use App\Models\BedAssignment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class IotBedMonitoringController extends Controller
{
    public function index(): View
    {
        $sensors = IotBedSensor::with('bed')
            ->latest()
            ->paginate(20);
        
        $bedStatus = $this->getBedStatusOverview();
        
        return view('hms.iot.bed-monitoring', compact('sensors', 'bedStatus'));
    }

    public function create(): View
    {
        $beds = Bed::with('bedType')->get();
        
        return view('hms.iot.create-sensor', compact('beds'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'sensor_id' => 'required|string|unique:iot_bed_sensors',
            'sensor_type' => 'required|string|in:pressure,temperature,movement,heart_rate',
            'configuration' => 'nullable|array',
        ]);

        $sensor = IotBedSensor::create([
            'bed_id' => $data['bed_id'],
            'sensor_id' => $data['sensor_id'],
            'sensor_type' => $data['sensor_type'],
            'sensor_data' => [],
            'is_occupied' => false,
            'vital_signs' => [],
            'alert_level' => 'normal',
            'alerts' => null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $sensor,
            'message' => 'IoT bed sensor created successfully'
        ], 201);
    }

    public function receiveSensorData(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sensor_id' => 'required|string',
            'sensor_data' => 'required|array',
            'timestamp' => 'nullable|date',
        ]);

        $sensor = IotBedSensor::where('sensor_id', $data['sensor_id'])->first();

        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor not found'
            ], 404);
        }

        // Process sensor data
        $processedData = $this->processSensorData($data['sensor_data'], $sensor);
        $vitalSigns = $this->extractVitalSigns($processedData, $sensor);
        $alertLevel = $this->determineAlertLevel($vitalSigns, $sensor);
        $alerts = $this->generateAlerts($vitalSigns, $alertLevel, $sensor);

        $sensor->update([
            'sensor_data' => $processedData,
            'vital_signs' => $vitalSigns,
            'alert_level' => $alertLevel,
            'alerts' => $alerts,
            'is_occupied' => $this->detectOccupancy($processedData, $sensor),
        ]);

        // Check for critical alerts
        if ($alertLevel === 'critical') {
            $this->handleCriticalAlert($sensor, $alerts);
        }

        return response()->json([
            'success' => true,
            'data' => $sensor,
            'message' => 'Sensor data processed successfully'
        ]);
    }

    public function getSensorData(IotBedSensor $sensor): JsonResponse
    {
        $sensor->load('bed.bedType');

        return response()->json([
            'success' => true,
            'data' => $sensor
        ]);
    }

    public function getBedStatus(Bed $bed): JsonResponse
    {
        $sensor = IotBedSensor::where('bed_id', $bed->id)->first();
        $assignment = BedAssignment::where('bed_id', $bed->id)
            ->whereNull('discharged_at')
            ->with('patient')
            ->first();

        $status = [
            'bed' => $bed,
            'sensor' => $sensor,
            'assignment' => $assignment,
            'is_occupied' => $sensor ? $sensor->is_occupied : false,
            'alert_level' => $sensor ? $sensor->alert_level : 'normal',
            'last_update' => $sensor ? $sensor->updated_at : null,
        ];

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    public function getOccupancyMap(Request $request)
    {
        $beds = Bed::with(['bedType', 'sensor', 'bedAssignments' => function($q) {
            $q->whereNull('discharged_at')->with('patient');
        }])->get();

        // Group beds by ward and room
        $bedsByWard = $beds->groupBy('ward');

        $stats = [
            'total_beds' => $beds->count(),
            'occupied' => $beds->filter(fn($b) => $b->sensor && $b->sensor->is_occupied)->count(),
            'critical_alerts' => $beds->filter(fn($b) => $b->sensor && $b->sensor->alert_level === 'critical')->count(),
            'warning_alerts' => $beds->filter(fn($b) => $b->sensor && $b->sensor->alert_level === 'warning')->count(),
        ];

        // If AJAX request, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            $bedsData = $beds->map(function ($bed) {
                $sensor = $bed->sensor;
                return [
                    'bed_id' => $bed->id,
                    'bed_number' => $bed->bed_number,
                    'ward' => $bed->ward,
                    'room' => $bed->room,
                    'bed_type' => $bed->bedType->name ?? 'Unknown',
                    'is_occupied' => $sensor ? $sensor->is_occupied : false,
                    'alert_level' => $sensor ? $sensor->alert_level : 'normal',
                    'last_update' => $sensor ? $sensor->updated_at : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $bedsData
            ]);
        }

        // Return view for browser requests
        return view('hms.iot.bed-occupancy-map', compact('beds', 'bedsByWard', 'stats'));
    }

    public function getAlerts(Request $request): JsonResponse
    {
        $alertLevel = $request->get('level', 'all');
        
        $query = IotBedSensor::with('bed')
            ->whereNotNull('alerts');

        if ($alertLevel !== 'all') {
            $query->where('alert_level', $alertLevel);
        }

        $sensors = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $sensors
        ]);
    }

    public function acknowledgeAlert(IotBedSensor $sensor): JsonResponse
    {
        $sensor->update([
            'alert_level' => 'normal',
            'alerts' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert acknowledged successfully'
        ]);
    }

    public function getVitalSignsHistory(IotBedSensor $sensor, Request $request): JsonResponse
    {
        $hours = $request->get('hours', 24);
        
        // In a real implementation, you would have a separate table for historical data
        // For now, we'll return the current vital signs
        $history = [
            [
                'timestamp' => $sensor->updated_at,
                'vital_signs' => $sensor->vital_signs,
                'alert_level' => $sensor->alert_level,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    private function processSensorData(array $rawData, IotBedSensor $sensor): array
    {
        // Process raw sensor data based on sensor type
        switch ($sensor->sensor_type) {
            case 'pressure':
                return $this->processPressureData($rawData);
            case 'temperature':
                return $this->processTemperatureData($rawData);
            case 'movement':
                return $this->processMovementData($rawData);
            case 'heart_rate':
                return $this->processHeartRateData($rawData);
            default:
                return $rawData;
        }
    }

    private function processPressureData(array $rawData): array
    {
        return [
            'pressure_points' => $rawData['pressure_points'] ?? [],
            'total_pressure' => $rawData['total_pressure'] ?? 0,
            'pressure_distribution' => $rawData['pressure_distribution'] ?? [],
            'processed_at' => now()->toISOString(),
        ];
    }

    private function processTemperatureData(array $rawData): array
    {
        return [
            'temperature' => $rawData['temperature'] ?? 0,
            'humidity' => $rawData['humidity'] ?? 0,
            'processed_at' => now()->toISOString(),
        ];
    }

    private function processMovementData(array $rawData): array
    {
        return [
            'movement_detected' => $rawData['movement_detected'] ?? false,
            'movement_intensity' => $rawData['movement_intensity'] ?? 0,
            'position_changes' => $rawData['position_changes'] ?? 0,
            'processed_at' => now()->toISOString(),
        ];
    }

    private function processHeartRateData(array $rawData): array
    {
        return [
            'heart_rate' => $rawData['heart_rate'] ?? 0,
            'heart_rate_variability' => $rawData['hrv'] ?? 0,
            'processed_at' => now()->toISOString(),
        ];
    }

    private function extractVitalSigns(array $processedData, IotBedSensor $sensor): array
    {
        $vitalSigns = [];

        switch ($sensor->sensor_type) {
            case 'pressure':
                $vitalSigns['pressure_score'] = $this->calculatePressureScore($processedData);
                break;
            case 'temperature':
                $vitalSigns['temperature'] = $processedData['temperature'];
                $vitalSigns['humidity'] = $processedData['humidity'];
                break;
            case 'movement':
                $vitalSigns['movement_score'] = $this->calculateMovementScore($processedData);
                break;
            case 'heart_rate':
                $vitalSigns['heart_rate'] = $processedData['heart_rate'];
                $vitalSigns['hrv'] = $processedData['heart_rate_variability'];
                break;
        }

        return $vitalSigns;
    }

    private function calculatePressureScore(array $data): float
    {
        $totalPressure = $data['total_pressure'] ?? 0;
        $maxPressure = 1000; // Maximum expected pressure
        
        return min(100, ($totalPressure / $maxPressure) * 100);
    }

    private function calculateMovementScore(array $data): float
    {
        $intensity = $data['movement_intensity'] ?? 0;
        $changes = $data['position_changes'] ?? 0;
        
        return min(100, ($intensity + $changes) * 10);
    }

    private function determineAlertLevel(array $vitalSigns, IotBedSensor $sensor): string
    {
        // Determine alert level based on vital signs
        foreach ($vitalSigns as $key => $value) {
            if ($this->isCriticalValue($key, $value)) {
                return 'critical';
            }
            if ($this->isWarningValue($key, $value)) {
                return 'warning';
            }
        }

        return 'normal';
    }

    private function isCriticalValue(string $key, $value): bool
    {
        $criticalThresholds = [
            'temperature' => 40, // 40°C
            'heart_rate' => 120, // 120 BPM
            'pressure_score' => 95, // 95% pressure
        ];

        return isset($criticalThresholds[$key]) && $value > $criticalThresholds[$key];
    }

    private function isWarningValue(string $key, $value): bool
    {
        $warningThresholds = [
            'temperature' => 38, // 38°C
            'heart_rate' => 100, // 100 BPM
            'pressure_score' => 85, // 85% pressure
        ];

        return isset($warningThresholds[$key]) && $value > $warningThresholds[$key];
    }

    private function generateAlerts(array $vitalSigns, string $alertLevel, IotBedSensor $sensor): ?string
    {
        if ($alertLevel === 'normal') {
            return null;
        }

        $alerts = [];
        
        foreach ($vitalSigns as $key => $value) {
            if ($this->isCriticalValue($key, $value)) {
                $alerts[] = "Critical: {$key} is {$value}";
            } elseif ($this->isWarningValue($key, $value)) {
                $alerts[] = "Warning: {$key} is {$value}";
            }
        }

        return implode('; ', $alerts);
    }

    private function detectOccupancy(array $processedData, IotBedSensor $sensor): bool
    {
        // Detect occupancy based on sensor type
        switch ($sensor->sensor_type) {
            case 'pressure':
                return ($processedData['total_pressure'] ?? 0) > 50;
            case 'movement':
                return ($processedData['movement_detected'] ?? false);
            case 'heart_rate':
                return ($processedData['heart_rate'] ?? 0) > 0;
            default:
                return false;
        }
    }

    private function handleCriticalAlert(IotBedSensor $sensor, string $alerts): void
    {
        // Send critical alert notifications
        // This would integrate with your notification system
        
        \Log::critical('Critical IoT bed sensor alert', [
            'sensor_id' => $sensor->sensor_id,
            'bed_id' => $sensor->bed_id,
            'alerts' => $alerts,
            'timestamp' => now(),
        ]);
    }

    private function getBedStatusOverview(): array
    {
        $totalBeds = Bed::count();
        $occupiedBeds = IotBedSensor::where('is_occupied', true)->count();
        $criticalAlerts = IotBedSensor::where('alert_level', 'critical')->count();
        $warningAlerts = IotBedSensor::where('alert_level', 'warning')->count();

        return [
            'total_beds' => $totalBeds,
            'occupied_beds' => $occupiedBeds,
            'available_beds' => $totalBeds - $occupiedBeds,
            'occupancy_rate' => $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0,
            'critical_alerts' => $criticalAlerts,
            'warning_alerts' => $warningAlerts,
        ];
    }
}
