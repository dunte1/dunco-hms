# RBAC Test Matrix

Generated: 2026-10-01
Source: RolesAndPermissionsSeeder, SidebarPermissionsSeeder, routes/web.php, PermissionMiddlewareTest, RolesAndPermissionsTest

Permission middleware: permission:perm1|perm2 = OR (any one grants access).

---

## Existing Test Coverage

| Test | Verifies |
|---|---|
| RolesAndPermissionsTest | 32 roles exist; key permissions exist; role-permission assignments |
| PermissionMiddlewareTest | Unauthenticated redirect; Receptionist/Doctor patient access; no-permission 403; appointments by role; IPD by role; triage by role; admin roles protection; Super Admin access |
| G0xx module tests | Clinical workflows (triage, emergency, maternity, ICU, theatre, lab, pharmacy, HIV, etc.) |

---

## Role x Critical Route Matrix

Legend: Y = allowed (has permission), N = denied (403), P = protected this session

| Role | patients | appointments | ipd | queue | triage | admin/roles | mortuary | security | mental-health | hiv | cssd | telemedicine | ai/elliana |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Super Admin | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y |
| Hospital Admin | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y | Y |
| Doctor | Y | Y | Y | Y | Y | N | N | N | N | Y | N | Y | Y |
| Nurse | Y | Y | Y | Y | Y | N | N | N | N | N | N | N | Y |
| Receptionist | Y | Y | Y | Y | N | N | N | N | N | N | N | N | Y |
| Pharmacist | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Lab Technician | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Radiologist | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Accountant | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Case Handler | Y | N | N | N | N | N | N | N | N | N | N | N | Y |
| Ambulance Operator | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| HR Officer | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Patient | N | N | N | N | N | N | N | N | N | N | N | N | N |
| System Auditor | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Support Staff | Y* | N | N | N | N | N | N | N | N | N | N | N | Y |
| Telemedicine Doctor | Y | Y | Y | Y | Y | N | N | N | N | Y | N | Y | Y |
| Inventory Manager | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Procurement Officer | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| IT Support | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Marketing Manager | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| System AI Bot | N | N | N | N | N | N | N | N | N | N | N | N | N |
| Maternity Nurse | Y | Y | Y | Y | Y | N | N | N | N | N | N | N | Y |
| ICU Nurse | Y | Y | Y | Y | Y | N | N | N | N | N | N | N | Y |
| Theatre Nurse | Y | Y | Y | Y | Y | N | N | N | N | N | N | N | Y |
| CSSD Technician | N | N | N | N | N | N | N | N | N | N | Y | N | Y |
| Mortuary Attendant | N | N | N | N | N | N | Y | N | N | N | N | N | Y |
| Security Officer | N | N | N | N | N | N | N | Y | N | N | N | N | Y |
| Quality Officer | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Biomedical Engineer | N | N | N | N | N | N | N | N | N | N | N | N | Y |
| Dietitian | Y | N | Y | N | N | N | N | N | N | N | N | N | Y |
| Social Worker | Y | N | N | N | N | N | N | N | N | N | N | N | Y |
| Mental Health Professional | N | N | N | N | N | N | N | N | Y | N | N | N | Y |

*Support Staff: dashboard access confirmed by PermissionMiddlewareTest; clinical route access depends on assigned permissions.

---

## Permission Groups Used By Route Middleware (post-audit)

| Route group | Middleware permissions |
|---|---|
| Patients | view patients, add patients, edit patients |
| Appointments | create appointments, manage appointments |
| IPD | admit patients, manage admissions, view patients |
| Triage/Vitals | manage patient vitals, view patients |
| Admin roles | manage roles, manage permissions |
| SHA module | module:sha-shif |
| DHA module | module:dha-integration |
| Mortuary (new) | manage mortuary records, manage mortuary slots, manage body identifications, manage postmortems, issue death certificates |
| CSSD (new) | manage cssd instruments, manage cssd cycles, manage sterilizer runs, manage cssd issues, manage cssd returns, record sterility indicators |
| Security (new) | manage security incidents, manage lost found items, manage access events, manage visitor passes |
| Mental Health (new) | manage mh assessments, manage mh treatment plans, manage counselling sessions |
| Social Work (new) | manage social assessments, manage welfare waivers, manage discharge plans |
| HIV (new) | manage hts encounters, manage hiv care enrollments, manage art regimens, manage tb screens, manage cancer registrations |
| Telemedicine (new) | use telemedicine, manage telemedicine sessions |
| AI (new) | use ai assistant, manage ai suggestions |
| Consent/MRD (new) | manage record requests, upload documents, upload scanned documents, manage icd coding |
| Vaccination/Public Health (new) | manage immunization schedules, manage family planning visits, manage surveillance cases, manage outbreak events |
| Maintenance/Assets (new) | manage maintenance requests, manage work orders, manage calibrations, manage assets, transfer assets, dispose assets |
| RFID/IoT (new) | manage rfid tags, monitor iot sensors |

---

## Required Automated Tests (Acceptance)

1. Every role tested against: visible menus, hidden menus, direct URLs, API endpoints.
2. Unauthorized patient access blocked.
3. Unauthorized department/sensitive-record access blocked.
4. Scope enforced where facility/branch assigned.
5. Patient role sees only own records.
6. Changing role changes navigation without code changes.
7. Sidebar badges never expose unauthorized counts.

Existing suite covers items 1-3 partially via PermissionMiddlewareTest + RolesAndPermissionsTest + G0xx tests.
Items 4-7 remain open (see MISSING-FEATURES.md).
