# Dunco HMS — DHA & SHA Integration Guide

## Overview

Dunco HMS integrates with Kenya's **Digital Health Superhighway (DHS)**, operated by the **Digital Health Authority (DHA)**. This provides:

- **SHA e-claims** via the **EHA** (Health Interoperability Engine) — electronic insurance claims processing, eligibility verification, and pre-authorization workflows against the **Social Health Authority (SHA)**.
- **Client Registry (CR)** — patient lookup and real-time identity verification.
- **Biometric verification** — national-registry biometric checks (falls back to local matching).
- **Facility Registry** — facility (FRN) lookup against the national registry.
- **Provider Registry** — health-worker / practitioner lookup.
- **Afyalink / document interchange** — clinical document transmission.

## Current Status

| Component | Status |
|-----------|--------|
| ShaService (OAuth2 integration) | Production-ready code |
| EHA Credentials (production) | **NOT CONFIGURED** — EHA_CLIENT_ID, EHA_CLIENT_SECRET, EHA_FACILITY_ID are empty |
| DHA Credentials (production) | **NOT CONFIGURED** — DHA_CLIENT_ID, DHA_CLIENT_SECRET, DHA_FACILITY_ID are empty |
| Local Database Fallback | Functional — system operates without live DHA/EHA APIs |
| InsuranceApiController (generic) | Wired to ShaService when EHA configured; tagged simulated fallback otherwise |
| Insurance Claims CRUD + EHA submission | Fully implemented |
| SHA Member Registry | Database tables created + `cr_id` link |
| SHA Service Codes | 25 codes seeded (plus ICD-10 codes) |
| Pre-Authorization | Implemented in ShaService (uses `cr_id`) |
| Claims Submission | Implemented in ShaService |
| Client Registry verify/lookup | Implemented in DhaService (fallback to local registry) |
| Biometric national verification | Implemented (DHA-first, local fallback) |
| Facility/Provider Registry | Implemented in DhaService |
| Afyalink document transmit | Implemented in DhaService |
| DHA admin UI | `/hms/integration/dha` |
| Audit Logging | Implemented (`insurance_api_logs`, `api_provider` = `EHA_SHA` or `DHA`) |
| Module enable/disable + routing | Fully implemented (77 modules, `module:` middleware, sidebar gating) |

## Important Disclaimer

**SHA/DHA integration is subject to confirmation of the applicable SHA interoperability/API requirements, facility credentials, and approved access from the Digital Health Authority.**

The system has the technical capability to integrate with SHA via EHA and the DHA registries, but live API connectivity has NOT been verified in the production environment because credentials have not been configured.

## Architecture

```
┌─────────────────────────────┐
│        Dunco HMS            │
│  ShaService / DhaService    │
└──────┬──────────────┬───────┘
       │ OAuth2       │ OAuth2
       ▼              ▼
┌─────────────────────┐   ┌───────────────────────────┐
│  DHA EHA            │   │  DHA Digital Health       │
│  (SHA e-claims)     │   │  Superhighway             │
│  UAT: ilm-dev.dha.go.ke   │  - Client Registry (CR) │
│  PROD: ilm.dha.go.ke│   │  - Facility Registry      │
└──────────┬──────────┘   │  - Provider Registry      │
           │              │  - Afyalink / Biometrics  │
           ▼              └─────────────┬─────────────┘
┌──────────────────┐                    │
│   SHA Registry   │                    ▼
│ (Member Data)    │        UAT: api.dha.go.ke/uat/api
└──────────────────┘        PROD: api.dha.go.ke/api
```

## ShaService Capabilities

The `ShaService` provides:

1. **OAuth2 Authentication** — Client credentials flow with token caching
2. **Patient Search** — Search SHA member registry
3. **Member Verification** — Verify member status and eligibility (persists national `cr_id`)
4. **Eligibility Checking** — Check coverage, benefits, sub-benefits
5. **Consent Management** — Visit consent with OTP
6. **Pre-Authorization** — Request, retrieve, cancel pre-authorizations (sends `cr_id`)
7. **Claims** — Add claim lines, submit claims
8. **Audit Logging** — Every API call logged to `insurance_api_logs`
9. **Fallback** — Graceful degradation to local database

## DhaService Capabilities

The `DhaService` (`app/Services/DhaService.php`) provides:

1. **OAuth2 Authentication** — separate from EHA, cached access token
2. **Client Registry Search** — `searchClientRegistry($identifier, $idType)`
3. **Patient Verification** — `verifyPatient($digitalId, $idType)` returns `cr_id` + demographics; falls back to local patient lookup
4. **Biometric Verification** — `verifyBiometric($template, $type, $nationalId)`; `BiometricService` calls DHA first, then local template matching
5. **Facility Lookup** — `getFacility()` against the Facility Registry (FRN)
6. **Provider Search** — `searchProviderRegistry($identifier)` against the Provider Registry
7. **Afyalink Document Transmit** — `transmitClinicalDocument($document)`
8. **Audit Logging** — every call logged with `api_provider='DHA'`
9. **Fallback** — returns graceful `NOT_CONFIGURED` / local-database responses when credentials are absent

### Patient-level DHA fields

When a patient is registered (or edited) with a national ID and DHA is configured, the system auto-verifies against the Client Registry and stores:

- `patients.national_id`
- `patients.dha_cr_id`
- `patients.dha_verified_at`

## Configuration

### Environment Variables

```
# SHA / EHA Integration (Kenya Social Health Authority)
EHA_ENV=uat                    # uat or production
EHA_CLIENT_ID=                 # Your EHA client ID
EHA_CLIENT_SECRET=             # Your EHA client secret
EHA_FACILITY_ID=               # Your facility ID
EHA_FACILITY_ID_TYPE=          # Facility ID type
EHA_TIMEOUT=30                 # API timeout in seconds
EHA_TOKEN_CACHE_TTL=1700       # Token cache duration
EHA_LOG_REQUESTS=true          # Enable API call logging

# DHA - Digital Health Superhighway (Client Registry, Facility/Provider Registries, Afyalink)
DHA_ENV=uat
DHA_UAT_BASE_URL=https://api.dha.go.ke/uat/api
DHA_PROD_BASE_URL=https://api.dha.go.ke/api
DHA_CLIENT_ID=
DHA_CLIENT_SECRET=
DHA_FACILITY_ID=               # Facility Registry Number (FRN)
DHA_TIMEOUT=30
DHA_TOKEN_CACHE_TTL=1700
DHA_LOG_REQUESTS=true
DHA_TRANSMIT_DOCUMENTS=true
```

### API Endpoints

| Service | Environment | Base URL |
|---------|-------------|----------|
| EHA (SHA claims) | UAT | https://ilm-dev.dha.go.ke/uat-middleware/api/v1 |
| EHA (SHA claims) | Production | https://ilm.dha.go.ke/api/v1 |
| DHA Superhighway | UAT | https://api.dha.go.ke/uat/api |
| DHA Superhighway | Production | https://api.dha.go.ke/api |

## Module Enable/Disable

A super-admin module registry powers sidebar and route gating. Admin UI: **Admin → Modules** (`/admin/modules`). 77 modules are seeded; core modules (`dashboard`, `settings`, `roles-alc`, `users`) cannot be disabled.

- Sidebar sections are gated via `Module::isEnabled('slug')` in `resources/views/partials/sidebar.blade.php`.
- Route groups use the `module:{slug}` middleware (registered in `bootstrap/app.php`), e.g. `module:sha-shif` for all SHA routes, `module:dha-integration` for the DHA admin UI.
- Slugs relevant to this integration: `sha-shif`, `dha-integration`, `insurance-management`, `biometric-security`, `queue-management`, `hr-management`, `reports-analytics`, `communication-frontdesk`.

## Database Tables

| Table | Purpose |
|-------|---------|
| `sha_providers` | Facility API configuration (keys, endpoints) |
| `sha_members` | SHA member registry (linked to patients, has `cr_id`) |
| `sha_authorizations` | Pre-authorization records |
| `sha_service_codes` | SHA service tariff codes (25 seeded) |
| `insurance_claims` | Claims lifecycle (with SHA-specific fields) |
| `insurance_api_logs` | API call audit trail (`api_provider` distinguishes `EHA_SHA` / `DHA`) |
| `icd10_codes` | ICD-10 diagnostic codes (75 seeded) |
| `modules` | Super-admin module registry (name, slug, category, `is_enabled`, `sort_order`) |
| `patients.national_id / dha_cr_id / dha_verified_at` | DHA Client Registry linkage |

## Workflows

### 1. Member Verification

```
Patient → Enter SHA number → ShaService::verifyMember() → EHA API → SHA Registry → Member data saved to sha_members (with cr_id)
```

### 2. Eligibility Check

```
Verified member → ShaService::checkEligibility() → EHA API → Coverage details returned
```

### 3. Pre-Authorization

```
Member (cr_id) → ShaService::requestAuthorization() → EHA API → Authorization number → sha_authorizations saved
```

### 4. Claim Submission

```
Treatment complete → Map to ICD-10 + service codes → InsuranceClaimsController::submit() → ShaService::submitClaim() → EHA API → Claim response → insurance_claims updated
```

### 5. Client Registry Verification

```
Patient (national ID) → DhaService::verifyPatient() → DHA CR → cr_id + demographics stored on patient
```

### 6. Biometric Verification

```
BiometricController / BiometricService → DHA biometric verify (if configured) → local template match fallback
```

## What Needs to Be Done for Production

1. **Obtain EHA credentials** from the Digital Health Authority (for SHA e-claims)
2. **Obtain DHA superhighway credentials** (for Client Registry / biometrics / registries / Afyalink)
3. **Register facility** with SHA/DHA and confirm the Facility Registry Number (FRN)
4. **Configure EHA_* and DHA_* variables** in `.env`
5. **Test in UAT environment** first
6. **Verify OAuth2 token acquisition** for both EHA and DHA
7. **Test member verification** with real SHA numbers
8. **Test eligibility checking and pre-authorization workflow**
9. **Test claims submission and reconciliation**
10. **Test Client Registry verification and biometric checks** against national records

## Seeded Reference Data

- **25 SHA service codes** covering major benefits (SSA and settlement-benefit tariffs)
- **77 modules** in the enable/disable registry
- **75 ICD-10 codes** covering infectious diseases, neoplasms, circulatory, respiratory, digestive, and more

Custom codes can be added via their management interfaces.