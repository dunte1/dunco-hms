# Menu Permission Matrix

Generated: 2026-10-01
Source: resources/views/partials/sidebar.blade.php (49+ @can/@canany directives)

Sidebar is permission-driven (Blade @can), NOT hard-coded by role. This matches specification section 38.

Module gating also applies via Module::isEnabled (e.g. patients-management).

---

## Top-Level Menu Groups (13)

| Menu | Primary permission gate(s) | Notes |
|---|---|---|
| Dashboard | view dashboard analytics, view analytics | |
| Hospital Management | view patients, add patients, edit patients, delete patients, view doctors, manage staff profiles, manage nurses, manage ambulances | Parent hidden if no children visible |
| Communication | create appointments, manage appointments, view appointments, manage queue | Appointments, Queue, Enquiries, Notices, Mail/SMS, Reminders |
| Clinical | view prescriptions, manage case handlers, generate operation reports, manage bed assignments | Prescriptions, cases, diagnosis, vitals, OT, beds |
| Diagnostics | manage test categories, add test requests, enter test results, manage blood bank | Lab, Pathology, Radiology, Blood Bank |
| Pharmacy and Inventory | view prescriptions, dispense medicines, manage medicine inventory, manage packages | Medicines, Inventory, Packages |
| Finance | create invoices, edit invoices, add payments, view payment reports | Billing, payments, accounts, expenses, income, insurance |
| HR | manage staff profiles, view attendance, manage payrolls | Employees, payroll, attendance, leave, recruitment, shifts |
| Reports | generate patient reports, generate billing reports, view dashboard analytics, generate birth reports, generate death reports | Clinical/financial/operational reports |
| Settings | manage system settings, manage roles, manage permissions, view audit logs, manage backups | Hospital info, branches, users, roles, modules, backup, audit, API keys |
| CMS | manage homepage, manage services, manage doctors listing, manage marketing | Frontend CMS |
| Marketing | manage marketing, create marketing posts, manage campaigns, manage social accounts | Marketing suite |
| AI and Integrations | use ai assistant, manage ai suggestions, view analytics, use telemedicine, manage rfid tags, monitor iot sensors | AI, integrations, RFID, IoT |

---

## Submenu Permission Map (High Level)

| Submenu | Permission(s) |
|---|---|
| All Patients | view patients, add patients |
| Doctors / Staff | view doctors, manage staff profiles |
| Nurses | manage nurses |
| Ambulance | manage ambulances |
| Appointments | create appointments, manage appointments, view appointments |
| Queue | manage queue, view appointments, create appointments |
| Prescriptions | view prescriptions |
| Case Handlers | manage case handlers |
| Operation Reports | generate operation reports |
| Bed Management | manage bed assignments |
| Pathology / Lab | manage test categories, add test requests, enter test results, view test results |
| Radiology | manage test categories, add test requests, approve radiology reports |
| Blood Bank | manage blood bank |
| Medicines / Inventory | view prescriptions, dispense medicines, manage medicine inventory, manage packages |
| Billing / Payments | create invoices, edit invoices, add payments, view payment reports |
| HR | manage staff profiles, view attendance, manage payrolls |
| Reports | generate billing reports, generate birth reports, generate death reports |
| Settings | manage system settings, manage roles, manage permissions, view audit logs, manage backups |
| CMS | manage homepage, manage services, manage doctors listing, manage marketing |
| Marketing | manage marketing, create marketing posts, manage campaigns, manage social accounts |
| AI / Telemedicine / RFID / IoT | use ai assistant, use telemedicine, manage rfid tags, monitor iot sensors |

---

## Empty Parent Menu Rule

Sidebar structure already supports hiding parents when children are unauthorized (nested @can blocks). Verify visually: user with only laboratory permission should see Diagnostics > Laboratory only.

---

## Sidebar Badges

Badges must only render when the user has permission to see underlying records. Current sidebar uses @can gates around badge-bearing sections. No badge should query unauthorized data.

---

## No Menu Without Authorization Rule

All menus in sidebar.blade.php are gated by @can/@canany or Module::isEnabled except public site pages (outside HMS sidebar).

| Menu item exists without permission rule? | Status |
|---|---|
| HMS sidebar items | No — all gated |
| Public site (/, /blog, etc.) | Public by design |
| Patient portal | Separate auth (patient portal login) |

---

## Acceptance Criteria (Spec section 61)

| # | Criterion | Status |
|---|---|---|
| 1 | Sidebar generated from effective permissions | Met (@can on permissions) |
| 2 | Unauthorized parent menus disappear | Met (nested structure) |
| 3 | Unauthorized children disappear | Met |
| 4 | Direct URLs protected | Partial (expanded this session) |
| 5 | APIs protected | Partial (api.php has 26 permission middleware calls) |
| 6 | Changing role changes navigation without hard-coded role checks | Met |
| 7 | Permission changes take effect without code changes | Met (Spatie cache + @can) |
| 8 | Scope respected | Not met (no query-level scope) |
| 9 | Patient role only sees own data | Not verified at route level |
| 10 | Sensitive modules require appropriate permissions | Partial (mental health, HIV routes now protected) |
| 11 | Sidebar badges respect authorization | Met (gated sections) |
| 12 | Mobile navigation follows same rules | Uses same sidebar partial |
| 13 | Search/navigation does not expose unauthorized resources | Not fully verified |

---

## Navigation Is Not Security (Spec section 62)

Sidebar hiding is UX only. Enforcement points:
- Route middleware: permission:/role:/module:
- API middleware on api.php
- Server-side checks in controllers for sensitive operations (partially present)
- Future: policy authorize() calls (policies now registered)
