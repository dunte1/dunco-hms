<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\PatientInsurance;
use App\Models\InsuranceApiLog;
use App\Models\InsuranceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InsuranceApiController extends Controller
{
    public function index()
    {
        $providers = InsuranceProvider::with('patientInsurances')->latest()->paginate(20);
        $logs = InsuranceApiLog::with('patientInsurance.patient')
            ->latest()
            ->paginate(20);
        
        return view('hms.integration.insurance-api', compact('providers', 'logs'));
    }

    public function verifyInsurance(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_insurance_id' => 'required|exists:patient_insurance,id',
            'provider' => 'required|string',
        ]);

        $patientInsurance = PatientInsurance::with('patient')->find($data['patient_insurance_id']);
        $provider = InsuranceProvider::where('name', $data['provider'])->first();

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Insurance provider not found'
            ], 404);
        }

        try {
            // Simulate API call to insurance provider
            $verificationResult = $this->callInsuranceApi($provider, $patientInsurance, 'verification');
            
            // Log the API call
            $this->logApiCall($patientInsurance, $provider, 'verification', $verificationResult);

            return response()->json([
                'success' => true,
                'data' => $verificationResult,
                'message' => 'Insurance verification completed'
            ]);
        } catch (\Exception $e) {
            $this->logApiCall($patientInsurance, $provider, 'verification', [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Insurance verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitClaim(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_insurance_id' => 'required|exists:patient_insurance,id',
            'claim_amount' => 'required|numeric',
            'service_codes' => 'required|array',
            'diagnosis_codes' => 'required|array',
        ]);

        $patientInsurance = PatientInsurance::with('patient')->find($data['patient_insurance_id']);
        $provider = InsuranceProvider::find($patientInsurance->insurance_provider_id);

        try {
            // Simulate claim submission
            $claimResult = $this->callInsuranceApi($provider, $patientInsurance, 'claim', $data);
            
            // Log the API call
            $this->logApiCall($patientInsurance, $provider, 'claim', $claimResult);

            return response()->json([
                'success' => true,
                'data' => $claimResult,
                'message' => 'Insurance claim submitted successfully'
            ]);
        } catch (\Exception $e) {
            $this->logApiCall($patientInsurance, $provider, 'claim', [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Claim submission failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkEligibility(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_insurance_id' => 'required|exists:patient_insurance,id',
            'service_type' => 'required|string',
        ]);

        $patientInsurance = PatientInsurance::with('patient')->find($data['patient_insurance_id']);
        $provider = InsuranceProvider::find($patientInsurance->insurance_provider_id);

        try {
            // Simulate eligibility check
            $eligibilityResult = $this->callInsuranceApi($provider, $patientInsurance, 'eligibility', $data);
            
            // Log the API call
            $this->logApiCall($patientInsurance, $provider, 'eligibility', $eligibilityResult);

            return response()->json([
                'success' => true,
                'data' => $eligibilityResult,
                'message' => 'Eligibility check completed'
            ]);
        } catch (\Exception $e) {
            $this->logApiCall($patientInsurance, $provider, 'eligibility', [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Eligibility check failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function callInsuranceApi(InsuranceProvider $provider, PatientInsurance $patientInsurance, string $type, array $additionalData = []): array
    {
        // Simulate API call based on provider and type
        $baseData = [
            'patient_id' => $patientInsurance->patient->id,
            'policy_number' => $patientInsurance->policy_number,
            'member_id' => $patientInsurance->member_id,
            'provider_name' => $provider->name,
        ];

        $requestData = array_merge($baseData, $additionalData);

        // Simulate different responses based on type
        switch ($type) {
            case 'verification':
                return $this->simulateVerificationResponse($requestData);
            case 'claim':
                return $this->simulateClaimResponse($requestData);
            case 'eligibility':
                return $this->simulateEligibilityResponse($requestData);
            default:
                return ['success' => false, 'error' => 'Unknown API type'];
        }
    }

    private function simulateVerificationResponse(array $data): array
    {
        // Simulate verification response
        return [
            'success' => true,
            'verified' => true,
            'policy_status' => 'active',
            'coverage_type' => 'comprehensive',
            'deductible_remaining' => 500.00,
            'co_pay' => 25.00,
            'response_time' => now()->toISOString()
        ];
    }

    private function simulateClaimResponse(array $data): array
    {
        // Simulate claim response
        return [
            'success' => true,
            'claim_id' => 'CLM-' . strtoupper(uniqid()),
            'status' => 'submitted',
            'estimated_processing_time' => '5-7 business days',
            'covered_amount' => $data['claim_amount'] * 0.8, // 80% coverage
            'patient_responsibility' => $data['claim_amount'] * 0.2,
            'response_time' => now()->toISOString()
        ];
    }

    private function simulateEligibilityResponse(array $data): array
    {
        // Simulate eligibility response
        return [
            'success' => true,
            'eligible' => true,
            'service_type' => $data['service_type'],
            'coverage_percentage' => 80,
            'requires_prior_authorization' => false,
            'network_status' => 'in-network',
            'response_time' => now()->toISOString()
        ];
    }

    private function logApiCall(PatientInsurance $patientInsurance, InsuranceProvider $provider, string $type, array $result): void
    {
        InsuranceApiLog::create([
            'patient_insurance_id' => $patientInsurance->id,
            'api_provider' => $provider->name,
            'request_type' => $type,
            'request_data' => [
                'patient_id' => $patientInsurance->patient->id,
                'policy_number' => $patientInsurance->policy_number,
                'member_id' => $patientInsurance->member_id,
            ],
            'response_data' => $result,
            'response_code' => $result['success'] ? 200 : 400,
            'status' => $result['success'] ? 'success' : 'failed',
            'error_message' => $result['error'] ?? null,
        ]);
    }
}
