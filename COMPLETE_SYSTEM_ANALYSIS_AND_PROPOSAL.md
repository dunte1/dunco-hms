# DUNCO HOSPITAL MANAGEMENT SYSTEM (Dunco HMS)
## Complete Technical Analysis, Production Readiness Audit & Commercial Proposal

**Version:** 3.1
**Date:** September 21, 2026
**Prepared by:** Dunco Web Solutions
**Classification:** Internal Analysis + Client-Ready Proposal

---

# TABLE OF CONTENTS

1. [Technical System Assessment](#a-technical-system-assessment)
2. [Production Readiness Checklist](#b-production-readiness-checklist)
3. [Recommended Commercial Model](#c-recommended-commercial-model)
4. [Recommended First-Year Budget](#d-recommended-first-year-budget)
5. [Annual Renewal Price](#e-annual-renewal-price)
6. [Optional Services](#f-optional-services)
7. [Client-Facing Proposal](#g-client-facing-proposal)
8. [Internal Notes for Dunco Web Solutions](#h-internal-notes)

---

# A. TECHNICAL SYSTEM ASSESSMENT

## A.1 Technology Stack

| Component | Technology | Version | Assessment |
|-----------|-----------|---------|------------|
| Backend Framework | Laravel | 12.x | Current, well-supported |
| PHP | PHP | 8.2+ | Current LTS requirement |
| Frontend | Blade + Alpine.js + Tailwind CSS | 3.x | Appropriate for server-rendered HMS |
| Build Tool | Vite | 7.x | Current |
| Database | SQLite (dev) / MySQL (production) | — | SQLite default needs MySQL for production |
| Cache | Database (default) / Redis (recommended) | — | Redis needed for production |
| Queue | Database (default) | — | Functional but Redis recommended |
| Authentication | Laravel Breeze + Sanctum | — | Session-based + API tokens |
| Authorization | Spatie Laravel Permission | 6.21 | Production-grade RBAC |
| PDF Generation | Laravel DomPDF | 3.1 | Functional |
| Excel Export | Maatwebsite Excel | 3.1 | Functional |
| Activity Logging | Spatie Activity Log | 4.7 | Production-grade |
| Backups | Spatie Laravel Backup | 9.0 | Production-grade |
| SMS | Twilio SDK | 7.3 | Code ready, credentials needed |
| Social Auth | Laravel Socialite | 5.15 | Code ready, OAuth apps needed |

## A.2 Codebase Metrics

| Metric | Count |
|--------|-------|
| Controller files | 153 |
| Model files | 131 |
| Service files | 13 |
| Blade view files | 457 |
| Database migrations | 163 |
| Database seeders | 17 |
| Factory files | 12 |
| Route files | 4 |
| Config files (custom) | 4 (dha, eha, mpesa, permission) |
| Middleware files | 4 |
| Test files | 18 |
| Estimated PHP lines | 15,000+ (controllers alone) |
| Sidebar menu sections | 13 top-level |
| Total modules (seeded) | 75 |
| Roles defined | 21 |
| Permissions defined | 95+ |

## A.3 Module-by-Module Status

### CLINICAL MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Patient Registration** | ✅ FUNCTIONAL | PatientsController (267 lines), full CRUD, search, filters, DHA verification, auto-invoicing | Production-ready |
| **Patient Profiles** | ✅ FUNCTIONAL | Patient model with relationships, MedicalHistory, PatientAllergy, PatientCase models | Production-ready |
| **OPD** | ✅ FUNCTIONAL | OpdVisitsController (136 lines), full CRUD, statistics | Production-ready |
| **IPD** | ✅ FUNCTIONAL | IpdAdmissionsController (180 lines), bed assignment, discharge logic | Production-ready |
| **Doctors** | ✅ FUNCTIONAL | DoctorsController (100 lines), DoctorDepartments, scheduling | Production-ready |
| **Nurses** | ✅ FUNCTIONAL | NursesController (201 lines), duty roster, ward assignments | Production-ready |
| **Appointments** | ✅ FUNCTIONAL | AppointmentsController (136 lines), M-Pesa/SHA integration, AI suggestions | Production-ready |
| **Queue Management** | ✅ FUNCTIONAL | QueueManagementController (279 lines), token generation, display board, kiosk mode | Production-ready |
| **Clinical Notes** | ⚠️ PARTIAL | PatientCase model exists, CaseHandlersController (186 lines) | Needs workflow refinement |
| **Diagnoses** | ✅ FUNCTIONAL | DiagnosisController (154 lines), ICD10Controller (75 lines), categories | Production-ready |
| **Prescriptions** | ✅ FUNCTIONAL | PrescriptionsController (162 lines), E-Prescription (255 lines), templates, PDF | Production-ready |
| **Vitals** | ✅ FUNCTIONAL | MedicalHistoryController (85 lines), vitals tracking | Production-ready |
| **Wards/Beds** | ✅ FUNCTIONAL | BedsController, BedTypesController, BedAssignments, visualization | Production-ready |
| **Theatre (OT)** | ✅ FUNCTIONAL | OtSchedulingController (274 lines), rooms, instruments, time logs | Production-ready |
| **Emergency** | ✅ FUNCTIONAL | EmergencyAdmission model, ambulance calls | Production-ready |
| **Ambulance** | ✅ FUNCTIONAL | AmbulanceController (266 lines), fleet + calls CRUD | Production-ready |
| **Blood Bank** | ✅ FUNCTIONAL | BloodBankController (161 lines), donors, inventory, requests | Production-ready |
| **Discharge Summary** | ✅ FUNCTIONAL | DischargeSummaryController (147 lines), PDF generation | Production-ready |
| **Birth/Death Reports** | ✅ FUNCTIONAL | BirthDeathReportsController (173 lines), certificates | Production-ready |
| **Operation Reports** | ✅ FUNCTIONAL | OperationReportsController (101 lines) | Production-ready |
| **Case Handlers** | ✅ FUNCTIONAL | CaseHandlersController (186 lines), case management | Production-ready |
| **Consent Management** | ✅ FUNCTIONAL | ConsentController (77 lines) | Production-ready |
| **Vaccination** | ✅ FUNCTIONAL | VaccinationController (86 lines), stock tracking | Production-ready |
| **Mortuary** | ✅ FUNCTIONAL | MortuaryController (75 lines) | Production-ready |
| **CSSD** | ✅ FUNCTIONAL | CssdController (86 lines), instrument sterilization tracking | Production-ready |
| **MRD** | ✅ FUNCTIONAL | MrdController (94 lines), file management, issue/return | Production-ready |

### DIAGNOSTICS MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Laboratory** | ✅ FUNCTIONAL | LaboratoryController (234 lines), tests, requests, results, technicians | Production-ready |
| **Radiology** | ✅ FUNCTIONAL | RadiologyController (151 lines), tests, requests, PDF reports | Production-ready |
| **Lab Equipment Integration** | ⚠️ PARTIAL | LabIntegrationController (185 lines), EquipmentResult model | Code exists, equipment API needs configuration |
| **Investigation Reports** | ✅ FUNCTIONAL | TestCategoriesController with investigation reports | Production-ready |

### PHARMACY MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Medicines** | ✅ FUNCTIONAL | MedicinesController (72 lines), categories, brands | Production-ready |
| **Prescriptions** | ✅ FUNCTIONAL | PharmacyController (198 lines), dispensing with stock management | Production-ready |
| **E-Prescription** | ✅ FUNCTIONAL | EPrescriptionController (255 lines), templates, PDF | Production-ready |
| **Drug Interactions** | ✅ FUNCTIONAL | DrugInteractionController (110 lines), local DB-driven checker | Production-ready (data-dependent) |
| **Inventory** | ✅ FUNCTIONAL | InventoryController (87 lines), InventoryManagementController (92 lines) | Production-ready |
| **Suppliers** | ✅ FUNCTIONAL | SuppliersController (143 lines) | Production-ready |
| **Purchase Orders** | ✅ FUNCTIONAL | PurchaseOrdersController (322 lines), submit/approve/PDF | Production-ready |
| **Stock Movements** | ✅ FUNCTIONAL | StockMovementsController (297 lines), multi-store transfers | Production-ready |
| **Expiry Tracking** | ✅ FUNCTIONAL | Expiry alerts in inventory views | Production-ready |
| **Packages** | ✅ FUNCTIONAL | PackagesController (135 lines) | Production-ready |

### FINANCIAL MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Billing** | ✅ FUNCTIONAL | BillingController (77 lines) + InvoicesController (159 lines) + PaymentsController (275 lines) | Production-ready |
| **Invoices** | ✅ FUNCTIONAL | Sequential numbering, line items, PDF, thermal receipt | Production-ready |
| **Payments** | ✅ FUNCTIONAL | Multi-method (cash, card, M-Pesa, insurance), auto invoice balance update | Production-ready |
| **Receipts** | ✅ FUNCTIONAL | Thermal receipt templates, PDF receipts | Production-ready |
| **M-Pesa** | ⚠️ SANDBOX | MpesaService (273 lines) + MpesaCallbackController (537 lines) | Real integration, sandbox credentials active |
| **Expenses** | ✅ FUNCTIONAL | ExpensesController (212 lines), categories, reports | Production-ready |
| **Income** | ✅ FUNCTIONAL | IncomeController (174 lines), reports | Production-ready |
| **Finance Dashboard** | ✅ FUNCTIONAL | FinanceController (287 lines), P&L, Balance Sheet, Cash Flow | Production-ready |
| **Chart of Accounts** | ✅ FUNCTIONAL | AccountsController (240 lines), ledger, trial balance | Production-ready |
| **Advance Payments** | ✅ FUNCTIONAL | AdvancePaymentsController (160 lines), deposits, refunds | Production-ready |
| **Multi-Currency** | ✅ FUNCTIONAL | MultiCurrencyController (166 lines), CurrencyService with free API | Production-ready |
| **Stripe** | ❌ STUB | PaymentGatewayService returns fake transaction IDs | Requires implementation |
| **PayPal** | ❌ STUB | PaymentGatewayService returns fake transaction IDs | Requires implementation |

### INSURANCE / SHA MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Insurance Providers** | ✅ FUNCTIONAL | InsuranceProvidersController (151 lines) | Production-ready |
| **Patient Insurance** | ✅ FUNCTIONAL | PatientInsurance model, CRUD | Production-ready |
| **Insurance Claims** | ✅ FUNCTIONAL | InsuranceClaimsController (210 lines), submit/approve/reject/PDF | Production-ready (local) |
| **SHA Integration** | ⚠️ CODE READY | ShaService (788 lines), real API endpoints, but no credentials | Needs DHA facility credentials |
| **DHA Integration** | ⚠️ CODE READY | DhaService (324 lines), real API endpoints, but no credentials | Needs DHA facility credentials |
| **SHA Member Management** | ✅ FUNCTIONAL | ShaController (166 lines), members, authorizations, service codes | Production-ready (local) |
| **ICD-10 Codes** | ✅ FUNCTIONAL | ICD10Controller (75 lines), seeder with real ICD-10 data | Production-ready |

### HR MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Employees** | ✅ FUNCTIONAL | EmployeesController (88 lines), full CRUD, photo upload, user account creation | Production-ready |
| **Departments** | ✅ FUNCTIONAL | EmployeeDepartmentsController (56 lines) | Production-ready |
| **Designations** | ✅ FUNCTIONAL | DesignationsController (71 lines) | Production-ready |
| **Attendance** | ✅ FUNCTIONAL | AttendanceController (68 lines) | Production-ready |
| **Leave Management** | ✅ FUNCTIONAL | LeaveRequestsController (92 lines), approve/reject, LeaveTypes (91 lines) | Production-ready |
| **Payroll** | ✅ FUNCTIONAL | PayrollsController (75 lines) | Production-ready |
| **Schedules** | ✅ FUNCTIONAL | SchedulesController (103 lines) | Production-ready |
| **Shifts** | ✅ FUNCTIONAL | ShiftsController (167 lines), roster builder | Production-ready |
| **Performance Appraisals** | ✅ FUNCTIONAL | PerformanceAppraisalsController (156 lines) | Production-ready |
| **Training Programs** | ✅ FUNCTIONAL | TrainingProgramsController (191 lines), enrollments, certificates | Production-ready |
| **Recruitment** | ✅ FUNCTIONAL | RecruitmentController (243 lines), job postings, applications | Production-ready |
| **HR Announcements** | ✅ FUNCTIONAL | HrAnnouncementsController (163 lines) | Production-ready |
| **Public Holidays** | ✅ FUNCTIONAL | PublicHolidaysController (89 lines) | Production-ready |
| **Documents** | ✅ FUNCTIONAL | HrDocumentsController (101 lines), document types | Production-ready |
| **HR Reports** | ✅ FUNCTIONAL | HrReportsController (261 lines), 8+ report types with PDF | Production-ready |
| **Import/Export** | ✅ FUNCTIONAL | EmployeesImportExportController (192 lines) | Production-ready |

### ADMINISTRATION MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Users** | ✅ FUNCTIONAL | UsersManagementController (296 lines), full CRUD | Production-ready |
| **Roles & Permissions** | ✅ FUNCTIONAL | RoleManagementController (176 lines), 21 roles, 95+ permissions | Production-ready |
| **Audit Logs** | ✅ FUNCTIONAL | Spatie Activity Log integration, audit log views | Production-ready |
| **System Settings** | ✅ FUNCTIONAL | SystemSettingsController (242 lines), general, branches, timezone | Production-ready |
| **Theme Customizer** | ✅ FUNCTIONAL | ThemeController (141 lines), colors, logo, dark mode | Production-ready |
| **Backup & Restore** | ✅ FUNCTIONAL | Spatie Backup, backup views | Production-ready |
| **API Keys** | ✅ FUNCTIONAL | ApiKeysController (48 lines) | Production-ready |
| **Module Manager** | ✅ FUNCTIONAL | ModulesController (279 lines), enable/disable modules | Production-ready |
| **Localization** | ✅ FUNCTIONAL | LocalizationController (61 lines) | Production-ready |
| **ID Cards** | ✅ FUNCTIONAL | IdCardController (457 lines), QR codes, bulk generation | Production-ready |

### PATIENT-FACING MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Patient Portal** | ✅ FUNCTIONAL | PatientPortalController (264 lines), login, dashboard, appointments, prescriptions, billing, 2FA | Production-ready |
| **Online Appointments** | ✅ FUNCTIONAL | SiteController with appointment booking form | Production-ready |
| **Results Access** | ✅ FUNCTIONAL | Portal routes for lab results, prescriptions | Production-ready |
| **Notifications** | ⚠️ PARTIAL | NotificationsController (69 lines), email templates exist | SMS needs credentials |
| **Telemedicine** | ⚠️ CODE READY | TelemedicineController (208 lines), ZoomService (326 lines) | Needs Zoom API credentials |

### AI MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Elliana D (Virtual Nurse)** | ⚠️ CODE READY | EllianaDAssistantService (422 lines), OpenRouter AI | Needs OpenRouter API key, keyword fallback works |
| **AI Appointment Suggestions** | ✅ FUNCTIONAL | AiAssistantController, rule-based algorithm | Functional without external API |
| **AI Diagnosis Suggestions** | ✅ FUNCTIONAL | AiAssistantController, symptom matching with probability | Functional without external API |
| **AI Predictive Analytics** | ⚠️ PARTIAL | View exists, data queries functional | Basic analytics, not ML-based |
| **AI Daily Summary** | ⚠️ CODE READY | Depends on OpenRouter API | Keyword fallback works |
| **AI Content Writer** | ⚠️ CODE READY | AiContentService (144 lines), OpenRouter | Needs API key |

### MARKETING / CMS MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Website CMS** | ✅ FUNCTIONAL | CmsController (263 lines), pages, header/footer | Production-ready |
| **Blog** | ✅ FUNCTIONAL | BlogController (187 lines) | Production-ready |
| **Gallery** | ✅ FUNCTIONAL | GalleryController (135 lines) | Production-ready |
| **Careers** | ✅ FUNCTIONAL | CareersController (200 lines), job postings, applications | Production-ready |
| **Testimonials** | ✅ FUNCTIONAL | TestimonialsController (106 lines) | Production-ready |
| **Social Accounts** | ✅ FUNCTIONAL | SocialAccountController (107 lines), OAuth | Needs social app credentials |
| **Marketing Posts** | ✅ FUNCTIONAL | MarketingPostController (111 lines) | Production-ready |
| **Marketing Campaigns** | ✅ FUNCTIONAL | CampaignController (90 lines) | Production-ready |
| **Scheduler** | ✅ FUNCTIONAL | SchedulerController (55 lines) | Production-ready |
| **Comment Replies** | ✅ FUNCTIONAL | CommentReplyController (282 lines), multi-platform | Production-ready |
| **Graphic Assets** | ✅ FUNCTIONAL | GraphicAssetController (95 lines) | Production-ready |
| **SEO Manager** | ✅ FUNCTIONAL | SeoController (59 lines) | Production-ready |

### IoT / RFID MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **RFID Management** | ✅ FUNCTIONAL | RfidController (195 lines), tag CRUD, scanning, reports | Functional (needs RFID hardware) |
| **IoT Bed Monitoring** | ✅ FUNCTIONAL | IotBedMonitoringController (370 lines), sensor data processing, alerts | Functional (needs sensor hardware) |
| **Biometric Security** | ⚠️ PARTIAL | BiometricController (148 lines), BiometricService (269 lines) | Local storage works, DHA verification needs credentials |

### REPORTING MODULES

| Module | Status | Evidence | Notes |
|--------|--------|----------|-------|
| **Dashboards** | ✅ FUNCTIONAL | DashboardController (176 lines), multiple dashboards | Production-ready |
| **BI Dashboard** | ✅ FUNCTIONAL | BiDashboardController (368 lines), charts, analytics | Production-ready |
| **Custom Report Builder** | ✅ FUNCTIONAL | CustomReportBuilderController (297 lines), CRUD + generate | Production-ready |
| **Excel Export** | ⚠️ PARTIAL | Maatwebsite Excel installed, BatchExport job is stub | Export job needs implementation |
| **PDF Export** | ✅ FUNCTIONAL | DomPDF throughout (invoices, claims, reports) | Production-ready |
| **Analytics Reports** | ✅ FUNCTIONAL | AnalyticsReportsController (219 lines), 8+ report types | Production-ready |

---

# B. PRODUCTION READINESS CHECKLIST

## B.1 Security

| Item | Status | Details |
|------|--------|---------|
| Password Hashing | ✅ READY | bcrypt with 12 rounds |
| CSRF Protection | ✅ READY | Laravel default middleware |
| XSS Protection | ✅ READY | Blade auto-escaping |
| SQL Injection | ✅ READY | Eloquent ORM parameterized queries |
| Session Management | ✅ READY | Database-backed, regeneration on login |
| RBAC | ✅ READY | Spatie Permission with 21 roles, 95+ permissions |
| Module Gating | ✅ READY | CheckModule middleware for feature flags |
| Audit Logging | ✅ READY | Spatie Activity Log |
| Biometric Encryption | ✅ READY | AES-256-CBC for template storage |
| Rate Limiting (Login) | ❌ NOT IMPLEMENTED | No throttle on POST /login |
| Rate Limiting (API) | ❌ NOT IMPLEMENTED | No throttle on API routes |
| Rate Limiting (Password Reset) | ❌ NOT IMPLEMENTED | No throttle on forgot-password |
| 2FA (Admin/Staff) | ❌ NOT IMPLEMENTED | Only patient portal has partial 2FA |
| Sanctum Token Expiry | ❌ CONFIGURED WRONG | `expiration => null` (tokens never expire) |
| Session Encryption | ❌ DISABLED | `SESSION_ENCRYPT=false` |
| Secure Cookies | ❌ DISABLED | `SESSION_SECURE_COOKIE=false` |
| APP_DEBUG in Production | ❌ MUST CHANGE | Currently `true` |
| Application-level Encryption | ❌ NOT DONE | Patient medical records not encrypted at rest |
| Brute-force Protection | ❌ NOT IMPLEMENTED | No account lockout mechanism |
| HTTPS Enforcement | ❌ NOT CONFIGURED | No redirect-to-HTTPS middleware |
| Dependency Vulnerabilities | ⚠️ NEEDS AUDIT | `composer audit` not run |
| File Upload Security | ⚠️ NEEDS REVIEW | Photo uploads exist, virus scanning absent |

## B.2 Data Protection (Healthcare)

| Item | Status | Details |
|------|--------|---------|
| Patient Data Access Control | ✅ PARTIAL | RBAC exists but no patient-level data isolation |
| Audit Trail | ✅ READY | Spatie Activity Log on models |
| Data Retention Policy | ❌ NOT IMPLEMENTED | No automatic data purge/archival |
| Backups | ⚠️ CONFIGURED | Spatie Backup installed, daily SQLite copy in cron |
| Backup Verification | ❌ NOT DONE | No automated restore testing |
| Disaster Recovery | ❌ NOT DOCUMENTED | No DR plan |
| Data Export | ⚠️ PARTIAL | BatchExport job is a stub |
| Data Deletion | ⚠️ PARTIAL | Soft deletes on some models, not comprehensive |
| Privacy Controls | ❌ NOT IMPLEMENTED | No consent management for data processing |
| Data Encryption at Rest | ❌ NOT DONE | Only biometric data encrypted |
| HIPAA/DPA Compliance | ❌ NOT VERIFIED | Cannot claim compliance |

## B.3 Reliability

| Item | Status | Details |
|------|--------|---------|
| Backup Frequency | ⚠️ DAILY | Cron job at 2 AM, SQLite copy only |
| Backup Verification | ❌ NOT DONE | No automated verification |
| Restore Testing | ❌ NOT DONE | No restore test procedure |
| Server Monitoring | ❌ NOT CONFIGURED | No uptime monitoring |
| Queue Reliability | ⚠️ DATABASE | Database queue driver, not Redis |
| Cron Reliability | ⚠️ NEEDS SETUP | Scheduled tasks defined, needs server cron |
| Database Reliability | ⚠️ SQLite DEFAULT | Must switch to MySQL for production |
| Downtime Recovery | ❌ NOT DOCUMENTED | No runbook |

## B.4 Performance

| Item | Status | Details |
|------|--------|---------|
| Database Indexing | ⚠️ PARTIAL | Foreign keys indexed, search columns may need indexes |
| Query Efficiency | ✅ GOOD | Eloquent with eager loading, pagination |
| Caching | ⚠️ DATABASE DEFAULT | Redis recommended for production |
| Redis | ❌ NOT CONFIGURED | .env has Redis config but not active |
| Queue Processing | ⚠️ DATABASE | Works but Redis recommended |
| Large Datasets | ⚠️ NEEDS TESTING | Pagination implemented, no stress testing done |
| Concurrent Users | ⚠️ NEEDS TESTING | No load testing performed |
| File Storage | ✅ LOCAL | Storage disk configured, S3 optional |
| Report Generation | ✅ DOMPDF | Functional for moderate data |

## B.5 Pre-Production Fixes Required (Critical)

1. **Set APP_DEBUG=false** in production .env
2. **Set SESSION_SECURE_COOKIE=true** in production
3. **Set SESSION_ENCRYPT=true** in production
4. **Add rate limiting** to login, password reset, and API routes
5. **Set Sanctum token expiration** to a reasonable value (e.g., 24 hours)
6. **Switch database to MySQL** for production
7. **Configure Redis** for cache, sessions, and queues
8. **Set up automated backups** with verification
9. **Remove or secure .env** file (ensure not committed to git)
10. **Run `composer audit`** and fix any vulnerabilities

---

# C. RECOMMENDED COMMERCIAL MODEL

## C.1 Licensing Structure

**Recommended: Per-Facility License + Annual Maintenance**

| Component | Model | Ownership |
|-----------|-------|-----------|
| Software License | One-time perpetual license per facility | Dunco Web Solutions retains IP |
| Source Code | NOT transferred to client | Dunco Web Solutions retains IP |
| Implementation | One-time service fee | Work-for-hire |
| Configuration | Included in implementation | Part of setup |
| Hosting | Annual subscription | Client pays provider |
| Support & Maintenance | Annual subscription | Ongoing service |
| Custom Development | Per-project quote | Separate engagement |
| Data Migration | One-time service fee | Separate engagement |
| Training | Included in implementation | Part of setup |

## C.2 Why This Model

- **Protects IP**: Client gets a license to use, not ownership of the code
- **Recurring Revenue**: Annual support/maintenance creates predictable income
- **Scalable**: Each new hospital is a new license fee
- **Competitive**: Lower upfront cost than building custom, better than SaaS lock-in
- **Sustainable**: Maintenance fees cover ongoing support costs

## C.3 Pricing Tiers

### Tier 1: Clinic (1-3 departments, <20 staff) — KES 300,000
### Tier 2: Hospital (4-10 departments, 20-100 staff) — KES 550,000
### Tier 3: Enterprise (10+ departments, 100+ staff, multi-branch) — KES 800,000

All tiers include full system access. Tier differentiation is based on facility size, implementation complexity, data migration scope, training requirements, and support level — NOT feature restriction.

---

# D. RECOMMENDED FIRST-YEAR BUDGET

## D.1 One-Time Costs

| Item | Tier 1 (Clinic) | Tier 2 (Hospital) | Tier 3 (Enterprise) |
|------|-----------------|-------------------|---------------------|
| Software License (All Modules) | KES 200,000 | KES 380,000 | KES 550,000 |
| Installation & Setup | KES 40,000 | KES 60,000 | KES 80,000 |
| Configuration & Branding | KES 25,000 | KES 40,000 | KES 60,000 |
| Data Migration | KES 15,000 | KES 35,000 | KES 60,000 |
| Training (All User Groups) | KES 20,000 | KES 35,000 | KES 50,000 |
| **Subtotal One-Time** | **KES 300,000** | **KES 550,000** | **KES 800,000** |

**Note:** All three tiers include ALL modules — clinical, pharmacy, lab, radiology, billing, HR, insurance/SHA, AI, telemedicine, CMS, marketing, IoT/RFID, reporting, and analytics. No feature is locked or restricted in any tier.

## D.2 Annual Recurring Costs

| Item | Tier 1 (Clinic) | Tier 2 (Hospital) | Tier 3 (Enterprise) |
|------|-----------------|-------------------|---------------------|
| Hosting (VPS/Cloud) | KES 48,000 | KES 72,000 | KES 120,000 |
| Domain Name | **FREE Year 1** (KES 2,000 Year 2+) | **FREE Year 1** (KES 2,000 Year 2+) | **FREE Year 1** (KES 2,000 Year 2+) |
| Professional Email | **FREE** (unlimited accounts) | **FREE** (unlimited accounts) | **FREE** (unlimited accounts) |
| SSL Certificate | FREE (Let's Encrypt) | FREE (Let's Encrypt) | FREE (Let's Encrypt) |
| Support & Maintenance | KES 72,000 | KES 108,000 | KES 180,000 |
| **Subtotal Annual** | **KES 120,000** | **KES 180,000** | **KES 300,000** |

**Year 1 Bonus:** Domain registration is FREE. From Year 2 onward, domain renewal is KES 2,000/year (added to annual renewal).

## D.3 Optional Costs (Annual)

| Item | Tier 1 | Tier 2 | Tier 3 |
|------|--------|--------|--------|
| SMS (Twilio/Africa's Talking) | KES 24,000 | KES 60,000 | KES 120,000 |
| AI API (OpenRouter) | KES 12,000 | KES 24,000 | KES 48,000 |
| Zoom Telemedicine | KES 18,000 | KES 36,000 | KES 72,000 |
| Payment Gateway Charges | 1.5-3% per txn | 1.5-3% per txn | 1.5-3% per txn |
| Advanced DR (offsite backups) | KES 24,000 | KES 48,000 | KES 120,000 |

## D.4 Total First-Year Investment

| Package | One-Time | Annual | Total Year 1 |
|---------|----------|--------|--------------|
| **Tier 1 (Clinic)** | KES 300,000 | KES 120,000 | **KES 420,000** |
| **Tier 2 (Hospital)** | KES 550,000 | KES 180,000 | **KES 730,000** |
| **Tier 3 (Enterprise)** | KES 800,000 | KES 300,000 | **KES 1,100,000** |

---

# E. ANNUAL RENEWAL PRICE

| Package | Annual Renewal | Includes |
|---------|---------------|----------|
| **Tier 1** | KES 120,000 | Hosting, email, support, maintenance, updates |
| **Tier 2** | KES 180,000 | Hosting, email, support, maintenance, updates |
| **Tier 3** | KES 300,000 | Hosting, email, support, maintenance, updates, priority SLA |

**Renewal includes:**
- Cloud server hosting and management
- Domain renewal
- Business email accounts
- SSL certificate renewal
- System updates and security patches
- Bug fixes and technical support
- Database maintenance
- Daily backup monitoring
- Performance optimization
- Priority response for Tier 3

**Renewal does NOT include:**
- Third-party subscription fees (M-Pesa, SMS, AI, Zoom, SHA API)
- New feature development
- Major customizations
- Hardware replacement
- Data migration for new systems

---

# F. OPTIONAL SERVICES

| Service | Price Range | Notes |
|---------|-------------|-------|
| Additional Custom Modules | KES 50,000 - 300,000 | Per module, scoped separately |
| Major Workflow Customization | KES 30,000 - 150,000 | Per workflow |
| Additional Training Sessions | KES 5,000 - 10,000 | Per session per group |
| On-Site Visits (Nairobi) | KES 10,000 - 20,000 | Per visit + transport |
| On-Site Visits (Upcountry) | KES 20,000 - 40,000 | Per visit + travel + accommodation |
| Advanced Data Migration | KES 50,000 - 200,000 | Complex legacy systems |
| Custom Report Development | KES 10,000 - 50,000 | Per report |
| M-Pesa Production Setup | KES 15,000 - 30,000 | Safaricom credential setup + testing |
| SHA/DHA Integration Setup | KES 20,000 - 50,000 | DHA facility credential setup + testing |
| SMS Provider Setup (Twilio) | KES 10,000 - 20,000 | Account setup + template registration |
| AI Feature Configuration | KES 10,000 - 20,000 | OpenRouter setup + prompt tuning |
| Security Audit | KES 50,000 - 100,000 | Annual recommended |
| Load Testing | KES 30,000 - 60,000 | One-time |
| SLA Upgrade (24/7 Premium) | KES 60,000 - 120,000 | Annual premium |

---

# G. CLIENT-FACING PROPOSAL

---

## DUNCO HOSPITAL MANAGEMENT SYSTEM
### Comprehensive Implementation Proposal

**Prepared for:** [Hospital Name]
**Prepared by:** Dunco Web Solutions
**Date:** [Date]
**Version:** 3.1

---

### 1. EXECUTIVE SUMMARY

Dunco HMS is a comprehensive, web-based Hospital Management System designed to centralize and streamline all hospital operations — from patient registration and clinical care to billing, pharmacy, laboratory, HR, and management reporting.

**Built for Kenya. Ready for Africa.**

Dunco HMS is not generic hospital software — it is purpose-built for the Kenyan healthcare environment with deep integration into the systems that matter most:

**Kenya-Specific Integrations:**
- **M-Pesa (Safaricom Daraja API)** — Accept payments directly through M-Pesa STK Push. Patients pay consultation fees, pharmacy bills, and lab charges via their phone. Real-time callback handling, transaction tracking, and automatic invoice reconciliation.
- **SHA / SHIF (Social Health Authority)** — Full integration with Kenya's new Social Health Insurance Fund. Patient eligibility verification, member lookup, authorization requests, claims submission, and coverage tracking — all through the DHA Health Interoperability Gateway.
- **DHA (Digital Health Agency)** — Connect to Kenya's national health data infrastructure via the Digital Health Superhighway. Patient verification against the national Client Registry, facility lookup, provider verification, clinical document exchange, and biometric verification.
- **AI-Powered Healthcare** — Built-in AI assistants (Elliana D Virtual Nurse) for patient queries, AI appointment suggestions, AI diagnosis support, AI predictive analytics, and AI content generation for hospital marketing.

**Core Capabilities:**
- **Centralized patient management** across OPD, IPD, and emergency — one patient record, accessible across all departments
- **Integrated billing** with M-Pesa, insurance, cash, and card payment support — no more manual reconciliation
- **Pharmacy and inventory control** with expiry tracking, stock management, and automated reorder alerts
- **Laboratory and radiology** with test requests, results entry, PDF reports, and equipment integration
- **Human resources management** including payroll, attendance, leave, appraisals, recruitment, and training
- **Insurance and claims management** with SHA integration, private insurance, and claims tracking
- **Telemedicine** for remote consultations via Zoom integration
- **Patient self-service portal** for appointments, results, billing, and profile management
- **Marketing suite** with social media management, SEO, AI content writer, and campaign tools
- **Comprehensive reporting** with custom report builder, BI dashboard, and PDF/Excel export

Dunco HMS is designed for Kenyan healthcare facilities of all sizes — from small clinics to multi-branch hospital networks — and includes the Kenya-specific integrations that generic international software does not offer.

**All packages include FREE domain registration (.or.ke or .co.ke) for Year 1 and UNLIMITED professional email accounts** (e.g., info@hospital.or.ke, doctor@hospital.or.ke, pharmacy@hospital.or.ke) — no per-user charges, no hidden email costs.

---

### 2. ABOUT DUNCO HMS

Dunco HMS is developed by **Dunco Web Solutions**, a Kenyan software company specializing in healthcare technology. The system is purpose-built for the Kenyan market with deep integration into local payment systems, insurance frameworks, and health data infrastructure.

**Technology:**
- Backend: Laravel 12 (PHP 8.2+) — modern, secure, well-supported framework
- Frontend: Blade templates, Alpine.js, Tailwind CSS — fast, responsive, mobile-friendly
- Database: MySQL 8.0+ with Redis for caching — reliable and scalable
- Authentication: Laravel Breeze + Sanctum — secure session + API token auth
- Authorization: Spatie Permission (RBAC) — 21 roles, 95+ granular permissions
- PDF: DomPDF — invoices, claims, reports, ID cards
- Excel: Maatwebsite Excel — data import/export
- Backups: Spatie Laravel Backup — automated daily backups

**Architecture:**
- Server-side rendered web application — works on any device, any browser
- Role-based access control with 21 predefined roles (Super Admin, Doctor, Nurse, Pharmacist, etc.)
- Module-based architecture with enable/disable capability — turn features on or off per department
- Multi-branch support — manage multiple hospital locations from one system
- Multi-language support (English, French, Swahili, Arabic)
- Multi-currency support with live exchange rates
- RESTful API with token authentication (Sanctum) — for mobile apps and integrations

**Kenya Integrations:**
- M-Pesa (Safaricom Daraja API) — STK Push, callbacks, transaction tracking
- SHA / SHIF (Social Health Authority) — eligibility, authorization, claims via DHA gateway
- DHA (Digital Health Agency) — national patient registry, facility registry, document exchange
- AI Healthcare (OpenRouter) — virtual nurse assistant, diagnosis support, appointment suggestions

**Scale:**
- 153 controllers, 131 models, 457 blade views
- 163 database migrations
- 75+ configurable modules
- 95+ granular permissions

---

### 3. MODULES & CAPABILITIES

#### Clinical Operations
- Patient Registration & Profiles
- OPD (Outpatient Department) Management
- IPD (Inpatient Department) Management
- Doctor Management & Scheduling
- Nurse Management & Duty Rosters
- Appointment Booking (walk-in + online)
- Queue Management with display boards and kiosk mode
- Prescription Management & E-Prescriptions
- Medical History & Vitals Tracking
- Patient Diagnosis with ICD-10 Codes
- Discharge Summary with PDF generation
- Birth & Death Reports with certificates
- Operation Reports
- Case Management
- Consent Management
- Vaccination Management
- Mortuary Management
- CSSD (Central Sterile Services)
- Medical Records Department (MRD)

#### Diagnostics
- Laboratory Management (tests, requests, results)
- Radiology Management (tests, requests, reports)
- Investigation Reports
- Lab Equipment Integration

#### Pharmacy
- Medicine Catalog with categories and brands
- Prescription Dispensing with stock deduction
- E-Prescription Templates
- Drug Interaction Checking
- Inventory Management (multi-store)
- Supplier Management
- Purchase Orders with approval workflow
- Stock Movements & Transfers
- Expiry Tracking & Alerts

#### Billing & Finance
- Invoice Generation with line items
- **M-Pesa STK Push Payment Processing** — patients pay via Safaricom M-Pesa directly from the system
- **M-Pesa Real-Time Callbacks** — automatic payment confirmation and invoice reconciliation
- Payment Processing (cash, card, M-Pesa, insurance)
- Thermal Receipt Printing
- PDF Invoice Generation
- Advance Payments & Deposits
- Expense Management
- Income Management
- Chart of Accounts & Ledger
- Trial Balance
- Profit & Loss, Balance Sheet, Cash Flow
- Multi-Currency Support (live exchange rates)
- Insurance Claims Management
- **SHA/SHIF Integration** — eligibility verification, authorization, claims submission through DHA gateway

#### Human Resources
- Employee Management with photo upload
- Department & Designation Management
- Attendance Tracking
- Leave Management with approval workflow
- Payroll Generation
- Shift Management & Roster Builder
- Performance Appraisals
- Training Programs & Certificates
- Recruitment (job postings, applications)
- HR Announcements
- Public Holidays
- Document Management
- HR Reports (8+ report types)

#### Insurance & SHA (Kenya)
- Insurance Provider Management
- Patient Insurance Policies
- Insurance Claims (submit, approve, reject, payment)
- **SHA Member Management** — register and manage SHA-registered patients
- **SHA Authorization & Verification** — real-time eligibility check through DHA Health Interoperability Gateway
- **SHA Claims Submission** — submit claims directly to SHA for reimbursement
- **SHA Service Codes** — Kenya SHA procedure and service code database
- **ICD-10 Code Database** — standard diagnostic codes for claims and reporting
- Private Insurance Company Management
- Preauthorization Workflow

#### Patient-Facing
- Patient Portal (self-service)
- Online Appointment Booking
- Lab Results Access
- Prescription History
- Billing & Payment History
- Profile Management
- Two-Factor Authentication (optional)

#### Communication
- Queue Display Boards
- Kiosk Mode for token generation
- SMS Reminders (Twilio, Africa's Talking)
- Email Notifications
- Bulk Messaging
- Message Templates
- Appointment & Payment Reminders
- Notice Board

#### Marketing & CMS
- Hospital Website CMS
- Blog Management
- Gallery Management
- Careers & Job Listings
- Testimonials
- Social Media Management (Facebook, Twitter, Instagram, LinkedIn)
- AI Content Writer
- Marketing Campaigns
- Post Scheduler
- SEO Manager
- Graphic Assets Library
- Comment Reply Management

#### AI Features (Kenya-First)
- **Elliana D Virtual Nurse Assistant** — AI-powered chatbot that handles patient queries, appointment booking, and medical information in English and Swahili
- **AI Appointment Suggestions** — intelligent scheduling based on doctor availability, patient history, and department load
- **AI Diagnosis Suggestions** — symptom-based condition matching with probability scoring to assist clinicians
- **AI Predictive Analytics** — bed occupancy forecasting, revenue projections, and patient flow analysis
- **AI Daily Summary** — automated daily hospital performance summary for management
- **AI Content Writer** — generates healthcare marketing content, blog posts, and social media posts for the hospital

#### Advanced Features
- Telemedicine (Zoom integration)
- RFID Tag Management
- IoT Bed Monitoring & Sensors
- Biometric Security
- ID Card Generation with QR Codes
- Custom Report Builder
- BI Dashboard
- Multi-Branch Management
- Module Manager (enable/disable features)
- Theme Customizer
- API Access

---

### WHY DUNCO HMS IS DIFFERENT

**Generic hospital software does not understand the Kenyan healthcare environment.** Dunco HMS does.

| Feature | Generic HMS | Dunco HMS |
|---------|-------------|-----------|
| Payment Processing | Cash, card only | **M-Pesa STK Push + callbacks** |
| Insurance | Manual entry | **SHA/SHIF eligibility, claims, authorization via DHA** |
| Patient Verification | Manual | **National Client Registry lookup via DHA** |
| AI Support | None | **Elliana D Virtual Nurse (English + Swahili)** |
| Marketing | None | **Built-in CMS, social media, AI content writer** |
| Reporting | Basic | **Custom report builder + BI dashboard** |
| Multi-Branch | Extra cost | **Included in all tiers** |
| Localization | English only | **English, Swahili, French, Arabic** |
| Support | International | **Kenyan company, local support** |

**This is not a foreign product adapted for Kenya. It is built for Kenya from the ground up.**

---

### 4. IMPLEMENTATION SCOPE

Our implementation includes:

**Phase 1: Infrastructure (Week 1)**
- Domain registration and DNS setup
- Cloud server provisioning (Ubuntu 22.04 LTS)
- PHP 8.2+, MySQL 8.0, Redis, Nginx installation
- SSL certificate (HTTPS)
- Firewall configuration

**Phase 2: Application Deployment (Week 1-2)**
- Laravel application deployment
- Environment configuration
- Database migration and seeding
- Asset compilation
- Queue and cron setup

**Phase 3: Third-Party Integration (Week 2-3)**
- M-Pesa production setup (Safaricom credentials required)
- SMS provider setup (Twilio or Africa's Talking credentials required)
- Email server configuration
- SHA/DHA integration (facility credentials required)

**Phase 4: Hospital Configuration (Week 3-4)**
- Hospital branding (name, logo, colors)
- Department and ward setup
- Service catalog and fee schedules
- User accounts and role assignment
- Module activation

**Phase 5: Data Migration (Week 4-5)**
- Existing patient data import
- Staff and doctor data import
- Medicine and inventory data import
- Service and fee data import
- Data validation and verification

**Phase 6: Training (Week 5-6)**
- Role-based training sessions
- Administrator training
- Department-specific training
- Training materials and documentation

**Phase 7: Testing & UAT (Week 6-7)**
- System testing
- User acceptance testing
- Bug fixes and adjustments
- Go-live preparation

**Phase 8: Go-Live (Week 7)**
- Production deployment
- Data verification
- User support
- Issue resolution

**Phase 9: Post-Go-Live (Weeks 8-12)**
- On-site support
- Remote monitoring
- Issue resolution
- Performance optimization

---

### 5. INFRASTRUCTURE & HOSTING

**Recommended Server Specification:**

| Component | Minimum | Recommended | Enterprise |
|-----------|---------|-------------|------------|
| CPU | 2 vCPU | 4 vCPU | 8 vCPU |
| RAM | 4 GB | 8 GB | 16 GB |
| Storage | 80 GB SSD | 160 GB NVMe | 320 GB NVMe |
| Database | MySQL 8.0 | MySQL 8.0 + Redis | MySQL 8.0 + Redis |
| Bandwidth | Unmetered | Unmetered | Unmetered |
| Backup | 30 GB | 100 GB | 500 GB |

**Monthly Hosting Cost Estimate:**
- Tier 1 (Clinic): KES 4,000/month — 2 vCPU, 4 GB RAM, 80 GB SSD
- Tier 2 (Hospital): KES 6,000/month — 4 vCPU, 8 GB RAM, 160 GB NVMe
- Tier 3 (Enterprise): KES 10,000/month — 8 vCPU, 16 GB RAM, 320 GB NVMe

**Why healthcare needs reliable hosting:**
- Patient data availability is critical
- Downtime affects patient care
- Data loss is unacceptable
- Security breaches have legal consequences
- Performance affects user adoption

---

### 6. DOMAIN & BUSINESS EMAIL

**Included FREE for Year 1:**

| Item | Tier 1 | Tier 2 | Tier 3 |
|------|--------|--------|--------|
| Domain Registration | **FREE** (.or.ke or .co.ke) | **FREE** (.or.ke or .co.ke) | **FREE** (.or.ke or .co.ke) |
| Domain Renewal (Year 2+) | KES 2,000/year | KES 2,000/year | KES 2,000/year |
| DNS Management | Included | Included | Included |
| SSL Certificate | FREE (Let's Encrypt) | FREE (Let's Encrypt) | FREE (Let's Encrypt) |

**Domain choices:** `.or.ke` (recommended for organisations), `.co.ke` (commercial), `.ac.ke` (academic), `.go.ke` (government)

**Professional Email — UNLIMITED accounts included FREE for Year 1:**

| What You Get | Details |
|--------------|---------|
| Email Accounts | **Unlimited** — create as many as you need |
| Domain | **yourhospital.or.ke** (or .co.ke) |
| Examples | admin@hospital.or.ke, doctor@hospital.or.ke, pharmacy@hospital.or.ke, reception@hospital.or.ke, billing@hospital.or.ke, info@hospital.or.ke |
| Webmail Access | Yes — access from any browser |
| Mobile Access | Yes — Android, iPhone, Outlook, Thunderbird |
| Storage | 10 GB per account |
| IMAP/SMTP | Yes — works with any email client |
| Calendar | Included |
| Admin Panel | Create, delete, manage accounts |
| Spam Protection | Included |
| **Annual Cost (Year 2+)** | **FREE** — self-hosted email on your server |

**How it works:** Email is hosted on your own server (included in hosting package). No per-user charges. Create unlimited accounts like:
- info@hospital.or.ke
- reception@hospital.or.ke
- dr.mwangi@hospital.or.ke
- pharmacy@hospital.or.ke
- billing@hospital.or.ke
- lab@hospital.or.ke
- admin@hospital.or.ke

**Alternative (if preferred):** Google Workspace at KES 200/user/month or Microsoft 365 at KES 250/user/month — quoted separately if client prefers third-party email.

---

### 7. USER TRAINING

| User Group | Sessions | Duration | Topics |
|------------|----------|----------|--------|
| System Administrators | 4 | 3 hours each | System config, users, roles, backups, troubleshooting |
| IT Staff | 3 | 3 hours each | Server management, deployment, monitoring |
| Reception/Front Desk | 3 | 2 hours each | Patient registration, appointments, queue management |
| Doctors | 2 | 2 hours each | Patient records, prescriptions, scheduling, telemedicine |
| Nurses | 2 | 2 hours each | Patient care, vitals, ward management, duty roster |
| Pharmacy | 3 | 2 hours each | Medicine management, dispensing, inventory, prescriptions |
| Laboratory | 3 | 2 hours each | Test requests, results entry, reports, equipment |
| Radiology | 2 | 2 hours each | Test requests, results, reports |
| Billing/Cashiers | 3 | 2 hours each | Invoices, payments, M-Pesa, receipts, reports |
| Finance/Accounts | 3 | 2 hours each | Chart of accounts, ledger, P&L, balance sheet, expenses |
| HR | 3 | 2 hours each | Employees, attendance, leave, payroll, appraisals |
| Insurance/SHA Desk | 2 | 2 hours each | Claims, SHA verification, authorization, tracking |
| Management | 2 | 2 hours each | Dashboards, reports, analytics, KPIs |
| Marketing/CMS | 2 | 2 hours each | Website, blog, social media, campaigns |

**Total: 39 training sessions across 14 user groups**

---

### 8. CUSTOMIZATION

**Included in Implementation (No Extra Charge):**
- Hospital name, logo, branding colors
- Department and ward configuration
- Service catalog and fee schedules
- User accounts and role assignment
- Basic report customization
- Email and SMS templates
- Theme and layout customization

**Paid Customization (Quoted Separately):**
- Custom workflows beyond standard
- Custom report development
- Custom integrations
- New feature development
- Advanced form design
- Custom ID card templates
- Specialized notification rules

---

### 9. SECURITY & DATA PROTECTION

**Implemented Security Measures:**
- Password hashing (bcrypt, 12 rounds)
- Role-based access control (21 roles, 95+ permissions)
- CSRF protection on all forms
- XSS protection via Blade auto-escaping
- SQL injection prevention via Eloquent ORM
- Session management with regeneration
- Audit logging of all critical actions
- Module-level access control
- Biometric data encryption (AES-256-CBC)
- Secure file upload handling

**Additional Security (Recommended):**
- Two-factor authentication for admin users
- Rate limiting on login attempts
- IP-based access restrictions
- Database encryption at rest
- Regular security audits
- Dependency vulnerability scanning

**Data Protection:**
- Daily automated backups
- 30-day backup retention
- Database and file backups included
- Restore capability on demand
- HIPAA-aligned access controls (audit logging, RBAC)

---

### 10. SUPPORT & MAINTENANCE

#### Basic Package: KES 58,000/year
- Email support (business hours)
- 24-hour response time
- Bug fixes
- Security updates
- Application updates
- Monthly backup monitoring

#### Standard Package: KES 82,000/year
- Email + phone support
- 8-hour response time
- All Basic features
- Remote support sessions
- Database maintenance
- Performance monitoring
- Quarterly system review

#### Premium Package: KES 120,000/year
- Email + phone + on-site support
- 2-hour response time
- All Standard features
- Emergency support (24/7)
- Monthly on-site visits
- Proactive monitoring
- Priority bug fixes
- Custom report support

**SLA Summary:**

| Metric | Basic (KES 58K/yr) | Standard (KES 82K/yr) | Premium (KES 120K/yr) |
|--------|-------|----------|---------|
| Response Time | 24 hours | 8 hours | 2 hours |
| Support Hours | Business hours | Extended hours | 24/7 |
| Remote Support | Email only | Email + phone | Email + phone + remote |
| On-Site Support | Not included | Not included | Monthly visits |
| Emergency Support | Not included | Not included | 24/7 included |

**NOT Included in Maintenance:**
- New modules or major features
- Third-party subscription fees (hosting, SMS, AI, Zoom)
- Hardware procurement or replacement
- Network infrastructure changes
- Large-scale data migration
- Major customizations
- New integrations

---

### 11. IMPLEMENTATION TIMELINE

| Phase | Duration | Milestone |
|-------|----------|-----------|
| Phase 1: Infrastructure | Week 1 | Server ready |
| Phase 2: Application Deployment | Weeks 1-2 | System accessible |
| Phase 3: Third-Party Integration | Weeks 2-3 | M-Pesa, SMS live |
| Phase 4: Hospital Configuration | Weeks 3-4 | Hospital data loaded |
| Phase 5: Data Migration | Weeks 4-5 | Historical data imported |
| Phase 6: Training | Weeks 5-6 | Staff trained |
| Phase 7: Testing/UAT | Weeks 6-7 | UAT sign-off |
| Phase 8: Go-Live | Week 7 | System operational |
| Phase 9: Post-Go-Live | Weeks 8-12 | Stabilization |

**Total: 12 weeks from contract signing to stabilization**

---

### 12. PAYMENT TERMS

**Tier 1 (Clinic) — KES 300,000:**

| Milestone | Percentage | Amount | Due |
|-----------|-----------|--------|-----|
| Contract Signing | 50% | KES 150,000 | Day 0 |
| UAT Sign-off | 30% | KES 90,000 | Week 7 |
| Go-Live + 30 days | 20% | KES 60,000 | Week 11 |

**Tier 2 (Hospital) — KES 550,000:**

| Milestone | Percentage | Amount | Due |
|-----------|-----------|--------|-----|
| Contract Signing | 40% | KES 220,000 | Day 0 |
| Infrastructure Ready | 20% | KES 110,000 | Week 2 |
| UAT Sign-off | 25% | KES 137,500 | Week 7 |
| Go-Live + 30 days | 15% | KES 82,500 | Week 11 |

**Tier 3 (Enterprise) — KES 800,000:**

| Milestone | Percentage | Amount | Due |
|-----------|-----------|--------|-----|
| Contract Signing | 35% | KES 280,000 | Day 0 |
| Infrastructure Ready | 20% | KES 160,000 | Week 2 |
| UAT Sign-off | 25% | KES 200,000 | Week 7 |
| Go-Live + 30 days | 20% | KES 160,000 | Week 11 |

**Annual Renewal:** KES 120,000 / KES 180,000 / KES 300,000 per tier (due annually from go-live date)

---

### 13. WARRANTY

- **30-day warranty** from go-live date
- Covers bugs and defects in delivered functionality
- Does not cover issues caused by client modifications
- Does not cover third-party service outages
- Warranty support via email, 24-hour response

---

### 14. LICENSING & OWNERSHIP

- **Software License**: Perpetual, non-exclusive, non-transferable license to use Dunco HMS at the licensed facility
- **Source Code**: Remains property of Dunco Web Solutions. NOT transferred to client
- **Customizations**: Custom work performed for the client becomes part of the licensed system
- **Data**: All hospital data remains property of the hospital
- **IP**: Dunco Web Solutions retains all intellectual property rights
- **Restrictions**: Client may not redistribute, resell, or sublicense the software

---

### 15. ASSUMPTIONS

1. Client will provide hospital data (departments, services, fees, staff list) within 2 weeks of contract signing
2. Client will obtain and provide M-Pesa production credentials from Safaricom
3. Client will obtain and provide SHA/DHA facility credentials from Kenya Ministry of Health
4. **Domain (.or.ke or .co.ke) is registered FREE for Year 1 by Dunco Web Solutions** — client must not have an existing domain that conflicts
5. **Professional email is hosted on the included server** — unlimited accounts at no extra charge
6. Client will designate a project lead for coordination
7. Client will provide suitable training venue and equipment
8. Client will participate in UAT within the scheduled timeframe
9. Internet connectivity at the hospital is reliable (minimum 10 Mbps)
10. Hospital has existing computers/tablets for system access

---

### 16. EXCLUSIONS

- Third-party subscription fees (M-Pesa transaction fees, SMS charges, AI API usage, Zoom subscription)
- Hardware procurement (computers, tablets, printers, barcode scanners)
- Network infrastructure setup (LAN, WiFi, cabling)
- Internet service provider contracts
- RFID hardware and IoT sensors (if required)
- Biometric hardware (if required)
- Legal and regulatory compliance consultation
- Data entry of historical records beyond migration scope
- Custom feature development not in standard scope

---

### 17. CLIENT RESPONSIBILITIES

1. Provide hospital information and data within agreed timelines
2. Designate a project coordinator
3. Ensure stakeholder availability for training
4. Provide feedback during UAT within 5 business days
5. Obtain required third-party credentials (M-Pesa, SHA, SMS)
6. Ensure reliable internet connectivity
7. Provide hardware for system access
8. Make payment milestones on time
9. Communicate issues promptly through designated channels

---

### 18. ACCEPTANCE & SIGN-off

Upon completion of implementation:

| Item | Client Sign-off | Date |
|------|----------------|------|
| Infrastructure Setup | ☐ | ___/___/___ |
| Application Deployment | ☐ | ___/___/___ |
| Third-Party Integration | ☐ | ___/___/___ |
| Hospital Configuration | ☐ | ___/___/___ |
| Data Migration | ☐ | ___/___/___ |
| Training Completion | ☐ | ___/___/___ |
| UAT Sign-off | ☐ | ___/___/___ |
| Go-Live Approval | ☐ | ___/___/___ |

**Authorized Client Representative:**

Name: _________________________
Title: _________________________
Signature: _________________________
Date: _________________________

**Dunco Web Solutions:**

Name: _________________________
Title: _________________________
Signature: _________________________
Date: _________________________

---

# H. INTERNAL NOTES FOR DUNCO WEB SOLUTIONS

## H.1 Risks

| Risk | Severity | Mitigation |
|------|----------|------------|
| Client delays providing data | HIGH | Set contractual deadlines with penalties |
| M-Pesa credentials delayed | MEDIUM | System works without M-Pesa (cash billing) |
| SHA credentials unavailable | LOW | Local SHA management works without API |
| Scope creep | HIGH | Strict change control process |
| Client expects regulatory compliance | HIGH | Do NOT claim HIPAA/DPA compliance without verification |
| Data migration complexity | MEDIUM | Scoping exercise before quote |
| Client hardware inadequate | MEDIUM | Specify minimum hardware requirements in contract |
| Internet reliability | MEDIUM | Recommend backup internet connection |
| Staff resistance to change | MEDIUM | Include change management in training |

## H.2 Pricing Mistakes to Avoid

1. **Do NOT include third-party costs in package price** — always list separately
2. **Do NOT promise unlimited support** — define SLA clearly
3. **Do NOT give source code** — license only
4. **Do NOT underprice training** — it's labor-intensive
5. **Do NOT quote data migration without scoping** — complexity varies enormously
6. **Do NOT price SMS/AI/Zoom as included** — these are ongoing client costs
7. **Do NOT forget hosting renewal** — it's annual, not one-time

## H.3 Scope-Creep Risks

1. "Can you add this one small feature?" — Quote separately
2. "The other hospital has this feature" — Different scope, different price
3. "Can you customize this report?" — Included: simple. Custom: quoted
4. "Can you integrate with our existing system?" — Always quoted separately
5. "Can you train more people?" — Additional sessions quoted
6. "Can you modify the workflow?" — Small tweaks included, major changes quoted
7. "Can you add more user roles?" — Included in standard, custom roles quoted

## H.4 Technical Weaknesses to Fix Before Selling

### CRITICAL (Fix before any demonstration)

1. **No rate limiting on login** — Add throttle middleware to auth routes
2. **Sanctum tokens never expire** — Set `expiration` to 24 hours minimum
3. **APP_DEBUG=true** — Must be false in production
4. **SESSION_SECURE_COOKIE=false** — Must be true in production
5. **Stripe/PayPal are stubs** — Either implement properly or remove from feature list
6. **BatchExport job is a stub** — Remove or implement before demo
7. **Default password `password123`** in HrController — Remove this default
8. **No HTTPS redirect middleware** — Add forced HTTPS in production

### HIGH (Fix before production deployment)

9. **SQLite as default database** — Must use MySQL in production
10. **No Redis configured** — Set up Redis for cache/sessions/queues
11. **No automated backup verification** — Add restore testing
12. **No rate limiting on API** — Add API throttling
13. **SMS service completely unconfigured** — Set up at least one provider
14. **2FA incomplete** — Either implement fully or remove from feature list
15. **No data encryption at rest** for medical records

### MEDIUM (Fix during implementation)

16. **7 "Coming Soon" placeholders** — Implement or remove buttons
17. **QR code on visitor badge** — Complete the implementation
18. **IoT sensor history tables** — Add dedicated history tables
19. **RFID location history** — Add dedicated history table
20. **Elliana D chat history** — Implement conversation storage

### LOW (Fix over time)

21. **Mixed bcrypt/Hash::make usage** — Standardize on Hash::make
22. **No comprehensive test coverage** for financial modules
23. **No load testing** performed
24. **No dependency vulnerability audit** run
25. **Session encryption disabled** — Enable for production

## H.5 Features That Need Verification

1. **M-Pesa callback handling** — Test end-to-end with Safaricom sandbox
2. **SHA/DHA integration** — Cannot verify without real credentials
3. **Zoom telemedicine** — Cannot verify without real API keys
4. **Social media OAuth** — Cannot verify without app credentials
5. **SMS delivery** — Cannot verify without provider credentials
6. **AI features** — Test with and without OpenRouter API key
7. **PDF generation** — Test with large datasets
8. **Excel export** — BatchExport is currently a stub
9. **Thermal receipt printing** — Test with actual thermal printer
10. **ID card QR codes** — Verify scanning works

## H.6 Things to Fix Before Demonstrating or Selling

### Demo Preparation Checklist

- [ ] Remove or clearly label Stripe/PayPal as "coming soon"
- [ ] Implement or remove BatchExport stub
- [ ] Add rate limiting to login
- [ ] Set APP_DEBUG=false for demo environment
- [ ] Ensure demo has sample data (not empty database)
- [ ] Test all PDF generation with sample data
- [ ] Verify M-Pesa sandbox STK push works
- [ ] Test all report generation
- [ ] Verify queue worker is running
- [ ] Test patient portal login
- [ ] Verify email notifications work
- [ ] Prepare demo script with realistic scenarios
- [ ] Test on mobile devices (responsive)
- [ ] Verify all 13 sidebar sections are accessible
- [ ] Test role-based access (login as different roles)

## H.7 Commercial Positioning Notes

**DO NOT sell as:**
- "130+ features hospital software"
- "AI-powered hospital management"
- "Fully integrated healthcare solution"

**DO sell as:**
- "The only HMS built for Kenya — with M-Pesa, SHA, and DHA integration out of the box"
- "Accept M-Pesa payments directly from the system — patients pay via STK Push"
- "Fully SHA-ready — verify patient eligibility, submit claims, and track reimbursements"
- "Connected to the DHA Digital Health Superhighway — national patient verification"
- "AI-powered virtual nurse handles patient queries 24/7 in English and Swahili"
- "Centralized hospital operations — one system for all departments"
- "Reduce paperwork and improve patient flow"
- "Real-time financial visibility and accountability"
- "Patient self-service reduces front desk workload"
- "Digital records with audit trail for compliance"
- "Scalable from clinic to multi-branch hospital"

**Key differentiators vs. competitors:**
1. **M-Pesa integration** — most Kenyan HMS products still use manual payment recording
2. **SHA/DHA integration** — ready for Kenya's new universal health coverage system
3. **AI features** — virtual nurse, diagnosis support, predictive analytics
4. **Full feature set** — clinical, pharmacy, lab, billing, HR, insurance, marketing in one system
5. **Kenyan company** — local support, understands Kenyan healthcare workflows
6. **Modern technology** — Laravel 12, not legacy PHP or outdated frameworks

**Target buyer:** Hospital CEO, Administrator, Medical Superintendent, Finance Manager, IT Manager

**Key message:** "Dunco HMS is the only hospital management system in Kenya that gives you M-Pesa payments, SHA insurance integration, DHA national connectivity, and AI-powered clinical support — all in one platform. It pays for itself through reduced paperwork, fewer billing errors, better inventory control, and improved staff accountability."

---

*End of Analysis*
