# Missing Features Register

Generated: 2026-10-01
Rule: Do not implement an item solely because it appears in the specification. First verify.

Statuses: Existing | Partial | Missing | Duplicate | Unknown | Blocked

| Feature | Status | Evidence | Priority | Dependency | Action |
|---|---|---|---|---|---|
| 32 roles seeded | Existing | RolesAndPermissionsSeeder; RolesAndPermissionsTest | P0 | None | Preserve |
| ~444 permissions seeded | Existing | Main seeder 344 unique + SidebarPermissionsSeeder ~100 | P0 | None | Preserve naming convention |
| Permission-driven sidebar | Existing | sidebar.blade.php @can/@canany (49+) | P0 | None | Preserve |
| Spatie Permission package | Existing | composer.json ^6.21; User HasRoles trait | P0 | None | Do not replace |
| Route permission middleware | Partial | 8 hits pre-audit in web.php; expanded this session | P0 | None | Continue protecting high-risk groups |
| Policy registration | Partial -> Implemented | 4 policies existed unregistered; registered in AppServiceProvider | P0 | None | Adopt authorize() in controllers over time |
| Auditable trait on clinical models | Partial -> Implemented | Trait existed unused; applied to key models | P0 | audit_logs table | Monitor log volume |
| Branch/facility scope enforcement | Missing | Columns exist on ~31 models; no query scopes/middleware | P0 | Multi-branch product decision | Design scope model before enforcing |
| Patient 360 consolidated view | Partial | Many patient views; no single confirmed 360 route | P1 | Authorization per section | Audit patient views; consolidate only if verified gap |
| Break-glass workflow end-to-end | Partial | BreakGlassEvent model + manage break glass events permission | P1 | Audit + security review workflow | Verify full workflow; do not treat as normal access |
| Mental health route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | MH permissions in seeder | Test Mental Health Professional access |
| HIV/TB/Oncology route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | program permissions | Test domain role access |
| Social work route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | social permissions | Test Social Worker access |
| Mortuary route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | mortuary permissions | Test Mortuary Attendant access |
| CSSD route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | cssd permissions | Test CSSD Technician access |
| Security route protection | Partial -> Implemented | Routes existed auth-only; permission middleware added | P0 | security permissions | Test Security Officer access |
| SHA live integration | Blocked | ShaService + config/eha.php exist; no EHA_* credentials in .env | P4 | DHA facility registration + client credentials | Mark BLOCKED_EXTERNAL_DEPENDENCY; do not fake |
| SHA claims workflow UI/models | Existing | ShaMember, claims, tariffs, preauth, remittance controllers | P2 | Live credentials for E2E | Keep local fallbacks labeled |
| DHA live integration | Blocked | DhaService + config/dha.php exist; no DHA_* credentials in .env | P4 | DHA developer registration | Mark BLOCKED_EXTERNAL_DEPENDENCY |
| FHIR mapping layer | Partial | EhrIntegrationController fhirConfig/sendFhirResource only | P4 | Kenya FHIR IG verification | Do not invent profiles; design dedicated layer |
| ePrescription Kenya IG alignment | Partial | EPrescriptionController + templates exist | P4 | Official IG v0.1.0 (draft) review | Audit against guide; do not claim final conformance |
| Permission dot-notation rename | Duplicate/Not required | Project uses human-readable phrases | Low | Would break sidebar/middleware/tests | Do not rename |
| Legacy roles/permissions tables | Duplicate | Custom tables coexist with Spatie tables | Medium | Migration risk | Document; consolidate only with safe migration plan |
| Stale docs (ROLE-PERMISSION-MATRIX, gaps-audit) | Duplicate | Contradict current code | Medium | None | Superseded by new docs this session |
| Patient role own-records enforcement | Missing | No verified route-level own-records isolation | P0 | Portal + API design | Audit PatientPortalController + API |
| Sensitive data segmentation (MH, HIV) | Partial | Permissions exist; route protection added | P1 | Policy + scope | Verify role assignment completeness |
| Export permissions separate from view | Partial | export reports, export journals, export ledgers exist | P1 | None | Audit UI export buttons vs permissions |
| Role inheritance composition | Missing | Spatie roles are flat; no parent-role composition | P2 | Architecture decision | Optional; current assignments acceptable |
| Scope-aware My Work dashboards | Missing | No verified role-aware My Work section | P2 | Permission + query design | Implement only after scope model |
| AI action audit trail | Partial | AI models/controllers exist; full audit of AI actions unverified | P2 | AuditLog integration | Log AI generate/accept/reject events |
| Integration health checks | Partial | Integrations UI exists; health check depth unverified | P2 | Credentials for live status | Avoid fake success states |
| Backup/disaster recovery | Implemented | Spatie laravel-backup wired via BackupService; Settings UI create/list/download/verify/delete/restore; scheduled run/clean/monitor; optional encryption | P3 | Set `BACKUP_ARCHIVE_PASSWORD` + off-server copies | Periodically test restore from an off-server archive |
| Rate limiting / brute force | Unknown | Not fully audited this session | P1 | Middleware config | Audit auth + API throttle config |
| Encryption at rest | Unknown | Not fully audited this session | P1 | Infra | Verify DB/disk encryption posture |
| DPIA / data protection docs | Missing | No DPIA artefacts found in docs/ | P4 | Legal/compliance | Flag for human/legal review |

---

## Implemented This Session (Verified Gaps Only)

1. Registered PatientPolicy, LabRequestPolicy, InvoicePolicy, RadiologyRequestPolicy in AppServiceProvider.
2. Added permission middleware to high-risk route groups (mortuary, CSSD, security, mental health, social work, HIV/TB/oncology, telemedicine, AI, consent/MRD, public health, maintenance/assets, RFID/IoT, nutrition).
3. Applied Auditable trait to key clinical models for audit trail.
4. Aligned E2ETest with RBAC (seed roles + assign Super Admin) so tests match corrected security model.
5. Generated 8 documentation deliverables.

---

## Not Implemented (Intentionally)

| Item | Reason |
|---|---|
| SHA/DHA live calls | No credentials; spec forbids fabricating production behavior |
| Full FHIR resource mapping | Requires official Kenya IG; do not invent profiles |
| Permission rename to dot-notation | Breaks existing system; spec forbids unnecessary redesign |
| Replace Spatie | Functioning package; spec forbids replacement without necessity |
| Scope middleware | Requires product decision on multi-branch model |
| Implement every spec checklist item blindly | Spec section 59 and 67: verify first |
