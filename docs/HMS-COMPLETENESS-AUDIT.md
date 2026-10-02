# HMS Completeness Audit

Audit date: 2026-10-01  
Auditor: Automated codebase inspection (opencode)  
Specification: `New Text Document (2).txt` — HMS RBAC/Menu/Permissions/Clinical/SHA/DHA Completeness Specification  
Stack: Laravel 12, Spatie Laravel Permission ^6.21, Blade sidebar, Sanctum API

---

## Executive Summary

The DuncoHMS codebase is substantially more complete than older internal reports (`gaps-audit.md`, `FEATURE_CONFIRMATION_REPORT.md`) claim. All 32 specified roles exist. All major clinical modules have controllers, models, and views. The sidebar is already permission-driven via `@can`/`@canany` (not hard-coded by role).

The genuine gaps found are concentrated in **authorization enforcement depth**, **scope enforcement**, **policy registration**, **audit trait adoption**, and **external integration credentials** (SHA/DHA).

| Metric | Value |
|---|---|
| Total roles | 32 |
| Unique permissions (main seeder) | 344 |
| Sidebar permissions (additional) | ~100 |
| Approx. total seeded permissions | ~444 |
| Menu top-level groups | 13 |
| Hms controllers | 305 |
| Feature tests | 63 |
| Migrations | 427 |
| Policies defined | 4 (were unregistered) |
| Route middleware hits (web.php) | 8 pre-audit → expanded this session |

---

## Classification Legend

| Status | Meaning |
|---|---|
| EXISTING | Implemented and functional |
| PARTIAL | Some functionality exists; important components missing |
| MISSING | No meaningful implementation |
| DUPLICATE | Exists elsewhere; consolidate rather than recreate |
| UNKNOWN | Cannot be established from repo without external credentials |

---

## Area Audit Table

| Area | Existing | Partial | Missing | Duplicate | Unknown | Evidence |
|---|---|---|---|---|---|---|
| Roles (32) | ✓ | | | | | `database/seeders/RolesAndPermissionsSeeder.php` L268–302; test asserts count 32 |
| Permissions (~444) | ✓ | | | | | Main seeder 344 unique; `SidebarPermissionsSeeder.php` ~100 |
| Dynamic sidebar | ✓ | | | | | `resources/views/partials/sidebar.blade.php` — 49+ `@can`/`@canany` directives |
| Permission middleware | Partial | ✓ | | | | `PermissionMiddleware.php` exists; web.php had only 8 hits pre-audit |
| Policies | | ✓ | | | | 4 policies in `app/Policies/`; were not registered in AppServiceProvider |
| Scope (branch/facility) | | ✓ | | | | Columns on ~31 models; no query scopes or scope middleware |
| Patient registration | ✓ | | | | | `PatientsController`, `Patient` model, views under `hms/patients/` |
| Patient 360 | Partial | ✓ | | | | Multiple patient views exist; no single consolidated 360 route confirmed |
| Triage | ✓ | | | | | `TriageController`, `TriageEscalationController`, `VitalsController`, models |
| Emergency/Casualty | ✓ | | | | | `EmergencyAdmission`, `ResuscitationController`, `TraumaController` |
| Inpatient/Ward/Bed | ✓ | | | | | `Ward`, `Bed`, `BedAssignment`, `IpdAdmissionsController`, `WardRoundController` |
| ICU/HDU | ✓ | | | | | `IcuAdmission`, `CriticalCareChart`, ventilator/sedation/infusion controllers |
| Maternity | ✓ | | | | | `Pregnancy`, `AncVisit`, `LabourRecord`, `Delivery`, related controllers |
| Neonatal/NICU | ✓ | | | | | `NewbornController`, `NicuAdmission`, `PhototherapyController`, feeds |
| Theatre/OT | ✓ | | | | | `OtSchedule`, `OtRoom`, WHO checklist, preop, recovery, theatre team controllers |
| Anaesthesia | ✓ | | | | | `AnaesthesiaAssessment`, `AnaesthesiaRecord`, drugs, complications, PACU review |
| Laboratory | ✓ | | | | | `LabRequest`, `LabTest`, specimen/worklist/verification controllers |
| Radiology | ✓ | | | | | `RadiologyRequest`, imaging/report/worklist controllers |
| Blood Bank | ✓ | | | | | Donors, donations, units, crossmatch, transfusion controllers |
| Pharmacy/Prescriptions | ✓ | | | | | Medicines, prescriptions, ePrescription, dispensing, controlled drugs |
| CSSD | ✓ | | | | | Instruments, batches, cycles, sterilizer runs, issue/return controllers |
| Mortuary | ✓ | | | | | Records, slots, body ID, postmortem, death certificate controllers |
| Ambulance | ✓ | | | | | Vehicles, calls, trips, fuel, maintenance controllers |
| Maintenance/Biomedical | ✓ | | | | | Assets, work orders, calibrations, equipment controllers |
| Security | ✓ | | | | | Security controllers, biometric, card scanner, incidents, access events |
| Social Work | ✓ | | | | | `SocialAssessment`, waivers, discharge plans |
| Dietetics | ✓ | | | | | `DietOrder`, `NutritionController`, meal orders |
| Mental Health | ✓ | | | | | `MhAssessmentController`, treatment plans, counselling sessions |
| HIV/TB/Oncology | ✓ | | | | | HTS, ART, viral load, TB screens/treatments, cancer/chemo controllers |
| SHA/Insurance | ✓ | | | | | `ShaService`, `ShaMember`, claims, tariffs, UAT/prod base URLs |
| SHA live credentials | | | | | ✓ | No `EHA_*` keys in `.env` → BLOCKED_EXTERNAL_DEPENDENCY |
| DHA/HIE | ✓ | | | | | `DhaService`, `DhaIntegrationController`, `config/dha.php` |
| DHA live credentials | | | | | ✓ | No `DHA_*` keys in `.env` → BLOCKED_EXTERNAL_DEPENDENCY |
| FHIR mapping layer | | ✓ | | | | `EhrIntegrationController::fhirConfig/sendFhirResource`; no dedicated mapping layer |
| ePrescription | ✓ | | | | | `EPrescriptionController`, templates, routes under `prescriptions/e-prescription` |
| Appointments/Queue | ✓ | | | | | Controllers, models, queue management, reminders |
| Billing/Finance | ✓ | | | | | Invoices, payments, accounts, journals, expenses, income |
| HR | ✓ | | | | | Employees, payroll, attendance, leave, recruitment, shifts |
| Reports | ✓ | | | | | Reports, analytics, KHIS/MOH, birth/death, custom builder |
| CMS/Marketing | ✓ | | | | | CMS controllers, marketing models/views |
| AI (Elliana) | ✓ | | | | | `EllianaDController`, `AiAssistantController`, suggestion models |
| Audit logging | ✓ | | | | | `AuditLog::log`, `audit_logs` table; `Auditable` trait existed but unused |
| Break-glass | Partial | ✓ | | | | `BreakGlassEvent` model + permission exist; workflow completeness unverified |
| Authorization tests | ✓ | | | | | `RolesAndPermissionsTest`, `PermissionMiddlewareTest`, module G0xx tests |
| Stale internal docs | | | | ✓ | | `docs/ROLE-PERMISSION-MATRIX.md` (21 roles/93 perms) vs code 32/~444 |

---

## Verified Findings (Contradicting Older Reports)

1. **`gaps-audit.md` claim: "ZERO controllers use permission middleware"** — FALSE. `routes/web.php` uses `permission:` on patient, appointment, IPD, triage/vitals, admin roles, and SHA module groups; `routes/api.php` uses 26 `permission:` middleware calls.
2. **`gaps-audit.md` claim: many modules MISSING (maternity, ICU, anaesthesia, HIV)** — CONTRADICTED. Controllers, models, migrations (2026_09_28), and G0xx tests exist for these modules.
3. **`FEATURE_CONFIRMATION_REPORT.md` claim: 22 roles / 198 permissions** — STALE. Code seeds 32 roles and ~444 permissions.
4. **`docs/ROLE-PERMISSION-MATRIX.md` claim: 21 roles / 93 permissions** — STALE. Regenerated matrices supersede it.
5. **`STUBS_AND_PLACEHOLDERS_REPORT.md` claim: Pharmacy controller is stub** — STALE. `PharmacyController` has full stats + medicines CRUD (238 lines).

---

## Authorization Architecture (As Found)

```
User → Spatie Role(s) → Permissions (human-readable names)
                              ↓
                    permission:/role:/module: route middleware
                              ↓
                    Blade @can/@canany sidebar visibility
                              ↓
                    AuditLog::log (manual controller calls)
```

- Package: Spatie Laravel Permission (runtime models: `Spatie\Permission\Models\Role|Permission`)
- Legacy custom `App\Models\Role` / `Permission` / `role_user` / `permission_role` tables coexist (schema debt)
- Sidebar: Blade partial + `@can` + `Module::isEnabled` — architecture already matches spec §38
- Enforcement: primarily route middleware; policies defined but were unregistered
- Scope: `branch_id` / `facility_id` / `department_id` columns exist on many models; **not enforced at query level**

---

## Permission Naming Convention (As Found)

Existing convention is **human-readable phrases**, not dot-notation:

- `view patients`, `add patients`, `manage mortuary records`, `dispense medicines`
- Spec examples (`patient.view`, `lab.result.validate`) are **not** the project convention
- Per spec §34: *"Use the project's existing naming convention if one already exists."*
- **Decision:** Do not rename ~444 permissions to dot-notation; that would break sidebar, middleware, seeders, and tests without security benefit

---

## Implementation Decisions (This Session)

| Item | Decision | Rationale |
|---|---|---|
| Register 4 policies | Implemented | Policies existed unused; registration is low-risk P0 |
| Protect high-risk routes | Implemented | Auth-only access to mortuary/CSSD/security/HIV/MH/etc. is a verified security defect |
| Apply `Auditable` trait | Implemented on key clinical models | Trait existed unused; spec §45 requires audit of clinical record changes |
| Rename permissions to dot-notation | **Not done** | Would break existing system; spec forbids unnecessary redesign |
| Replace Spatie | **Not done** | Spec §33: do not replace functioning authorization package |
| Claim SHA/DHA live compliance | **Not done** | No credentials in `.env`; marked BLOCKED_EXTERNAL_DEPENDENCY |
| Implement full FHIR mapping layer | Documented as gap | Requires Kenya IG verification; not fabricated |

---

## Counts (Final)

| Category | Count |
|---|---|
| Total roles | 32 |
| Total permissions (unique, seeded) | ~444 |
| Total menu items (approx.) | ~180 across 13 groups |
| Existing (verified) | Majority of clinical + business modules |
| Partial | Policy registration (fixed), route protection (expanded), scope, FHIR layer, break-glass workflow, Patient 360 consolidation |
| Missing | Query-level scope enforcement; dedicated FHIR mapping layer; verified break-glass end-to-end workflow |
| Duplicate | Legacy roles/permissions tables vs Spatie tables; stale docs |
| Unknown | Live SHA/DHA API behavior (no credentials) |
| Blocked external dependencies | SHA (EHA) credentials; DHA credentials; facility FRN |
| Implemented this session | Policy registration, route permission middleware (high-risk groups), Auditable trait on clinical models, E2ETest RBAC alignment, 8 documentation deliverables |

---

## Test Environment (Updated 2026-10-02)

| Setting | Value |
|---|---|
| Database | MySQL/MariaDB 10.4.32 (XAMPP) |
| Host/Port | 127.0.0.1:3360 |
| Test database | duncohms_test |
| Config | phpunit.xml (DB_CONNECTION=mysql) |

Why MySQL: SQLite :memory: re-migrated 427 migrations per test process. MySQL persists schema across test classes; RefreshDatabase then uses transactions. Seeder was also rewritten with bulk inserts (0.56s vs minutes).

### Schema defects found by MySQL strict mode (fixed)

| Issue | Fix |
|---|---|
| trauma_assessments.pupils_left/right varchar(10) rejected "3mm reactive" | Widened to varchar(50) via migration 2026_10_02_000001 |
| icu_admissions.unit_type, sedation_scores.score_type varchar(10) | Widened to varchar(50) |
| pregnancies.blood_group/rh_factor varchar(10) | Widened to varchar(20) |

### Test bugs fixed (aligned with corrected RBAC / current app behavior)

| Test | Issue | Fix |
|---|---|---|
| E2ETest | Roleless user on now-protected routes | Seed RBAC + assign Super Admin |
| G037HivTest | Roleless user on HIV routes | Seed + assign Doctor |
| G040PublicHealthTest | Custom permissions only | Seed + assign Nurse/Doctor |
| G042MentalHealthSocialWorkTest | Custom permission only | Seed + assign MH Professional/Social Worker |
| G042AlliedModulesTest | Custom permission only | Seed + assign Dietitian/Doctor |
| G043MortuaryCompletionTest | Roleless user | Seed + assign Mortuary Attendant |
| G047CssdCompletionTest | Non-standard permission name | Seed + assign CSSD Technician |
| G050MaintenanceCompletionTest | Roleless user | Seed + assign Biomedical Engineer |
| G054SecurityCompletionTest | Roleless user on security routes | Seed + assign Security Officer |
| G079TelemedicineCommsTest | Roleless user | Seed + assign Telemedicine Doctor |
| G022AnaesthesiaTest | Roleless user; wrong middleware on anaesthesia routes | Fixed route middleware + assign Doctor |
| PatientManagementTest | Roleless user on patient routes | Seed + assign Hospital Admin |
| CrudCompleteTest | assertDatabaseHas used value as column name | Fixed to use actual column key |
| ChartReadinessTest | Roleless user on analytics | Seed + assign Super Admin |
| RegistrationTest | Expected immediate auth; app now uses OTP flow | Assert user created + redirect to OTP |

### Verified passing on MySQL (this session)

| Suite | Result |
|---|---|
| PermissionMiddlewareTest | 19/19 |
| RolesAndPermissionsTest | 11/11 |
| E2ETest | 13/13 |
| G005 Triage | pass |
| G011 Emergency | pass (after schema fix) |
| G014 Maternity | pass |
| G015 Neonatal | pass |
| G017 Inpatient | pass |
| G019 ICU | pass |
| G020 Theatre | pass |
| G022 Anaesthesia | 6/6 |
| G025 Lab | pass |
| G028 Radiology | pass |
| G031 Blood Bank | pass |
| G034 Pharmacy | pass |
| G037 HIV | 22/22 combined with G042 MH |
| G038 TB | pass |
| G039 Oncology | pass |
| G040 Public Health | 10/10 |
| G042 MH/Social | pass |
| G042 Allied | pass |
| G043 Mortuary | pass |
| G045 Ambulance | pass |
| G047 CSSD | pass |
| G050 Maintenance | 18/18 combined |
| G054 Security | 5/5 |
| G059 Billing | pass |
| G062 SHA | pass |
| G067 Finance | pass |
| G070 HR | pass |
| G075 Appointments | pass |
| G079 Telemedicine | pass |
| G086 Audit | pass |
| G089 Dashboard | pass |
| G093 API | pass |
| G095 Audit Columns | pass |
| PatientManagementTest | pass |
| CrudCompleteTest | pass |
| StoreInventoryTest | pass |
| ChartReadinessTest | pass |
| Auth suite | 18/18 |

PHPUnit deprecations (`@test` doc-comments) are warnings only — tests pass. Migrating to `#[Test]` attributes is optional cleanup.

---

## Final Principle (Spec Section 67)

Objective met: inspected the existing HMS, established what exists, identified genuine gaps, implemented only verified gaps, and documented uncertainty instead of guessing.

Existing functionality preserved. No fake SHA/DHA production claims. No invented FHIR profiles. No unnecessary permission renames or framework replacement.

---

## Gap Completion Pass (2026-10-02)

Implemented and verified via `tests/Feature/GapCompletionTest.php` (**17/17 pass**).

| Gap | Status | Implementation |
|---|---|---|
| Auth rate limiting | **Done** | `throttle:10,1` login/register; `throttle:5,1` password reset; social login throttled |
| Portal own-records | **Done** | Dependant link requires own patient or matching email/phone; audit denied attempts; messages use portal session not Auth::id(); markRead ownership check |
| Branch/facility scope | **Done (architecture)** | `users.branch_id` migration; `BelongsToFacility` global scope; `SetFacilityContext` middleware; scope allows null facility for single-site |
| Route protection sweep | **Done** | Theatre, TB, Oncology, IPC, Quality, Batch, OHS, HR, Billing, E-prescription, EHR, Laboratory, Reports/exports, insurance PDFs, backup download protected |
| Auditable on clinical models | **Done** | Prescription, LabRequest, RadiologyRequest, Invoice, IpdAdmission, ConsentForm, MrdFile, VaccinationRecord, MortuaryRecord, PatientDiagnosis (+ earlier set) |
| Patient 360 | **Done** | `Patient360Controller` + `/patients/{id}/360` + permission-filtered sections view |
| Break-glass workflow | **Done** | Controller, routes, views, sidebar link; store by any auth staff (audited); review gated; approve/reject; AuditLog entries |
| Void instead of hard-delete | **Done** | Prescription destroy -> status cancelled + audit; LabRequest destroy -> cancelled + audit; dispensed/completed blocked from delete |
| Export permission checks | **Done** | Report/export/PDF routes gated with export/report permissions |
| My Work dashboard | **Done** | `MyWorkService` + dashboard section (role-aware counts) |
| FHIR R4 mapping layer | **Done (generic R4)** | `App\Services\Fhir\FhirResourceMapper` Patient/DiagnosticReport/MedicationRequest; EHR controller uses it; **no Kenya IG claim** |
| Role inheritance | **Done (helper)** | `RoleComposer` composites ICU/Maternity/Theatre Nurse, Telemedicine Doctor from base + extras |

### Still blocked (cannot implement without external inputs)

| Item | Blocker |
|---|---|
| Live SHA | EHA_CLIENT_ID / SECRET / FACILITY_ID |
| Live DHA | DHA_CLIENT_ID / SECRET / FACILITY_ID |
| Kenya FHIR IG conformance | Official IG validation + terminology mapping |
| Multi-branch production rollout | Assign users.branch_id via HR/branch UI (schema ready) |

### Regression (this pass)

| Suite | Result |
|---|---|
| GapCompletionTest | 17/17 |
| SidebarRoleVisibilityTest | 17/17 |
| PermissionMiddlewareTest | 19/19 |
| RolesAndPermissionsTest | 11/11 |
| G034 Pharmacy + G011 Emergency + Auth | 29/29 |
