# Security Controls

Generated: 2026-10-01
Source: code inspection of middleware, auth, audit, config, seeders, tests

---

## Authentication

| Control | Status | Evidence |
|---|---|---|
| Laravel auth (session) | Existing | routes/web.php auth middleware |
| Password hashing | Existing | Laravel default (bcrypt) via User model/factory |
| Email verification | Existing | dashboard uses auth + verified |
| Patient portal auth | Existing | PatientPortalController login/2FA methods |
| API tokens (Sanctum) | Existing | PermissionMiddlewareTest uses Bearer tokens |
| Session security | Existing (framework) | Laravel session config; deep audit of session hardening not completed |
| Rate limiting | Unknown | Not fully audited this session |
| Brute-force protection | Unknown | Not fully audited this session |

---

## Authorization

| Control | Status | Evidence |
|---|---|---|
| Role-based access | Existing | Spatie Permission, 32 roles |
| Permission-based access | Existing | ~444 permissions, human-readable names |
| Route middleware enforcement | Partial -> Expanded | permission:/role:/module: middleware; high-risk groups protected this session |
| API enforcement | Partial | 26 permission middleware calls in api.php |
| Policies | Partial -> Registered | PatientPolicy, LabRequestPolicy, InvoicePolicy, RadiologyRequestPolicy registered in AppServiceProvider |
| Gate definitions | Missing | No Gate::define found |
| Scope (branch/facility/department) | Missing at query level | Columns exist; no scopes/middleware |
| Patient own-records isolation | Missing/unverified | Must be audited on portal + API |
| Module enablement | Existing | CheckModule middleware + Module::isEnabled |
| Sidebar is not security | Documented | MENU-PERMISSION-MATRIX.md |

---

## Audit Logging

| Control | Status | Evidence |
|---|---|---|
| audit_logs table | Existing | migration 2025_10_20_019600 |
| AuditLog::log helper | Existing | app/Models/AuditLog.php |
| Manual controller audit calls | Existing | ~12 controllers |
| Auditable model trait | Partial -> Applied | Trait existed unused; applied to key clinical models this session |
| Spatie activitylog package | Installed unused | composer.json ^4.7; no LogsActivity traits found |
| Tamper resistance | Partial | DB-backed logs; no evidence of immutable storage/WORM |
| Editability by ordinary users | Should be denied | No UI to edit audit logs found; enforce in admin UI |
| Clinical audit columns | Existing | migration 2026_09_28_000010 |

Audited actions currently include (where controllers call AuditLog::log):
- Charges, discounts, payments, refunds
- Ipd admissions
- Settings changes
- Requisitions
- Store operations

Gap: not all clinical record changes audited until Auditable trait applied.

---

## Sensitive Data

| Control | Status | Evidence |
|---|---|---|
| Mental health permissions | Existing | manage mh assessments/plans/sessions |
| HIV/TB/Oncology permissions | Existing | domain-specific permissions in seeder |
| Social work permissions | Existing | manage social assessments/waivers/discharge plans |
| Route protection for sensitive modules | Expanded this session | Mental health, HIV, social work routes now permission-gated |
| Break-glass model | Partial | BreakGlassEvent model + permission exist |
| Break-glass full workflow | Partial/unverified | Reason -> temporary auth -> audit -> review flow not fully verified |
| Consent workflows | Existing | ConsentForm model + routes |
| Data export permissions | Partial | export reports/journals/ledgers exist |
| Encryption in transit | Existing (assumed HTTPS deployment) | Not re-verified in this audit |
| Encryption at rest | Unknown | Infra-level; not verified |
| Secret management | Config/env based | .env not committed as live secrets in audit sample; production secrets must be env-injected |
| Backup | Implemented | Spatie laravel-backup + BackupService; permission `manage backups`; optional archive encryption via `BACKUP_ARCHIVE_PASSWORD`; scheduled `backup:run` / `backup:clean` / `backup:monitor`; restore supports .sql and Spatie .zip archives |
| Data retention | Unknown | Not fully audited |

---

## Middleware Inventory

| Middleware | File | Check |
|---|---|---|
| RoleMiddleware | app/Http/Middleware/RoleMiddleware.php | hasRole -> 403 |
| PermissionMiddleware | app/Http/Middleware/PermissionMiddleware.php | can(permission) OR list -> 403 |
| CheckModule | app/Http/Middleware/CheckModule.php | Module::isEnabled -> 403 |
| SetLocaleFromSession | app/Http/Middleware/SetLocaleFromSession.php | Locale only |

Aliases registered in bootstrap/app.php.

---

## What Was Fixed This Session (Security)

1. **Policies registered** — previously defined but not bound in AppServiceProvider.
2. **High-risk routes permission-gated** — mortuary, CSSD, security, mental health, social work, HIV/TB/oncology, telemedicine, AI, consent/MRD, public health, maintenance/assets, RFID/IoT, nutrition.
3. **Auditable trait applied** to key clinical models for record-change audit trail.
4. **E2ETest aligned with RBAC** — tests no longer rely on roleless users accessing sensitive routes (that pattern encoded a security hole).

---

## Claims Not Made

- No claim of HIPAA/ISO 27001/Sh compliant certification
- No claim of live SHA/DHA compliance
- No claim of encryption-at-rest or rate-limiting completion without evidence
- Legal/compliance questions flagged for human/legal confirmation
