<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Digital Health Authority (DHA) — Digital Health Superhighway integration.
 *
 * Connects the HMIS to the national DHA.repositories:
 *   - Client Registry (CR): patient lookup & real-time verification
 *   - Facility Registry: facility (FRN) lookup
 *   - Provider Registry: health-worker / practitioner lookup
 *   - Afyalink / document interchange: clinical documents + claims
 *
 * Authentication uses OAuth2 client_credentials with a cached access token.
 * Every call is audited through the sharing insurance/API log tables where
 * possible and logged to laravel.log. Graceful local fallbacks are provided
 * so the system continues to work when DHA credentials are not configured.
 */
class DhaService
{
    protected string $tokenPath = '/oauth/token';

    public function baseUrl(): string
    {
        $env = config('dha.env', 'uat');
        return rtrim((string) config("dha.base_urls.{$env}", ''), '/');
    }

    public function isConfigured(): bool
    {
        return filled(config('dha.client_id')) && filled(config('dha.client_secret'));
    }

    public function facilityHeaders(): array
    {
        return [
            'X-Facility-Id' => config('dha.facility_id'),
            'X-Facility-Id-Type' => 'FRN',
            'Accept' => 'application/json',
        ];
    }

    public function getAccessToken(): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $key = 'dha_access_token_' . config('dha.env');

        return Cache::remember($key, (int) config('dha.token_cache_ttl', 1700), function () {
            return $this->fetchAccessToken();
        });
    }

    public function fetchAccessToken(): ?string
    {
        try {
            $response = Http::asForm()
                ->timeout((int) config('dha.timeout', 30))
                ->post($this->baseUrl() . $this->tokenPath, [
                    'client_id' => config('dha.client_id'),
                    'client_secret' => config('dha.client_secret'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'client-registry facility-registry provider-registry',
                ]);

            $json = $response->json();
            $this->log('token', [], $json ?: $response->body(), $response->status());

            if ($response->successful() && filled($json['access_token'] ?? null)) {
                return $json['access_token'];
            }

            Log::error('DHA token acquisition failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('DHA token exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /* ------------------------------------------------------------------
     | Client Registry (CR) — patient lookup & verification
     | ------------------------------------------------------------------ */

    /**
     * Search the national Client Registry for a patient by a digital ID
     * (national ID/ID number, or a DHA CR patient identifier).
     */
    public function searchClientRegistry(string $identifier, string $idType = 'ID'): array
    {
        return $this->call('GET', '/client-registry/search', [
            'identifier' => $identifier,
            'identifier_type' => $idType,
        ], 'cr-search');
    }

    /**
     * Verify a patient's identity against the national Client Registry
     * using a digital ID (national ID, passport, biometric reference, etc).
     *
     * @param  string  $digitalId      e.g. national ID number
     * @param  string  $digitalIdType  ID | PASSPORT | BIOMETRIC_REF | etc.
     */
    public function verifyPatient(string $digitalId, string $digitalIdType = 'ID'): array
    {
        if (!$this->isConfigured()) {
            return $this->fallbackVerify($digitalId);
        }

        $result = $this->searchClientRegistry($digitalId, $digitalIdType);

        if (!$result['success']) {
            return [
                'verified' => false,
                'provider' => 'dha-cr',
                'message' => $result['message'] ?? 'Client Registry lookup failed.',
                'data' => $result['data'],
            ];
        }

        $person = $result['data']['patient'] ?? $result['data']['person'] ?? $result['data'] ?? [];

        return [
            'verified' => true,
            'provider' => 'dha-cr',
            'cr_id' => $person['cr_id'] ?? $person['id'] ?? $person['CrId'] ?? null,
            'full_name' => $person['full_name'] ?? ($person['first_name'] ?? '') . ' ' . ($person['last_name'] ?? ''),
            'gender' => $person['gender'] ?? $person['Sex'] ?? null,
            'date_of_birth' => $person['date_of_birth'] ?? $person['DateOfBirth'] ?? null,
            'nationality' => $person['nationality'] ?? null,
            'data' => $result['data'],
        ];
    }

    /* ------------------------------------------------------------------
     | Facility Registry
     | ------------------------------------------------------------------ */

    public function getFacility(?string $facilityId = null): array
    {
        $facilityId = $facilityId ?: config('dha.facility_id');
        return $this->call('GET', '/facility-registry/' . urlencode((string) $facilityId), [], 'facility-lookup');
    }

    /* ------------------------------------------------------------------
     | Provider Registry
     | ------------------------------------------------------------------ */

    public function searchProviderRegistry(string $identifier, string $idType = 'PPB'): array
    {
        return $this->call('GET', '/provider-registry/search', [
            'identifier' => $identifier,
            'identifier_type' => $idType,
        ], 'provider-search');
    }

    /* ------------------------------------------------------------------
     | Afyalink / document interchange
     | ------------------------------------------------------------------ */

    /**
     * Transmit a clinical document / claim electronically via the DHA
     * network for reimbursement and audit purposes.
     */
    public function transmitClinicalDocument(array $document): array
    {
        return $this->call('POST', '/interchange/documents', $document, 'document-transmit', $this->facilityHeaders());
    }

    /* ------------------------------------------------------------------
     | Biometric verification
     | ------------------------------------------------------------------ */

    /**
     * Request real-time biometric verification against national records.
     *
     * @param  string  $template      Base64 fingerprint/face template
     * @param  string  $biometricType fingerprint|face|iris
     * @param  string  $nationalId    optional national ID to confirm
     */
    public function verifyBiometric(string $template, string $biometricType = 'fingerprint', ?string $nationalId = null): array
    {
        $payload = [
            'biometric_template' => $template,
            'biometric_type' => $biometricType,
        ];
        if ($nationalId) {
            $payload['national_id'] = $nationalId;
        }

        if (!$this->isConfigured()) {
            return [
                'verified' => false,
                'provider' => 'dha-biometric',
                'message' => 'DHA biometric verification not configured. Falling back to local matching.',
                'data' => null,
            ];
        }

        return $this->call('POST', '/client-registry/biometric-verify', $payload, 'biometric-verify', $this->facilityHeaders());
    }

    /* ------------------------------------------------------------------
     | HTTP helpers
     | ------------------------------------------------------------------ */

    protected function call(string $method, string $path, array $payload, string $logType, array $extraHeaders = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'status' => 0,
                'code' => 'NOT_CONFIGURED',
                'message' => 'DHA integration is not configured. Set DHA_CLIENT_ID / DHA_CLIENT_SECRET in .env.',
                'data' => null,
            ];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'status' => 401,
                'message' => 'Unable to obtain DHA access token.',
                'data' => null,
            ];
        }

        try {
            $builder = Http::withToken($token)
                ->withHeaders(array_merge(['Accept' => 'application/json'], $extraHeaders))
                ->timeout((int) config('dha.timeout', 30));

            $response = $method === 'POST'
                ? $builder->post($this->baseUrl() . $path, $payload)
                : $builder->get($this->baseUrl() . $path, $payload);

            $json = $response->json();
            $success = $response->successful();

            $this->log($logType, $payload, $json ?: $response->body(), $response->status());

            if (!$success) {
                Log::warning('DHA request failed', ['type' => $logType, 'status' => $response->status(), 'body' => $response->body()]);
            }

            return [
                'success' => $success,
                'status' => $response->status(),
                'code' => $json['code'] ?? $json['status'] ?? null,
                'message' => $json['message'] ?? $json['error'] ?? null,
                'data' => $json,
            ];
        } catch (\Exception $e) {
            $this->log($logType, $payload, ['exception' => $e->getMessage()], 0);
            Log::error('DHA request exception', ['type' => $logType, 'message' => $e->getMessage()]);
            return [
                'success' => false,
                'status' => 0,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    protected function log(string $type, array $requestData, $responseData, int $status): void
    {
        if (!config('dha.log_requests', true)) {
            return;
        }

        try {
            if (class_exists(\App\Models\InsuranceApiLog::class) && \Schema::hasTable('insurance_api_logs')) {
                \App\Models\InsuranceApiLog::create([
                    'api_provider' => 'DHA',
                    'request_type' => $type,
                    'request_data' => $requestData,
                    'response_data' => is_array($responseData) ? $responseData : ['raw' => (string) $responseData],
                    'response_code' => $status,
                    'status' => $status >= 200 && $status < 300 ? 'success' : 'failed',
                    'error_message' => (is_array($responseData) ? json_encode($responseData) : (string) $responseData),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to persist DHA audit log', ['message' => $e->getMessage()]);
        }
    }

    protected function fallbackVerify(string $digitalId): array
    {
        $patient = \App\Models\Patient::where('national_id', $digitalId)
            ->orWhere('phone', $digitalId)
            ->first();

        if ($patient) {
            return [
                'verified' => true,
                'provider' => 'local-database',
                'cr_id' => 'local-' . $patient->id,
                'full_name' => trim($patient->first_name . ' ' . $patient->last_name),
                'gender' => $patient->gender,
                'date_of_birth' => $patient->date_of_birth,
                'message' => 'DHA not configured — patient matched against local registry.',
                'data' => null,
            ];
        }

        return [
            'verified' => false,
            'provider' => 'local-database',
            'message' => 'DHA not configured — patient not found in local registry.',
            'data' => null,
        ];
    }
}
