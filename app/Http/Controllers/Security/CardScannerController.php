<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\RfidTag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardScannerController extends Controller
{
    /**
     * Show card scanner interface
     */
    public function index(): View
    {
        return view('hms.security.card-scanner.index');
    }
    
    /**
     * Process card scan
     */
    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'card_type' => 'required|in:id_card,rfid,magnetic_stripe',
            'card_data' => 'required|string',
            'scanner_location' => 'nullable|string',
        ]);
        
        try {
            $result = $this->processCardScan(
                $validated['card_type'],
                $validated['card_data'],
                $validated['scanner_location'] ?? 'unknown'
            );
            
            // Log the scan
            $this->logCardScan($validated['card_type'], $validated['card_data'], $result);
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Card scan failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Card scan processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Process different card types
     */
    protected function processCardScan(string $cardType, string $cardData, string $location): array
    {
        switch ($cardType) {
            case 'rfid':
                return $this->processRfidScan($cardData, $location);
            
            case 'id_card':
                return $this->processIdCardScan($cardData, $location);
            
            case 'magnetic_stripe':
                return $this->processMagneticStripeScan($cardData, $location);
            
            default:
                return [
                    'success' => false,
                    'message' => 'Unsupported card type'
                ];
        }
    }
    
    /**
     * Process RFID card scan
     */
    protected function processRfidScan(string $tagId, string $location): array
    {
        $tag = RfidTag::with(['patient', 'employee'])
            ->where('tag_id', $tagId)
            ->where('status', 'active')
            ->first();
        
        if (!$tag) {
            return [
                'success' => false,
                'message' => 'RFID tag not found or inactive',
                'card_type' => 'rfid',
                'tag_id' => $tagId,
            ];
        }
        
        // Update last seen
        $tag->update([
            'last_seen' => now(),
            'last_location' => $location,
        ]);
        
        $result = [
            'success' => true,
            'message' => 'RFID tag scanned successfully',
            'card_type' => 'rfid',
            'tag' => $tag,
        ];
        
        // Add associated entity info
        if ($tag->patient) {
            $result['patient'] = [
                'id' => $tag->patient->id,
                'name' => $tag->patient->full_name,
                'patient_no' => $tag->patient->patient_no,
            ];
        }
        
        if ($tag->employee) {
            $result['employee'] = [
                'id' => $tag->employee->id,
                'name' => $tag->employee->full_name,
                'employee_id' => $tag->employee->employee_id,
                'department' => $tag->employee->department->name ?? 'N/A',
            ];
        }
        
        return $result;
    }
    
    /**
     * Process ID card scan (barcode/QR code)
     */
    protected function processIdCardScan(string $cardData, string $location): array
    {
        // Try to extract patient number or employee ID
        $patientNo = null;
        $employeeId = null;
        
        // Try parsing as patient number (P-YYYY-######)
        if (preg_match('/P-(\d{4})-(\d+)/', $cardData, $matches)) {
            $patientNo = $cardData;
        }
        // Try parsing as employee ID
        elseif (preg_match('/EMP-(\d+)/', $cardData, $matches)) {
            $employeeId = $cardData;
        }
        
        $result = [
            'success' => false,
            'message' => 'ID card not recognized',
            'card_type' => 'id_card',
            'scanned_data' => $cardData,
        ];
        
        // Search for patient
        if ($patientNo) {
            $patient = Patient::where('patient_no', $patientNo)->first();
            if ($patient) {
                $result['success'] = true;
                $result['message'] = 'Patient ID card scanned';
                $result['patient'] = [
                    'id' => $patient->id,
                    'name' => $patient->full_name,
                    'patient_no' => $patient->patient_no,
                ];
            }
        }
        
        // Search for employee
        if ($employeeId) {
            $employee = Employee::where('employee_id', $employeeId)->first();
            if ($employee) {
                $result['success'] = true;
                $result['message'] = 'Employee ID card scanned';
                $result['employee'] = [
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                    'employee_id' => $employee->employee_id,
                    'department' => $employee->department->name ?? 'N/A',
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Process magnetic stripe card
     */
    protected function processMagneticStripeScan(string $cardData, string $location): array
    {
        // Parse magnetic stripe data (format: track1|track2|track3)
        $tracks = explode('|', $cardData);
        
        $result = [
            'success' => false,
            'message' => 'Magnetic stripe card not recognized',
            'card_type' => 'magnetic_stripe',
            'tracks' => count($tracks),
        ];
        
        // Parse track 2 (most common for ID cards)
        if (isset($tracks[1])) {
            // Track 2 format: ;PAN=ExpDate? or =PAN^Name^ExpDate
            $track2 = $tracks[1];
            
            // Try to extract card number and match to patient/employee
            if (preg_match('/=(\d+)/', $track2, $matches)) {
                $cardNumber = $matches[1];
                
                // Try to find matching patient or employee
                $patient = Patient::where('id', $cardNumber)->first();
                if ($patient) {
                    $result['success'] = true;
                    $result['message'] = 'Patient card scanned';
                    $result['patient'] = [
                        'id' => $patient->id,
                        'name' => $patient->full_name,
                        'patient_no' => $patient->patient_no,
                    ];
                } else {
                    $employee = Employee::where('id', $cardNumber)->first();
                    if ($employee) {
                        $result['success'] = true;
                        $result['message'] = 'Employee card scanned';
                        $result['employee'] = [
                            'id' => $employee->id,
                            'name' => $employee->full_name,
                            'employee_id' => $employee->employee_id,
                        ];
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Log card scan
     */
    protected function logCardScan(string $cardType, string $cardData, array $result): void
    {
        try {
            DB::table('card_scan_logs')->insert([
                'card_type' => $cardType,
                'card_number' => $result['patient']['patient_no'] ?? $result['employee']['employee_id'] ?? null,
                'scanned_data' => $cardData,
                'scanner_location' => request()->input('scanner_location', 'unknown'),
                'patient_id' => $result['patient']['id'] ?? null,
                'employee_id' => $result['employee']['id'] ?? null,
                'ip_address' => request()->ip(),
                'metadata' => json_encode($result),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log card scan: ' . $e->getMessage());
        }
    }
    
    /**
     * Get scan history
     */
    public function history(Request $request): JsonResponse
    {
        $query = DB::table('card_scan_logs')
            ->orderBy('created_at', 'desc')
            ->limit(100);
        
        if ($request->filled('card_type')) {
            $query->where('card_type', $request->card_type);
        }
        
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $logs = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}

