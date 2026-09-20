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
 * Reference: https://hie-docs.dha.go.ke/docs/scenarios/overview
 *
 * Flow per scenario:
 *   1. token        POST /api/v1/tenants/token        -> access_token
 *   2. patient      GET  /api/v1/patients?identification_number&identification_type
 *                                                   -> id (client registry CR ID)
 *   3. eligibility  GET  /api/v1/patients/eligibility?identification_number&identification_type
 *   4. sub-benefits GET  /api/v1/patients/sub-benefits?patient_id
 *   5. interventions GET /api/v1/patients/benefits/interventions?patient_id&sub_benefit_code
 *   consent (OTP):  GET /api/v1/patients/contacts, POST /api/v1/claims/otp, POST /api/v1/claims/authorize
 *   consent (bio):  POST /api/v1/claims/authorize (workstationID/agent) + iframe match
 *   6. start visit  POST /api/v1/claims/visit         -> token (consent_token)
 *   7. preauth      POST /api/v1/preauths  (consent_token, intervention_code, items, diagnoses, doctors)
 *   8. billing      POST /api/v1/claims/lines, POST /api/v1/claims/preview
 *   9. dispatch     OP: POST /api/v1/claims/submit   IP: POST /api/v1/claims/otp/discharge + /claims/discharge
 *                   discard: POST /api/v1/claims/close
 *
 * Authentication is OAuth2 client_credentials; the token is cached and every
 * call is audited to insurance_api_logs + laravel.log. All failures degrade to
 * tested local fallbacks when credentials are not configured.
 */
class ShaService
{
    protected ?ShaProvider $provider;

    /** The OAuth endpoint relative to the base URL. */
    protected string $tokenPath = '/tenants/token';

    /** Value clients pass to have the identification type auto-detected. */
    public const IDENT_AUTO = 'AUTO';

    /**
     * Identification types accepted by the client registry patient search.
     * (https://hie-docs.dha.go.ke/docs/registries/process/patientSearch)
     */
    public const SEARCH_IDENT_TYPES = [
        'NATIONAL ID', 'REFUGEE ID', 'TEMPORARY ID', 'MANDATE NUMBER', 'ALIEN ID',
        'BIRTH CERTIFICATE NUMBER', 'CR ID', 'SHA NUMBER', 'BIRTH NOTIFICATION', 'PASSPORT',
    ];

    /**
     * Identification types accepted by the eligibility check (SHA scheme covers).
     * (https://hie-docs.dha.go.ke/docs/claims/process/eligibility/eligibilityCheck)
     * "ClientRegistry ID" is the preferred type whenever the CR ID is already known.
     */
    public const ELIGIBILITY_IDENT_TYPES = [
        'National ID', 'ClientRegistry ID', 'Birth Notification', 'Birth Certificate',
        'Alien ID', 'Refugee ID', 'Mandate Number',
    ];

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
        return filled(config('eha.client_id')) && filled(config('eha.client_secret'));
    }

    /**
     * Facility context headers.
     *
     * Per https://hie-docs.dha.go.ke/docs/authentication/process/facility-identification:
     *  - A facility-scoped access token already carries the facility in its JWT claims, so
     *    the headers are NOT required (and sending them overrides the token claim).
     *  - Multitenant / service-account tokens MUST send both headers, and the only
     *    supported record type value is "fr-code".
     *
     * We therefore only emit the headers when a facility id is explicitly configured;
     * otherwise the token claim is relied on.
     */
    public function facilityHeaders(): array
    {
        if (!filled(config('eha.facility_id'))) {
            return [];
        }

        return [
            'X-Facility-Id' => config('eha.facility_id'),
            'X-Facility-Id-Type' => config('eha.facility_id_type', 'fr-code'),
        ];
    }

    /* ---------------------------------------------------------------------
     | Identification type helpers
     | --------------------------------------------------------------------- */

    /**
     * Map common user/gui inputs onto the canonical types the API recognises.
     */
    public function canonicalIdentificationType(string $type = self::IDENT_AUTO): string
    {
        $type = trim((string) $type);

        if ($type === '' || strtoupper($type) === self::IDENT_AUTO) {
            return $type === '' ? 'NATIONAL ID' : $type;
        }

        $map = [
            'NATIONAL ID' => 'NATIONAL ID', 'NATIONAL' => 'NATIONAL ID', 'NATIONALID' => 'NATIONAL ID', 'NID' => 'NATIONAL ID', 'ID CARD' => 'NATIONAL ID', 'ID' => 'NATIONAL ID', 'KRA PIN' => 'NATIONAL ID',
            'PASSPORT' => 'PASSPORT',
            'CR ID' => 'CR ID', 'CLIENT REGISTRY ID' => 'CR ID', 'CLIENTREGISTRYID' => 'CR ID', 'CRID' => 'CR ID',
            'SHA NUMBER' => 'SHA NUMBER', 'SHA' => 'SHA NUMBER', 'SHA NO' => 'SHA NUMBER', 'SHIF NUMBER' => 'SHA NUMBER',
            'REFUGEE ID' => 'REFUGEE ID', 'TEMPORARY ID' => 'TEMPORARY ID', 'MANDATE NUMBER' => 'MANDATE NUMBER', 'ALIEN ID' => 'ALIEN ID',
            'BIRTH CERTIFICATE NUMBER' => 'BIRTH CERTIFICATE NUMBER', 'BIRTH CERTIFICATE' => 'BIRTH CERTIFICATE NUMBER', 'BC NO' => 'BIRTH CERTIFICATE NUMBER',
            'BIRTH NOTIFICATION' => 'BIRTH NOTIFICATION',
            'CLIENTREGISTRY ID' => 'ClientRegistry ID',
        ];

        $lookup = strtoupper(preg_replace('/\s+/', ' ', $type));

        return $this->normalizeSpace($map[strtoupper($type)] ?? $map[$lookup] ?? $type);
    }

    /**
     * Detect the most likely identification type from the value itself.
     */
    public function detectIdentificationType(string $value): string
    {
        $value = trim($value);

        if (preg_match('/^CR\d/i', $value) || preg_match('/^CR\d.*/i', $value)) {
            return 'CR ID';
        }

        if (preg_match('/^SHA\d+/i', $value)) {
            return 'SHA NUMBER';
        }

        // National IDs in Kenya are 7-8 digits.
        if (preg_match('/^\d{7,8}$/', $value)) {
            return 'NATIONAL ID';
        }

        if (preg_match('/^[A-Z]{1,2}\d{6,}$/i', $value)) {
            return 'PASSPORT';
        }

        return 'NATIONAL ID';
    }

    protected function normalizeSpace(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', $value));
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
                    'grant_type' => 'client_credentials',
                    'scope' => '*',
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
     | Identification & eligibility
     | --------------------------------------------------------------------- */

    /**
     * Search the HIE client registry for a patient.
     *
     * @param  array  $params  identification_number, identification_type, ...
     */
    public function searchPatient(array $params): array
    {
        return $this->get('/patients', $params, 'search-patient');
    }

    /**
     * Verify a member by National ID / SHA number and persist the result.
     */
    public function verifyMember(string $memberNumber, string $identificationType = self::IDENT_AUTO): array
    {
        if (!$this->isConfigured()) {
            return $this->fallbackLocalVerify($memberNumber);
        }

        $type = $this->canonicalIdentificationType($identificationType);
        if ($identificationType === self::IDENT_AUTO) {
            $type = $this->detectIdentificationType($memberNumber);
        }

        $result = $this->searchPatient([
            'identification_number' => $memberNumber,
            'identification_type' => $type,
        ]);

        if (!$result['success']) {
            return $this->fallbackLocalVerify($memberNumber, $result['message'] ?? null);
        }

        $raw = $result['data'] ?? [];
        $patient = is_array($raw['patient'] ?? null) ? $raw['patient'] : (is_array($raw['results'] ?? null) ? reset($raw['results']) : $raw);
        $person = $this->normalizePatientRecord($patient);

        // Persist / update the local SHA member registry.
        $crId = $person['cr_id'];

        $member = ShaMember::where('national_id', $memberNumber)
            ->orWhere('sha_member_number', $memberNumber)
            ->first();

        $member = ShaMember::updateOrCreate(
            $member ? ['id' => $member->id] : ['national_id' => $memberNumber],
            [
                'sha_member_number' => $person['member_number'] ?? $memberNumber,
                'first_name' => $person['first_name'],
                'last_name' => $person['last_name'],
                'date_of_birth' => $person['date_of_birth'],
                'gender' => $person['gender'],
                'phone' => $person['phone'],
                'tier_level' => $person['tier'] ?? 'tier_1',
                'eligibility_status' => $person['status'] ?? 'active',
                'cr_id' => $crId,
                'last_verified_at' => now(),
            ]
        );

        return [
            'verified' => true,
            'member' => $member,
            'provider' => 'eha',
            'cr_id' => $crId,
            'identification_type' => $type,
            'person' => $person,
            'data' => $result['data'],
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Check a patient's SHA coverage.
     *
     * Per the HIE docs the endpoint takes identification_number + identification_type.
     * When you already hold the client registry CR ID, pass it here with the default
     * 'ClientRegistry ID' type to skip the extra lookup.
     */
    public function checkEligibility(string $crId, string $identificationType = 'ClientRegistry ID'): array
    {
        return $this->get('/patients/eligibility', [
            'identification_number' => $crId,
            'identification_type' => $this->canonicalIdentificationType($identificationType),
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
     | Consent: contacts / OTP / authorization
     | --------------------------------------------------------------------- */

    /**
     * GET /api/v1/patients/contacts — obtain the beneficiary's valid (masked)
     * registered contacts. The contact `id` is used as beneficiary_contact_id
     * when sending OTPs.
     *
     * @param  string  $patientId  beneficiary client registry ID (cr_id)
     * @param  array   $extra      optional extra query params (e.g. consent_token, is_alive)
     */
    public function getPatientContacts(string $patientId, array $extra = []): array
    {
        return $this->get('/patients/contacts', array_merge(['patient_id' => $patientId], $extra), 'contacts');
    }

    /**
     * POST /api/v1/claims/otp — request a consent OTP for a beneficiary.
     *
     * Fields: patient_id (or beneficiary_cr_id), intervention_codes[],
     * beneficiary_contact_id (optional, from getPatientContacts), consent_token
     * (when re-sending within a visit), otp_type ('' | 'discharge').
     */
    public function sendOtp(array $data): array
    {
        return $this->post('/claims/otp', $data, 'send-otp', $this->facilityHeaders());
    }

    /**
     * POST /api/v1/claims/authorize — create an authorization for OTP or
     * biometrics consent.
     *
     * OTP path:     patient_id, otp
     * Biometrics:   workstationID, agent national id, beneficiary CR id, ...
     *
     * Returns token (use as consent_token) and guid (use as auth_guid).
     */
    public function authorizeClaim(array $data): array
    {
        return $this->post('/claims/authorize', $data, 'authorize', $this->facilityHeaders());
    }

    /**
     * GET /api/v1/claims/authorizations/{consent_token} — check consent status.
     */
    public function getAuthorizations(string $consentToken): array
    {
        return $this->get('/claims/authorizations/' . urlencode($consentToken), [], 'get-authorization');
    }

    /**
     * POST /api/v1/claims/authorizations/{consent_token}/reject — clear a stuck
     * PENDING authorization so a new one can be created.
     */
    public function rejectAuthorization(string $consentToken): array
    {
        return $this->post('/claims/authorizations/' . urlencode($consentToken) . '/reject', [], 'reject-authorization', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Visits / preauths
     | --------------------------------------------------------------------- */

    /**
     * POST /api/v1/claims/visit — start a visit. OTP uses `otp`; biometrics uses
     * `auth_guid`. Response `token` is the consent_token for every subsequent call.
     *
     * Legacy signature kept for backward compatibility:
     *   startVisitConsent(string $crId, array $extra = [])
     */
    public function startVisitConsent(string $crId, array $extra = []): array
    {
        $payload = array_merge([
            'service_type' => 'OUTPATIENT',
        ], $extra);

        $payload['beneficiary_cr_id'] = $crId;
        unset($payload['patient_id']);

        return $this->post('/claims/visit', $payload, 'start-visit', $this->facilityHeaders());
    }

    /**
     * Array-friendly alias: startVisit([...]) where the array may already carry
     * beneficiary_cr_id / patient_id / service_type / otp / auth_guid / interventions.
     */
    public function startVisit(array $data): array
    {
        $crId = $data['beneficiary_cr_id'] ?? $data['patient_id'] ?? $data['cr_id'] ?? null;
        if (!$crId) {
            return $this->unconfiguredResult('start-visit', 'beneficiary_cr_id (client registry ID) is required to start a visit.');
        }

        return $this->startVisitConsent($crId, $data);
    }

    /**
     * POST /api/v1/preauths — create a preauthorization.
     * Requires consent_token + intervention_code + items[] + diagnoses[] + doctors[].
     */
    public function requestPreauth(array $data): array
    {
        return $this->post('/preauths', $data, 'preauth', $this->facilityHeaders());
    }

    /**
     * GET /api/v1/preauths — poll preauth status by consent token.
     */
    public function getPreauth(string $consentToken): array
    {
        return $this->get('/preauths', ['consent_token' => $consentToken], 'preauth-status');
    }

    public function cancelPreauth(array $data): array
    {
        return $this->post('/preauths/cancel', $data, 'preauth-cancel', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Billing
     | --------------------------------------------------------------------- */

    public function addClaimLine(array $data): array
    {
        return $this->post('/claims/lines', $data, 'claim-line', $this->facilityHeaders());
    }

    /**
     * POST /api/v1/claims/preview — preview the provider claim before dispatch.
     */
    public function previewClaim(string $consentToken): array
    {
        return $this->post('/claims/preview', ['consent_token' => $consentToken], 'claim-preview', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Claim dispatch
     | --------------------------------------------------------------------- */

    /**
     * POST /api/v1/claims/submit — submit an outpatient claim.
     * Fields: consent_token, invoice_number, discharge_reason,
     * otp|discharge_auth_guid, discharge_status (PARTIAL|FULL).
     */
    public function submitClaim(array $data): array
    {
        return $this->post('/claims/submit', $data, 'claim-submit', $this->facilityHeaders());
    }

    /**
     * POST /api/v1/claims/otp/discharge — send the discharge OTP for an inpatient.
     * Fields: beneficiary_cr_id, beneficiary_contact_id, consent_token, otp_type=discharge.
     */
    public function sendDischargeOtp(array $data): array
    {
        return $this->post('/claims/otp/discharge', $data, 'discharge-otp', $this->facilityHeaders());
    }

    /**
     * POST /api/v1/claims/discharge — discharge an inpatient and submit the claim.
     * Fields: consent_token, discharge_date, invoice_number, discharge_reason,
     * otp|discharge_auth_guid.
     */
    public function dischargePatient(array $data): array
    {
        return $this->post('/claims/discharge', $data, 'discharge', $this->facilityHeaders());
    }

    /**
     * POST /api/v1/claims/close — discard a claim.
     */
    public function closeClaim(array $data): array
    {
        return $this->post('/claims/close', $data, 'claim-close', $this->facilityHeaders());
    }

    /* ---------------------------------------------------------------------
     | Kept for backward compatibility with the existing UI
     | --------------------------------------------------------------------- */

    public function requestAuthorization(array $data): array
    {
        $consentToken = $data['consent_token'] ?? null;

        if (!$this->isConfigured()) {
            return $this->fallbackLocalAuthorization($data);
        }

        // New flow: a preauth requires an active consent_token from authorize/start-visit.
        if (filled($consentToken) && !empty($data['cr_id'])) {
            try {
                $preauth = $this->requestPreauth(array_merge([
                    'consent_token' => $consentToken,
                ], array_filter($data, fn ($k) => !in_array($k, ['cr_id', 'member_number'], true), ARRAY_FILTER_USE_KEY)));

                $approved = in_array($preauth['status'] ?? '', ['APPROVED', 'approved', 'ACTIVE', 'active', 'FINALISED', 'finalised', 'PENDING', 'pending'], true);

                $authorization = ShaAuthorization::create([
                    'authorization_number' => $preauth['data']['pre_authorization']['pre_auth_no'] ?? $preauth['data']['preauth_no'] ?? $preauth['data']['preauth_id'] ?? ('EHA-' . strtoupper(Str::random(10))),
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
            } catch (\Exception $e) {
                Log::error('EHA authorization exception', ['message' => $e->getMessage()]);
                return $this->fallbackLocalAuthorization($data);
            }
        }

        // Legacy path when no consent token is present (offline / manual mode).
        return $this->fallbackLocalAuthorization($data);
    }

    /* ---------------------------------------------------------------------
     | Response normalisation
     | --------------------------------------------------------------------- */

    /**
     * Normalise a client registry patient record across the field spellings the
     * HIE and older EHA middleware return (camelCase and PascalCase).
     */
    public function normalizePatientRecord(array $patient): array
    {
        $first = $patient['firstName'] ?? $patient['FirstName'] ?? null;
        $last = $patient['lastName'] ?? $patient['LastName'] ?? null;
        $full = $patient['fullName'] ?? $patient['FullName'] ?? ($patient['name'] ?? null);

        if (!$full && ($first || $last)) {
            $full = trim(($first ?? '') . ' ' . ($last ?? ''));
        }

        if ((!$first || !$last) && $full) {
            $parts = preg_split('/\s+/', trim($full));
            $first = $first ?? ($parts[0] ?? null);
            $last = $last ?? ($parts[1] ?? null);
        }

        return [
            'cr_id' => $patient['id'] ?? $patient['crId'] ?? $patient['CrId'] ?? $patient['clientRegistryId'] ?? $patient['memberCrNumber'] ?? null,
            'first_name' => $first,
            'last_name' => $last,
            'full_name' => $full,
            'member_number' => $patient['MemberNumber'] ?? $patient['memberNumber'] ?? $patient['shaNumber'] ?? $patient['ShaNumber'] ?? $patient['shaMemberNumber'] ?? null,
            'date_of_birth' => $patient['DateOfBirth'] ?? $patient['dateOfBirth'] ?? $patient['dob'] ?? $patient['DOB'] ?? null,
            'gender' => $patient['Sex'] ?? $patient['gender'] ?? null,
            'phone' => $patient['PhoneNumber'] ?? $patient['phoneNumber'] ?? $patient['phone'] ?? null,
            'tier' => $patient['Tier'] ?? $patient['tierLevel'] ?? $patient['tier'] ?? null,
            'status' => $patient['Status'] ?? $patient['status'] ?? $patient['eligibilityStatus'] ?? null,
            'age' => $patient['age'] ?? $patient['Age'] ?? null,
            'nationality' => $patient['nationality'] ?? null,
        ];
    }

    /**
     * Normalise the eligibility response flags used to drive the UI.
     */
    public function normalizeEligibility(array $data): array
    {
        $person = $this->normalizePatientRecord($data);
        $schemes = $data['schemes'] ?? $data['Schemes'] ?? [];

        return array_merge($person, [
            'schemes' => $schemes,
            'is_alive' => $data['isAlive'] ?? $data['is_alive'] ?? null,
            'whitelisted_for_otp' => $data['whitelistedForOTP'] ?? $data['whitelisted_for_otp'] ?? null,
            'facility_biometrics_enforced' => $data['facilityBiometricsEnforced'] ?? $data['facility_biometrics_enforced'] ?? null,
            'is_pomsf' => collect($schemes)->contains(fn ($s) => str_starts_with((string) ($s['schemeName'] ?? $s['name'] ?? ''), 'POMSF')),
        ]);
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

            $status = strtolower($json['status'] ?? '');
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