<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\PatientInsurance;
use App\Models\InsuranceApiLog;
use App\Models\InsuranceProvider;
use App\Models\ShaMember;
use App\Services\ShaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InsuranceApiController extends Controller
{
    public function __construct(protected ShaService $shaService) {}

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
            $shaMember = ShaMember::where('patient_id', $patientInsurance->patient_id)->first();

            if ($this->shaService->isConfigured() && $shaMember) {
                $verificationResult = $this->shaService->verifyMember(
                    $shaMember->sha_member_number
                );

                $this->logApiCall($patientInsurance, $provider, 'verification', $verificationResult);

                return response()->json([
                    'success' => $verificationResult['verified'] ?? false,
                    'data' => $verificationResult,
                    'message' => $verificationResult['verified'] ? 'SHA member verified successfully' : 'SHA member verification failed'
                ]);
            }

            $verificationResult = $this->simulateVerificationResponse([
                'patient_id' => $patientInsurance->patient->id,
                'policy_number' => $patientInsurance->policy_number,
                'member_id' => $patientInsurance->member_id,
                'provider_name' => $provider->name,
            ]);

            $this->logApiCall($patientInsurance, $provider, 'verification', $verificationResult);

            return response()->json([
                'success' => true,
                'data' => $verificationResult,
                'message' => 'Insurance verification completed (simulated — EHA not configured)'
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
            if ($this->shaService->isConfigured()) {
                $claimResult = $this->shaService->submitClaim([
                    'patient_id' => $patientInsurance->patient->id,
                    'claim_amount' => $data['claim_amount'],
                    'service_codes' => $data['service_codes'],
                    'diagnosis_codes' => $data['diagnosis_codes'],
                ]);

                $this->logApiCall($patientInsurance, $provider, 'claim', $claimResult);

                return response()->json([
                    'success' => $claimResult['success'] ?? false,
                    'data' => $claimResult['data'] ?? null,
                    'message' => $claimResult['success'] ? 'SHA claim submitted successfully' : 'SHA claim submission failed: ' . ($claimResult['message'] ?? 'Unknown error')
                ]);
            }

            $claimResult = $this->simulateClaimResponse([
                'patient_id' => $patientInsurance->patient->id,
                'policy_number' => $patientInsurance->policy_number,
                'claim_amount' => $data['claim_amount'],
            ]);

            $this->logApiCall($patientInsurance, $provider, 'claim', $claimResult);

            return response()->json([
                'success' => true,
                'data' => $claimResult,
                'message' => 'Insurance claim submitted (simulated — EHA not configured)'
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
            if ($this->shaService->isConfigured()) {
                $shaMember = ShaMember::where('patient_id', $patientInsurance->patient_id)->first();

                if ($shaMember && $shaMember->cr_id) {
                    $eligibilityResult = $this->shaService->checkEligibility($shaMember->cr_id);

                    $this->logApiCall($patientInsurance, $provider, 'eligibility', $eligibilityResult);

                    return response()->json([
                        'success' => $eligibilityResult['success'] ?? false,
                        'data' => $eligibilityResult['data'] ?? null,
                        'message' => $eligibilityResult['success'] ? 'SHA eligibility check completed' : 'SHA eligibility check failed: ' . ($eligibilityResult['message'] ?? 'Unknown error')
                    ]);
                }

                $eligibilityResult = $this->shaService->verifyMember($shaMember->sha_member_number ?? $patientInsurance->member_id);

                $this->logApiCall($patientInsurance, $provider, 'eligibility', $eligibilityResult);

                return response()->json([
                    'success' => $eligibilityResult['verified'] ?? false,
                    'data' => $eligibilityResult,
                    'message' => $eligibilityResult['verified'] ? 'SHA member verified for eligibility' : 'SHA member not found'
                ]);
            }

            $eligibilityResult = $this->simulateEligibilityResponse([
                'patient_id' => $patientInsurance->patient->id,
                'policy_number' => $patientInsurance->policy_number,
                'service_type' => $data['service_type'],
            ]);

            $this->logApiCall($patientInsurance, $provider, 'eligibility', $eligibilityResult);

            return response()->json([
                'success' => true,
                'data' => $eligibilityResult,
                'message' => 'Eligibility check completed (simulated — EHA not configured)'
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

    private function simulateVerificationResponse(array $data): array
    {
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
        return [
            'success' => true,
            'claim_id' => 'CLM-' . strtoupper(uniqid()),
            'status' => 'submitted',
            'estimated_processing_time' => '5-7 business days',
            'covered_amount' => $data['claim_amount'] * 0.8,
            'patient_responsibility' => $data['claim_amount'] * 0.2,
            'response_time' => now()->toISOString()
        ];
    }

    private function simulateEligibilityResponse(array $data): array
    {
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
            'api_provider' => $provider->name ?? 'EHA_SHA',
            'request_type' => $type,
            'request_data' => [
                'patient_id' => $patientInsurance->patient->id,
                'policy_number' => $patientInsurance->policy_number,
                'member_id' => $patientInsurance->member_id,
            ],
            'response_data' => $result,
            'response_code' => $result['success'] ?? $result['verified'] ?? false ? 200 : 400,
            'status' => ($result['success'] ?? $result['verified'] ?? false) ? 'success' : 'failed',
            'error_message' => $result['error'] ?? $result['message'] ?? null,
        ]);
    }
}
