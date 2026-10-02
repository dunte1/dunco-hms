# SHA Integration Status

Generated: 2026-10-01
Overall status: **BLOCKED - EXTERNAL DEPENDENCY** for live calls
Local workflow implementation: **EXISTING**

---

## What Exists (Verified)

| Component | Path | Status |
|---|---|---|
| ShaService | app/Services/ShaService.php (788 lines) | Existing |
| Config | config/eha.php | Existing |
| Models | ShaMember, ShaProvider, ShaAuthorization, ShaServiceCode, InsuranceClaim, ClaimBatch, ClaimItem, ClaimRejection, ClaimRemittance | Existing |
| Controllers | ShaController, InsuranceController, InsuranceClaimsController, TariffController | Existing |
| Views | resources/views/hms/sha/, insurance/ | Existing |
| Routes | module:sha-shif group in routes/web.php | Existing |
| API logs | InsuranceApiLog model + insurance_api_logs table | Existing |
| Test | G062ShaCompletionTest | Existing |

---

## Configured Endpoints (From config/eha.php)

| Env | Base URL |
|---|---|
| UAT | https://ilm-dev.dha.go.ke/uat-middleware/api/v1 |
| Production | https://ilm.dha.go.ke/api/v1 |

Config keys (all empty in current .env):
- EHA_ENV (default uat)
- EHA_CLIENT_ID
- EHA_CLIENT_SECRET
- EHA_FACILITY_ID
- EHA_FACILITY_ID_TYPE (default fr-code)
- EHA_TIMEOUT
- EHA_TOKEN_CACHE_TTL

---

## Flow Implemented in ShaService (Per DHA HIE Docs)

1. Token: POST /tenants/token (OAuth2 client_credentials)
2. Patient search: GET /patients?identification_number&type
3. Eligibility: GET /patients/eligibility
4. Sub-benefits / interventions
5. Consent (OTP or biometric)
6. Visit start: POST /claims/visit -> consent_token
7. Preauth: POST /preauths
8. Billing: POST /claims/lines, /claims/preview
9. Dispatch: OP submit / IP OTP discharge; discard close

Identification types supported in code:
- Search: NATIONAL ID, REFUGEE ID, TEMPORARY ID, MANDATE NUMBER, ALIEN ID, BIRTH CERTIFICATE NUMBER, CR ID, SHA NUMBER, BIRTH NOTIFICATION, PASSPORT
- Eligibility: National ID, ClientRegistry ID, Birth Notification, Birth Certificate, Alien ID, Refugee ID, Mandate Number

---

## Audit Findings

| Check | Result |
|---|---|
| Member verification workflow | Implemented in service/controllers |
| Eligibility check | Implemented |
| Coverage / tariffs | Models + TariffController exist |
| Preauthorization | ShaAuthorization + service flow |
| Claims prepare/validate/submit | InsuranceClaimsController + claim models |
| Claim tracking / rejected / returned | ClaimRejection, ClaimRemittance models |
| Remittance / reconciliation | Models exist; reconciliation depth partial |
| Integration logs | insurance_api_logs + laravel.log |
| SHA reports | Reports exist at module level |
| Live credentials present | **NO** - EHA_* not set in .env |
| Live test calls verified | **NO** - cannot claim |

---

## Classification

| Item | Status |
|---|---|
| Local SHA workflow code | EXISTING |
| Live SHA API integration | **BLOCKED - EXTERNAL DEPENDENCY** |
| Production conformance claim | **NOT CLAIMED** |
| Fake success simulation | **NOT PRESENT** in service (falls back when unconfigured) |

---

## Required Before Live Use

1. Facility registration with DHA/SHA
2. Obtain EHA_CLIENT_ID and EHA_CLIENT_SECRET
3. Set EHA_FACILITY_ID (FR code) if multitenant/service-account token
4. Set EHA_ENV=uat then verify with real test calls
5. Only then set EHA_ENV=production
6. Document successful test evidence in this file

---

## Rules Followed

- No fabricated API endpoints beyond those referenced in ShaService against DHA HIE docs
- No fake SHA verified=true without real response
- Mock/sandbox labeling required for any simulated flows
- Do not claim live integration without verified credentials and successful test calls
