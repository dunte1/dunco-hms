<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EhrIntegrationController extends Controller
{
    /**
     * Display EHR integration settings
     */
    public function index(): View
    {
        return view('hms.integration.ehr.index');
    }

    /**
     * HL7 Configuration
     */
    public function hl7Config(): View
    {
        return view('hms.integration.ehr.hl7-config');
    }

    /**
     * FHIR Configuration
     */
    public function fhirConfig(): View
    {
        return view('hms.integration.ehr.fhir-config');
    }

    /**
     * Send HL7 Message
     */
    public function sendHl7Message(Request $request)
    {
        $validated = $request->validate([
            'message_type' => 'required|in:ADT,ORM,ORU',
            'patient_id' => 'required|exists:patients,id',
            'data' => 'required|array',
        ]);

        // Generate HL7 message
        $hl7Message = $this->generateHl7Message(
            $validated['message_type'],
            $validated['patient_id'],
            $validated['data']
        );

        // Send to configured endpoint
        $endpoint = config('ehr.hl7_endpoint');
        if ($endpoint) {
            try {
                $response = Http::post($endpoint, [
                    'message' => $hl7Message,
                ]);

                Log::info('HL7 message sent', [
                    'message_type' => $validated['message_type'],
                    'patient_id' => $validated['patient_id'],
                    'status' => $response->status(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'HL7 message sent successfully',
                    'hl7_message' => $hl7Message,
                ]);
            } catch (\Exception $e) {
                Log::error('HL7 message failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send HL7 message: ' . $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'HL7 endpoint not configured',
        ], 400);
    }

    /**
     * Receive HL7 Message
     */
    public function receiveHl7Message(Request $request)
    {
        $message = $request->input('message');
        
        // Parse HL7 message
        $parsed = $this->parseHl7Message($message);
        
        // Process based on message type
        $this->processHl7Message($parsed);

        return response()->json(['success' => true]);
    }

    /**
     * Generate HL7 Message
     */
    private function generateHl7Message(string $type, int $patientId, array $data): string
    {
        // Basic HL7 message structure
        // MSH|^~\&|SendingApp|SendingFacility|ReceivingApp|ReceivingFacility|20250121120000||ADT^A01|123456|P|2.5
        
        $patient = \App\Models\Patient::findOrFail($patientId);
        $timestamp = now()->format('YmdHis');
        $messageControlId = uniqid();

        $msh = "MSH|^~\\&|DuncoHMS|Hospital|External|External|{$timestamp}||{$type}^A01|{$messageControlId}|P|2.5";
        $pid = "PID|1||{$patient->id}||{$patient->first_name}^{$patient->last_name}||{$patient->date_of_birth->format('Ymd')}|{$patient->gender}";
        
        return "$msh\r$pid";
    }

    /**
     * Parse HL7 Message
     */
    private function parseHl7Message(string $message): array
    {
        $segments = explode("\r", $message);
        $parsed = [];

        foreach ($segments as $segment) {
            $fields = explode('|', $segment);
            $segmentType = $fields[0] ?? '';
            $parsed[$segmentType] = $fields;
        }

        return $parsed;
    }

    /**
     * Process HL7 Message
     */
    private function processHl7Message(array $parsed): void
    {
        // Process incoming HL7 messages
        // This would typically update patient records, create orders, etc.
        Log::info('HL7 message processed', ['parsed' => $parsed]);
    }

    /**
     * Send FHIR Resource
     */
    public function sendFhirResource(Request $request)
    {
        $validated = $request->validate([
            'resource_type' => 'required|in:Patient,Observation,MedicationRequest',
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patient = \App\Models\Patient::findOrFail($validated['patient_id']);
        $fhirResource = $this->generateFhirResource($validated['resource_type'], $patient);

        $endpoint = config('ehr.fhir_endpoint');
        if ($endpoint) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/fhir+json',
                ])->post($endpoint, $fhirResource);

                return response()->json([
                    'success' => true,
                    'message' => 'FHIR resource sent successfully',
                    'resource' => $fhirResource,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send FHIR resource: ' . $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'FHIR endpoint not configured',
        ], 400);
    }

    /**
     * Generate FHIR Resource
     */
    private function generateFhirResource(string $type, $patient): array
    {
        if ($type === 'Patient') {
            return [
                'resourceType' => 'Patient',
                'id' => (string)$patient->id,
                'identifier' => [
                    [
                        'system' => 'http://hospital.example.org/patients',
                        'value' => (string)$patient->id,
                    ],
                ],
                'name' => [
                    [
                        'family' => $patient->last_name,
                        'given' => [$patient->first_name],
                    ],
                ],
                'gender' => strtolower($patient->gender),
                'birthDate' => $patient->date_of_birth->format('Y-m-d'),
            ];
        }

        return [];
    }

    /**
     * Test HL7 Connection
     */
    public function testHl7Connection(Request $request)
    {
        $endpoint = $request->input('endpoint');
        
        try {
            $response = Http::timeout(5)->get($endpoint . '/health');
            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
