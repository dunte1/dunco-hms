# DHA / HIE Integration Status

Generated: 2026-10-01
Overall status: **BLOCKED - EXTERNAL DEPENDENCY** for live calls
Architecture readiness: **PARTIAL** (services + controllers exist; dedicated FHIR mapping layer missing)

---

## What Exists (Verified)

| Component | Path | Status |
|---|---|---|
| DhaService | app/Services/DhaService.php (324 lines) | Existing |
| DhaIntegrationController | app/Http/Controllers/Integration/DhaIntegrationController.php | Existing |
| Config | config/dha.php | Existing |
| EHR/FHIR controller methods | EhrIntegrationController fhirConfig, sendFhirResource | Partial |
| Views | resources/views/hms/integration/ehr/fhir-config.blade.php, hl7-config.blade.php | Existing |
| Routes | module:dha-integration group (7 routes) | Existing |
| Patient fields | dha_cr_id, dha_verified_at on Patient (used in controller) | Existing |

---

## Configured Endpoints (From config/dha.php)

| Env | Base URL |
|---|---|
| UAT | https://api.dha.go.ke/uat/api (DHA_UAT_BASE_URL) |
| Production | https://api.dha.go.ke/api (DHA_PROD_BASE_URL) |

Config keys (all empty in current .env):
- DHA_ENV
- DHA_CLIENT_ID
- DHA_CLIENT_SECRET
- DHA_FACILITY_ID (FRN)
- DHA_TIMEOUT

---

## Services Implemented in DhaService

1. OAuth2 token: POST /oauth/token (client_credentials)
   scopes: client-registry facility-registry provider-registry
2. Client Registry: search + verifyPatient (digital ID)
3. Facility Registry: getFacility
4. Provider Registry: searchProviderRegistry
5. Document interchange / biometric verify (controller methods)
6. Graceful local fallbacks when not configured
7. Audit via insurance/API log tables + laravel.log

---

## FHIR / National Interoperability

| Spec item | Status | Evidence |
|---|---|---|
| Dedicated interoperability layer | Missing | Logic in controllers/services, not isolated FHIR layer |
| FHIR R4 resource mapping | Partial | EhrIntegrationController sendFhirResource exists; no full resource map |
| Patient Summary / Encounter / Observation | Not verified as complete FHIR | Domain models exist; FHIR profiles not implemented as dedicated layer |
| MedicationRequest / MedicationDispense | Partial domain only | Pharmacy models exist; FHIR mapping not dedicated |
| DiagnosticReport / ServiceRequest | Domain models exist | Lab/Radiology models; FHIR mapping not dedicated |
| Claim FHIR resource | Domain claim models exist | SHA/Insurance claims; FHIR mapping not dedicated |
| Organization / Practitioner identity mapping | Partial | Facility/practitioner data exists; national identifier mapping unverified |
| Terminology mapping (ICD/SNOMED/etc.) | Partial | ICD coding controller exists; terminology service not dedicated |
| Consent | ConsentForm model + routes exist | Not wired to FHIR Consent resource |
| Official Kenya FHIR IG conformance | **UNKNOWN** | No IG verification artifacts in repo |
| Invent profiles | **NOT DONE** | Per spec: do not invent FHIR profiles |

---

## Classification

| Item | Status |
|---|---|
| DHA service integration code | EXISTING |
| Live DHA API integration | **BLOCKED - EXTERNAL DEPENDENCY** |
| FHIR architecture layer | **PARTIAL / MISSING dedicated layer** |
| Kenya IG final conformance claim | **NOT CLAIMED** |
| Draft IG awareness | ePrescription guide treated as draft v0.1.0 FHIR R4 |

---

## Required Before Live Use

1. Register facility with DHA
2. Obtain DHA_CLIENT_ID / DHA_CLIENT_SECRET / DHA_FACILITY_ID
3. Verify UAT with real calls; document responses
4. Design dedicated FHIR mapping layer (HMS models -> FHIR R4 -> validation -> terminology -> consent -> HIE connector)
5. Validate against official Kenya FHIR Implementation Guides
6. Do not place national interoperability logic in ordinary controllers long-term

---

## Recommended Architecture (Spec Section 31)

HMS Domain Models
       ↓
FHIR Mapping Layer
       ↓
FHIR Resources
       ↓
Validation
       ↓
Terminology / Identifier Mapping
       ↓
Consent / Authorization
       ↓
HIE Connector
       ↓
DHA / National HIE

Current code does not yet isolate this layer. Documented as gap; not fabricated.
