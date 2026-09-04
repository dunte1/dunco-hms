# Dunco HMS — SHA Integration Guide

## Overview

The Dunco HMS includes integration with Kenya's **Social Health Authority (SHA)** through the **Digital Health Authority (DHA) Health Interoperability Engine (EHA)**. This enables electronic health insurance claims processing, eligibility verification, and pre-authorization workflows.

## Current Status

| Component | Status |
|-----------|--------|
| ShaService (OAuth2 integration) | Production-ready code |
| EHA Credentials (production) | **NOT CONFIGURED** — EHA_CLIENT_ID, EHA_CLIENT_SECRET, EHA_FACILITY_ID are empty in production |
| Local Database Fallback | Functional — system operates without live SHA API |
| InsuranceApiController (generic) | SIMULATED — returns hardcoded responses |
| Insurance Claims CRUD | Fully implemented |
| SHA Member Registry | Database tables created |
| ICD-10 Codes | 75 codes seeded |
| Pre-Authorization | Implemented in ShaService |
| Claims Submission | Implemented in ShaService |
| Audit Logging | Implemented (insurance_api_logs table) |

## Important Disclaimer

**SHA integration is subject to confirmation of the applicable SHA interoperability/API requirements, facility credentials, and approved access from the Digital Health Authority.**

The system has the technical capability to integrate with SHA via EHA, but live API connectivity has NOT been verified in the production environment because EHA credentials have not been configured.

## Architecture

```
┌──────────────────┐
│   Dunco HMS      │
│   (ShaService)   │
└────────┬─────────┘
         │ OAuth2 Client Credentials
         ▼
┌──────────────────┐
│   DHA EHA        │
│   (Health HIE)   │
│                  │
│ UAT: ilm-dev.dha.go.ke
│ PROD: ilm.dha.go.ke
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│   SHA Registry   │
│ (Member Data)    │
└──────────────────┘
```

## ShaService Capabilities

The `ShaService` (467 lines) provides:

1. **OAuth2 Authentication** — Client credentials flow with token caching
2. **Patient Search** — Search SHA member registry
3. **Member Verification** — Verify member status and eligibility
4. **Eligibility Checking** — Check coverage, benefits, sub-benefits
5. **Consent Management** — Visit consent with OTP
6. **Pre-Authorization** — Request, retrieve, cancel pre-authorizations
7. **Claims** — Add claim lines, submit claims
8. **Audit Logging** — Every API call logged to `insurance_api_logs`
9. **Fallback** — Graceful degradation to local database

## Configuration

### Environment Variables

```
EHA_ENV=uat                    # uat or production
EHA_CLIENT_ID=                 # Your EHA client ID
EHA_CLIENT_SECRET=             # Your EHA client secret
EHA_FACILITY_ID=               # Your facility ID
EHA_FACILITY_ID_TYPE=          # Facility ID type
EHA_TIMEOUT=30                 # API timeout in seconds
EHA_TOKEN_CACHE_TTL=1700       # Token cache duration
EHA_LOG_REQUESTS=true          # Enable API call logging
```

### EHA API Endpoints

| Environment | Base URL |
|-------------|----------|
| UAT | https://ilm-dev.dha.go.ke/uat-middleware/api/v1 |
| Production | https://ilm.dha.go.ke/api/v1 |

## Database Tables

| Table | Purpose |
|-------|---------|
| `sha_providers` | Facility API configuration (keys, endpoints) |
| `sha_members` | SHA member registry (linked to patients) |
| `sha_authorizations` | Pre-authorization records |
| `sha_service_codes` | SHA service tariff codes |
| `insurance_claims` | Claims lifecycle (with SHA-specific fields) |
| `insurance_api_logs` | API call audit trail |
| `icd10_codes` | ICD-10 diagnostic codes (75 seeded) |

## Workflow

### 1. Member Verification

```
Patient → Enter SHA number → ShaService::verifyMember() → EHA API → SHA Registry → Member data saved to sha_members
```

### 2. Eligibility Check

```
Verified member → ShaService::checkEligibility() → EHA API → Coverage details returned
```

### 3. Pre-Authorization

```
Service needed → ShaService::requestPreauth() → EHA API → Authorization number → sha_authorizations saved
```

### 4. Claim Submission

```
Treatment complete → Map to ICD-10 + service codes → ShaService::submitClaim() → EHA API → Claim response → insurance_claims updated
```

## What Needs to Be Done for Production

1. **Obtain EHA credentials** from the Digital Health Authority
2. **Register facility** with SHA/DHA
3. **Configure EHA_CLIENT_ID, EHA_CLIENT_SECRET, EHA_FACILITY_ID** in .env
4. **Test in UAT environment** first
5. **Verify OAuth2 token acquisition**
6. **Test member verification** with real SHA numbers
7. **Test eligibility checking**
8. **Test pre-authorization workflow**
9. **Test claims submission**
10. **Verify claims processing and reconciliation**

## ICD-10 Codes

75 common ICD-10 codes are pre-seeded covering:
- Infectious diseases (A00-B34)
- Neoplasms (C50-C80)
- Blood disorders (D50)
- Endocrine/metabolic (E10-E78)
- Mental/behavioral (F32-F41)
- Nervous system (G40-G43)
- Circulatory (I10-I63)
- Respiratory (J00-J98)
- Digestive (K21-K80)
- And more...

Custom ICD-10 codes can be added via the ICD-10 management interface.
