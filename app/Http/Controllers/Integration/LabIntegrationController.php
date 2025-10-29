<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\LabEquipment;
use App\Models\EquipmentResult;
use App\Models\LabRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LabIntegrationController extends Controller
{
    public function index()
    {
        $equipment = LabEquipment::with('results')->latest()->paginate(20);
        return view('hms.integration.lab-equipment', compact('equipment'));
    }

    public function createEquipment(Request $request)
    {
        $data = $request->validate([
            'equipment_name' => 'required|string',
            'equipment_type' => 'required|string',
            'model_number' => 'required|string',
            'serial_number' => 'required|string|unique:lab_equipment',
            'manufacturer' => 'required|string',
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer',
            'connection_type' => 'required|string',
            'configuration' => 'nullable|array',
        ]);

        $equipment = LabEquipment::create($data);

        return response()->json([
            'success' => true,
            'data' => $equipment,
            'message' => 'Lab equipment added successfully'
        ], 201);
    }

    public function testConnection(LabEquipment $equipment): JsonResponse
    {
        try {
            // Simulate connection test
            $isConnected = $this->testEquipmentConnection($equipment);
            
            $equipment->update(['is_connected' => $isConnected]);

            return response()->json([
                'success' => true,
                'connected' => $isConnected,
                'message' => $isConnected ? 'Equipment connected successfully' : 'Failed to connect to equipment'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function receiveResults(Request $request): JsonResponse
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:lab_equipment,id',
            'lab_request_id' => 'required|exists:lab_requests,id',
            'raw_data' => 'required|array',
        ]);

        $equipment = LabEquipment::find($data['equipment_id']);
        
        // Process raw data from equipment
        $processedData = $this->processEquipmentData($data['raw_data'], $equipment);

        $result = EquipmentResult::create([
            'lab_equipment_id' => $data['equipment_id'],
            'lab_request_id' => $data['lab_request_id'],
            'raw_data' => $data['raw_data'],
            'processed_data' => $processedData,
            'result_status' => 'processed'
        ]);

        // Update lab request status
        $labRequest = LabRequest::find($data['lab_request_id']);
        $labRequest->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Lab results received and processed successfully'
        ]);
    }

    public function getEquipmentResults(LabEquipment $equipment): JsonResponse
    {
        $results = $equipment->results()
            ->with('labRequest.patient')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    private function testEquipmentConnection(LabEquipment $equipment): bool
    {
        // Simulate connection test based on equipment type
        switch ($equipment->connection_type) {
            case 'tcp':
                return $this->testTcpConnection($equipment->ip_address, $equipment->port);
            case 'http':
                return $this->testHttpConnection($equipment->ip_address);
            case 'serial':
                return $this->testSerialConnection($equipment->serial_number);
            default:
                return false;
        }
    }

    private function testTcpConnection(string $ip, int $port): bool
    {
        // Simulate TCP connection test
        return @fsockopen($ip, $port, $errno, $errstr, 5) !== false;
    }

    private function testHttpConnection(string $ip): bool
    {
        // Simulate HTTP connection test
        $context = stream_context_create(['http' => ['timeout' => 5]]);
        return @file_get_contents("http://{$ip}/status", false, $context) !== false;
    }

    private function testSerialConnection(string $serial): bool
    {
        // Simulate serial connection test
        return true; // Simplified for demo
    }

    private function processEquipmentData(array $rawData, LabEquipment $equipment): array
    {
        // Process raw data based on equipment type
        $processedData = [];

        switch ($equipment->equipment_type) {
            case 'analyzer':
                $processedData = $this->processAnalyzerData($rawData);
                break;
            case 'centrifuge':
                $processedData = $this->processCentrifugeData($rawData);
                break;
            case 'microscope':
                $processedData = $this->processMicroscopeData($rawData);
                break;
            default:
                $processedData = $rawData;
        }

        return $processedData;
    }

    private function processAnalyzerData(array $rawData): array
    {
        // Process analyzer data (blood chemistry, etc.)
        return [
            'test_results' => $rawData['results'] ?? [],
            'units' => $rawData['units'] ?? [],
            'reference_ranges' => $rawData['ranges'] ?? [],
            'quality_control' => $rawData['qc'] ?? [],
            'processed_at' => now()->toISOString()
        ];
    }

    private function processCentrifugeData(array $rawData): array
    {
        // Process centrifuge data
        return [
            'speed' => $rawData['speed'] ?? 0,
            'time' => $rawData['time'] ?? 0,
            'temperature' => $rawData['temperature'] ?? 0,
            'processed_at' => now()->toISOString()
        ];
    }

    private function processMicroscopeData(array $rawData): array
    {
        // Process microscope data
        return [
            'magnification' => $rawData['magnification'] ?? 0,
            'image_data' => $rawData['image'] ?? null,
            'measurements' => $rawData['measurements'] ?? [],
            'processed_at' => now()->toISOString()
        ];
    }
}
