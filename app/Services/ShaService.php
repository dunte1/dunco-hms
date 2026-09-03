<?php

namespace App\Services;

use App\Models\InsuranceApiLog;
use App\Models\ShaAuthorization;
use App\Models\ShaMember;
use App\Models\ShaProvider;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Social Health Authority (SHA) integration through the DHA Health
 * Interoperability Engine (HIE) / EHA.
 *
 * Authentication: OAuth2 client_credentials against /tenants/token.
 * The access token is cached in Laravel's cache for its remaining lifetime.
 *
 * Every call is audited to the insurance_api_logs table and laravel.log.
 */
class ShaService
{
    protected ?ShaProvider $provider;

    /** The OAuth endpoint relative to the base URL. */
    protected string $tokenPath = '/tenants/token';

    public function __construct(?ShaProvider $provider = null)
    {
        $this->provider = $provider ?? ShaProvider::where('is_active', true)->first();
    }

    /* ---------------------------------------------------------------------
     | Configuration helpers
     | --------------------------------------------------------------------- */

    public function baseUrl(): string
    {
        $env = config('eha.env', 'uat');
        $base = config("eha.base_urls.{$env}", 'https://ilm-dev.dha.go.ke/uat-middleware/api/v1');

        // Allow a facility-specific override stored on the provider record.
        if ($this->provider && filled($this->provider->api_base_url)) {
            return rtrim($this->provider->api_base_url, '/');
        }

        return rtrim($base, '/');
    }

    public function isConfigured(): bool
    {
        return filled(config('eha.client_id'));
    }

    public function facilityHeaders(): array
    {
        return [
            'X-Facility-Id' => config('eha.facility_id'),
            'X-Facility-Id-Type' => config('eha.facility_id_type', 'FRN'),
        ];
    }

    /* ---------------------------------------------------------------------
     | OAuth token
     | --------------------------------------------------------------------- */

    public function getAccessToken(): ?string
    {
        $key = 'eha_access_token_' . config('eha.env') . '_' . config('eha.client_id');

        return Cache::remember($key, (int) config('eha.cache_ttl', 1700), function () {
            return $this->fetchAccessToken();
        });
    }

    public function fetchAccessToken(): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::asForm()
                ->timeout((int) config('eha.timeout', 30))
                ->post($this->baseUrl() . $this->tokenPath, [
                    'client_id' => config('eha.client_id'),
                    'client_secret' => config('eha.client_secret'),
                    'scope' => '*',
                    'grant_type' => 'client_credentials',
                ]);

            $json = $response->json();

            if ($response->successful() && filled($json['access_token'] ?? null)) {
                $this->audit('token', [], $json, $response->status(), 'success');
                return $json['access_token'];
            }

            $this->audit('token', [], $json ?: $response->body(), $response->status(), 'failed');
            Log::error('EHA token acquisition failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            $this->audit('token', [], ['exception' => $e->getMessage()], 0, 'failed');
            Log::error('EHA token exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /* ---------------------------------------------------------------------
     | Patient & eligibility
     | --------------------------------------------------------------------- */

    /**
     * Search the SHA client registry for a patient.
     *
     * @param  array  $params  identification_number, identification_type, etc.
     */
    public function searchPatient(array $params): array
    {
        return $this->get('/patients', $params, 'search-patient');
    }

    /**
     * Verify a member by national ID / SHA number and store the result.
     * Returns a normalised result for the UI.
     */
    public function verifyMember(string $memberNumber, string $identificationType = 'ID'): array
    {
        if (!$this->isConfigured()) {
            return $this->fallbackLocalVerify($memberNumber);
        }

        $result = $this->searchPatient([
            'identification_number' => $memberNumber,
            'identification_type' => $identificationType,
        ]);

        if (!$result['success']) {
            return $this->fallbackLocalVerify($memberNumber, $result['message'] ?? null);
        }

        $patient = $result['data']['patient'] ?? $result['data'] ?? [];

        // Persist / update the local SHA member registry.
        $member = ShaMember::where('national_id', $memberNumber)
            ->orWhere('sha_member_number', $memberNumber)
            ->first();

        $member = ShaMember::updateOrCreate(
            $member ? ['id' => $member->id] : ['national_id' => $memberNumber],
            [
                'sha_member_number' => $patient['MemberNumber'] ?? $patient['sha_member_number'] ?? $memberNumber,
                'first_name' => $patient['FirstName'] ?? $patient['first_name'] ?? null,
                'last_name' => $patient['LastName'] ?? $patient['last_name'] ?? null,
                'date_of_birth' => $patient['DateOfBirth'] ?? $patient['date_of_birth'] ?? null,
                'gender' => $patient['Sex'] ?? $patient['gender'] ?? null,
                'phone' => $patient['PhoneNumber'] ?? $patient['phone'] ?? null,
                'tier_level' => $patient['Tier'] ?? $patient['tier'] ?? 'tier_1',
                'eligibility_status' => $patient['Status'] ?? $patient['eligibility_status'] ?? 'active',
                'last_verified_at' => now(),
            ]
        );

        return [
            'verified' => true,
            'member' => $member,
            'provider' => 'eha',
            'cr_id' => $result['data']['id'] ?? $result['data']['CrId'] ?? null,
            'data' => $result['data'],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Check a patient's SHA eligibility for the current facility.
     */
    public function checkEligibility(string $crId): array
    {
        return $this->get('/patients/eligibility', [
            'patient_id' => $crId,
            'facility_id' => config('eha.facility_id'),
            'facility_id_type' => config('eha.facility_id_type', 'FRN'),
        ], 'eligibility');
    }

    public function getBenefits(string $crId): array
    {
        return $this->get('/patients/benefits', ['patient_id' => $crId], 'benefits');
    }

    public function getSubBenefits(string $crId): array
    {
        return $this->get('/patients/sub-benefits', ['patient_id' => $crId], 'sub-benefits');
    }

    public function getInterventions(string $crId, ?string $subBenefitCode = null): array
    {
        $params = ['patient_id' => $crId];
        if ($subBenefitCode) {
            $params['sub_benefit_code'] = $subBenefitCode;
        }

        return $this->get('/patients/benefits/interventions', $params, 'interventions');
    }

    public function getUtilization(string $crId): array
    {
        return $this->get('/patients/benefits/utilization', ['patient_id' => $crId], 'utilization');
    }

    /* ---------------------------------------------------------------------
     | Consent / visits / authorizations
     | --------------------------------------------------------------------- */

    /**
     * Start a virtual claim / visit for a patient (triggers OTP on the
     * member's registered phone).
     */
    public function startVisitConsent(string $crId, array $extra = []): array
    {
        return $this->post('/claims/visit', array_merge([
            'patient_id' => $crId,
            'consent_source' => 'OTP',
        ], $extra), 'start-visit', $this->facilityHeaders());
    }

    /**
     * Request a preauthorization for a service.
     */
    public function requestPreauth(array $data): array
    {
        return $this->post('/preauths', $data, 'preauth', $this->facilityHeaders());
    }

    public function getPreauth(string $consentToken): array
    {
        return $this->get('/preauths', ['consent_token' => $consentToken], 'preauth-status');
    }

    public function cancelPreauth(array $data): array
    {
        return $this->post('/preauths/cancel', $data, 'preauth-cancel', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Claims
     | --------------------------------------------------------------------- */

    public function addClaimLine(array $data): array
    {
        return $this->post('/claims/lines', $data, 'claim-line', $this->facilityHeaders());
    }

    public function submitClaim(array $data): array
    {
        return $this->post('/claims/submit', $data, 'claim-submit', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Kept for backward compatibility with the existing UI
     | --------------------------------------------------------------------- */

    public function requestAuthorization(array $data): array
    {
        if (!$this->isConfigured()) {
            return $this->fallbackLocalAuthorization($data);
        }

        // Determine the patient CR id; the caller passes either cr_id or
        // member_number. Preferred flow uses the /preauths endpoint.
        try {
            if (!empty($data['cr_id'])) {
                $preauth = $this->requestPreauth($data);
                $approved = in_array($preauth['status'] ?? '', ['APPROVED', 'approved', 'PENDING', 'pending'], true);

                $authorization = ShaAuthorization::create([
                    'authorization_number' => $preauth['data']['pre_authorization']['pre_auth_no'] ?? $preauth['data']['preauth_no'] ?? ('EHA-' . strtoupper(Str::random(10))),
                    'patient_id' => $data['patient_id'] ?? null,
                    'sha_member_id' => $data['sha_member_id'] ?? null,
                    'service_type' => $data['service_type'] ?? null,
                    'service_code' => $data['service_code'] ?? null,
                    'diagnosis_code' => $data['diagnosis_code'] ?? null,
                    'diagnosis_description' => $data['diagnosis_description'] ?? null,
                    'authorized_amount' => $data['estimated_amount'] ?? $preauth['data']['pre_authorization']['limit_amount'] ?? 0,
                    'status' => strtolower($preauth['status'] ?? 'pending'),
                    'authorized_date' => now(),
                    'expiry_date' => $preauth['data']['pre_authorization']['end_date'] ?? now()->addDays(30),
                    'api_response' => $preauth['data'],
                ]);

                return [
                    'approved' => $approved,
                    'authorization_number' => $authorization->authorization_number,
                    'authorized_amount' => $authorization->authorized_amount,
                    'status' => $authorization->status,
                    'expiry' => optional($authorization->expiry_date)->toDateTimeString(),
                    'member' => $this->provider->name ?? 'EHA',
                    'api_response' => $preauth['data'],
                ];
            }

            // Legacy path when no CR id present.
            return $this->fallbackLocalAuthorization($data);
        } catch (\Exception $e) {
            Log::error('EHA authorization exception', ['message' => $e->getMessage()]);
            return $this->fallbackLocalAuthorization($data);
        }
    }

    /* ---------------------------------------------------------------------
     | HTTP helpers
     | --------------------------------------------------------------------- */

    protected function get(string $path, array $params, string $logType): array
    {
        return $this->call('GET', $path, $params, $logType);
    }

    protected function post(string $path, array $data, string $logType, array $headers = []): array
    {
        return $this->call('POST', $path, $data, $logType, $headers);
    }

    protected function call(string $method, string $path, array $payload, string $logType, array $extraHeaders = []): array
    {
        if (!$this->isConfigured()) {
            return $this->unconfiguredResult($logType);
        }

        $token = $this->getAccessToken();

        if (!$token) {
            return $this->unconfiguredResult($logType, 'Unable to obtain EHA access token.');
        }

        try {
            $builder = Http::withToken($token)
                ->withHeaders(array_merge(['Accept' => 'application/json'], $extraHeaders))
                ->timeout((int) config('eha.timeout', 30));

            $response = $method === 'POST'
                ? $builder->post($this->baseUrl() . $path, $payload)
                : $builder->get($this->baseUrl() . $path, $payload);

            $json = $response->json();

            $status = strtolower($response->json('status') ?? '');
            $success = $response->successful() && !in_array($status, ['FAILED', 'failed', 'ERROR', 'error'], true);

            $this->audit($logType, $payload, $json ?: $response->body(), $response->status(), $success ? 'success' : 'failed');

            if (!$success) {
                Log::warning('EHA request failed', [
                    'type' => $logType,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return [
                'success' => $success,
                'status' => $response->status(),
                'code' => $json['code'] ?? $json['status'] ?? null,
                'message' => $json['message'] ?? $json['error'] ?? null,
                'data' => $json,
            ];
        } catch (\Exception $e) {
            $this->audit($logType, $payload, ['exception' => $e->getMessage()], 0, 'failed');
            Log::error('EHA request exception', ['type' => $logType, 'message' => $e->getMessage()]);

            return [
                'success' => false,
                'status' => 0,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /* ---------------------------------------------------------------------
     | Audit logging
     | --------------------------------------------------------------------- */

    protected function audit(string $requestType, array $requestData, $responseData, int $responseCode, string $status): void
    {
        if (!config('eha.log_requests', true)) {
            return;
        }

        try {
            InsuranceApiLog::create([
                'api_provider' => 'EHA_SHA',
                'request_type' => $requestType,
                'request_data' => $requestData,
                'response_data' => is_array($responseData) ? $responseData : ['raw' => (string) $responseData],
                'response_code' => $responseCode,
                'status' => $status,
                'error_message' => $status === 'success' ? null : (is_array($responseData) ? json_encode($responseData) : (string) $responseData),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to persist EHA audit log', ['message' => $e->getMessage()]);
        }
    }

    /* ---------------------------------------------------------------------
     | Fallbacks when EHA is not configured / unreachable
     | --------------------------------------------------------------------- */

    protected function unconfiguredResult(string $logType, ?string $message = null): array
    {
        Log::info('EHA call skipped (not configured)', ['type' => $logType, 'base' => $this->baseUrl()]);

        return [
            'success' => false,
            'status' => 0,
            'code' => 'NOT_CONFIGURED',
            'message' => $message ?? 'EHA integration is not configured. Set EHA_CLIENT_ID / EHA_CLIENT_SECRET in .env.',
            'data' => null,
        ];
    }

    protected function fallbackLocalVerify(string $memberNumber, ?string $reason = null): array
    {
        $member = ShaMember::where('sha_member_number', $memberNumber)
            ->orWhere('national_id', $memberNumber)
            ->first();

        if ($member && $member->isEligible()) {
            return [
                'verified' => true,
                'member' => $member,
                'provider' => 'local-database',
                'message' => $reason ? "EHA unavailable ({$reason}) — member verified against local registry." : 'Member verified against local SHA registry.',
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        return [
            'verified' => false,
            'provider' => 'local-database',
            'message' => $reason ? "EHA unavailable ({$reason}) — member not eligible in local registry." : 'Member not found or not eligible in local SHA registry.',
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    protected function fallbackLocalAuthorization(array $data): array
    {
        return [
            'approved' => true,
            'authorization_number' => 'SHA-' . strtoupper(Str::random(10)),
            'authorized_amount' => $data['estimated_amount'] ?? 0,
            'status' => 'pending',
            'expiry' => now()->addDays(30)->toDateTimeString(),
            'api_response' => [
                'provider' => 'local-database',
                'provided_as' => 'manual verification pending SHA API credentials',
            ],
        ];
    }
}
