# DUNCO HOSPITAL MANAGEMENT SYSTEM

## Complete System Documentation & Feature Catalogue

**Version:** 2.4  
**Date:** September 2026  
**Prepared by:** Dunco Web Solutions  
**Website:** https://hmse.duncowebsolutions.co.ke/  
**GitHub:** https://github.com/dunte1/dunco-hms

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Product Overview](#2-product-overview)
3. [Key Benefits](#3-key-benefits)
4. [System Modules](#4-system-modules)
5. [Detailed Features](#5-detailed-features)
6. [User Roles](#6-user-roles)
7. [Patient Workflow](#7-patient-workflow)
8. [Clinical Workflow](#8-clinical-workflow)
9. [Laboratory Workflow](#9-laboratory-workflow)
10. [Pharmacy Workflow](#10-pharmacy-workflow)
11. [Billing Workflow](#11-billing-workflow)
12. [Insurance/SHA Workflow](#12-insurancesha-workflow)
13. [Reporting](#13-reporting)
14. [Security](#14-security)
15. [System Architecture](#15-system-architecture)
16. [Technical Requirements](#16-technical-requirements)
17. [Installation](#17-installation)
18. [Configuration](#18-configuration)
19. [Administration](#19-administration)
20. [User Management](#20-user-management)
21. [Backup and Recovery](#21-backup-and-recovery)
22. [Maintenance](#22-maintenance)
23. [Support](#23-support)
24. [Current Implementation Status](#24-current-implementation-status)
25. [Limitations / Outstanding Items](#25-limitations--outstanding-items)
26. [Future Enhancements](#26-future-enhancements)
27. [Demo Access](#27-demo-access)
28. [Contact Information](#28-contact-information)

---

## 1. Executive Summary

The **Dunco Hospital Management System (Dunco HMS)** is a comprehensive, web-based healthcare management platform designed for hospitals, clinics, and medical facilities. Built on Laravel 12 with PHP 8.2+, the system provides end-to-end management of hospital operations including patient registration, clinical workflows, billing, pharmacy, laboratory, radiology, human resources, and insurance management.

The system includes **SHA (Social Health Authority) integration** capabilities through Kenya's Digital Health Authority (DHA) Interoperability Engine, enabling electronic claims processing, eligibility verification, and pre-authorization workflows.

**Key Highlights:**
- **110+ database tables** covering all hospital operations
- **21 user roles** with granular permission-based access control
- **584+ routes** spanning public, authenticated, and API endpoints
- **110+ Eloquent models** representing the complete hospital data model
- **SHA/EHA integration** with OAuth2 authentication and audit logging
- **M-Pesa payment integration** for mobile money transactions
- **RESTful API** with Sanctum token-based authentication
- **Multi-branch support** for hospital networks
- **Multi-language support** (English, French, Swahili, Arabic)

---

## 2. Product Overview

### Technology Stack

| Component | Technology |
|-----------|------------|
| Backend Framework | Laravel 12.x |
| Programming Language | PHP 8.2+ |
| Frontend | Blade Templates + Alpine.js + Tailwind CSS |
| Build Tool | Vite 7.0+ |
| Database | MySQL 8.0+ (SQLite for development) |
| Authentication | Laravel Sanctum (API) + Session-based (Web) |
| Authorization | Spatie Laravel Permission v6.21 |
| PDF Generation | barryvdh/laravel-dompdf v3.1 |
| Excel Import/Export | maatwebsite/excel v3.1 |
| SMS | Twilio SDK v7.3 |
| Activity Logging | spatie/laravel-activitylog v4.7 |
| Backups | spatie/laravel-backup v9.0 |
| Social Login | Laravel Socialite v5.15 |
| Hosting | Linux Server (cPanel) |
| Web Server | Apache 2.x |

### Deployment

- **Production URL:** https://hmse.duncowebsolutions.co.ke/
- **Server:** Shared hosting via cPanel (213.139.204.9)
- **SSL:** Let's Encrypt (active)
- **Database:** MySQL 8.0 on localhost

---

## 3. Key Benefits

1. **Centralized Patient Management** — Complete patient lifecycle from registration through discharge
2. **Integrated Billing** — Automated invoice generation, payment processing, and receipt management
3. **SHA Compliance** — Pre-built integration with Kenya's Social Health Authority
4. **Real-time Dashboard** — Live hospital operations overview with key metrics
5. **Role-Based Access** — Granular permissions for 21 different user roles
6. **Multi-Branch Support** — Manage multiple hospital locations from a single system
7. **Paperless Operations** — Digital records, e-prescriptions, and electronic lab orders
8. **Mobile Payments** — M-Pesa integration for convenient patient payments
9. **Audit Trail** — Complete activity logging for compliance and accountability
10. **Scalable Architecture** — Built on Laravel with queue-based background processing

---

## 4. System Modules

### Module Inventory

| # | Module | Purpose | Status |
|---|--------|---------|--------|
| 1 | Administration | System configuration, user management, roles | Implemented |
| 2 | Patient Management | Patient registration, profiles, history | Implemented |
| 3 | Doctor Management | Doctor profiles, departments, schedules | Implemented |
| 4 | Appointments | Scheduling, booking, management | Implemented |
| 5 | Reception / Queue | Queue management, token generation | Implemented |
| 6 | Outpatient (OPD) | Consultations, visits, clinical notes | Implemented |
| 7 | Inpatient (IPD) | Admissions, wards, beds, discharge | Implemented |
| 8 | Nursing | Nurse management, duty roster, ward assignment | Implemented |
| 9 | Pharmacy | Medicines, prescriptions, dispensing | Implemented |
| 10 | Laboratory | Lab tests, requests, results | Implemented |
| 11 | Radiology | Imaging tests, requests | Implemented |
| 12 | Billing & Finance | Invoices, payments, receipts | Implemented |
| 13 | Insurance / SHA | SHA members, eligibility, claims | Partially Implemented |
| 14 | Human Resources | Employees, payroll, attendance, leave | Implemented |
| 15 | Blood Bank | Donors, stock, requests | Implemented |
| 16 | Ambulance & Emergency | Calls, emergency admissions | Implemented |
| 17 | Reports & Analytics | Patient, revenue, financial reports | Implemented |
| 18 | Settings | System configuration, branches, audit logs | Implemented |
| 19 | CMS | Blog, gallery, careers, testimonials | Implemented |
| 20 | Marketing Suite | Posts, campaigns, social media, SEO | Implemented |
| 21 | Patient Portal | Patient self-service portal | Implemented |
| 22 | Telemedicine | Video consultations, sessions | Implemented |
| 23 | RFID Management | Tag tracking, scanning | Implemented |
| 24 | IoT Bed Monitoring | Sensor data, occupancy, alerts | Implemented |
| 25 | Biometric Security | Fingerprint, card scanning | Implemented |
| 26 | AI Features | Elliana-D virtual nurse, predictions | Partially Implemented |
| 27 | EHR Integration | HL7/FHIR configuration | Partially Implemented |
| 28 | Finance Module | Accounts, income, expenses, ledgers | Implemented |

---

## 5. Detailed Features

### 5.1 Administration Module

| Feature | Status |
|---------|--------|
| Admin Dashboard | Implemented |
| User Management (CRUD) | Implemented |
| Role Management (CRUD) | Implemented |
| Permission Management | Implemented |
| Department Management | Implemented |
| Hospital Branch Management | Implemented |
| System Settings | Implemented |
| Audit Logs | Implemented |
| Activity Logs | Implemented |
| Notifications | Implemented |
| Profile Management | Implemented |
| Theme Customization | Implemented |
| Language Switching | Implemented |
| Timezone Configuration | Implemented |
| API Key Management | Implemented |
| Backup & Restore | Implemented |

### 5.2 Patient Management Module

| Feature | Status |
|---------|--------|
| Patient Registration | Implemented |
| Patient Profile | Implemented |
| Patient Search | Implemented |
| Patient History | Implemented |
| Patient Identification (auto-generated patient_no) | Implemented |
| Emergency Contacts | Implemented |
| Next of Kin | Implemented |
| Patient Documents | Implemented |
| Medical History | Implemented |
| Patient Visits | Implemented |
| Patient Diagnosis | Implemented |
| Diagnosis Categories | Implemented |
| ICD-10 Codes | Implemented |

### 5.3 Doctor Management

| Feature | Status |
|---------|--------|
| Doctor Profiles (CRUD) | Implemented |
| Department Assignment | Implemented |
| Doctor Charges Configuration | Implemented |
| Doctor Schedules | Implemented |

### 5.4 Appointments

| Feature | Status |
|---------|--------|
| Appointment Booking | Implemented |
| Appointment Management (CRUD) | Implemented |
| Online Appointment Requests | Implemented |
| Public Booking Page | Implemented |
| Appointment Status Tracking | Implemented |

### 5.5 Queue Management

| Feature | Status |
|---------|--------|
| Queue Token Generation | Implemented |
| Queue Display Board | Implemented |
| Queue Kiosk | Implemented |
| Token Calling | Implemented |
| Service Start/Complete/Cancel | Implemented |
| Queue Analytics | Implemented |

### 5.6 Outpatient (OPD)

| Feature | Status |
|---------|--------|
| OPD Visits | Implemented |
| Consultation Notes | Implemented |
| Vital Signs Recording | Implemented |
| Clinical Examination | Implemented |
| Treatment Plans | Implemented |
| Follow-up Scheduling | Implemented |

### 5.7 Inpatient (IPD)

| Feature | Status |
|---------|--------|
| Patient Admission | Implemented |
| Ward Management | Implemented |
| Bed Types Management | Implemented |
| Bed Allocation | Implemented |
| Bed Assignments | Implemented |
| Patient Transfers | Implemented |
| Ward Rounds | Implemented |
| Discharge Summary | Implemented |
| Discharge Processing | Implemented |

### 5.8 Nursing Module

| Feature | Status |
|---------|--------|
| Nurse Profiles | Implemented |
| Nurse Departments | Implemented |
| Duty Roster | Implemented |
| Ward Assignment | Implemented |
| Nursing Notes | Implemented |
| Vital Signs Monitoring | Implemented |
| Medication Administration | Implemented |

### 5.9 Pharmacy Module

| Feature | Status |
|---------|--------|
| Medicine Catalog (CRUD) | Implemented |
| Medicine Categories | Implemented |
| Medicine Brands | Implemented |
| Drug Inventory | Implemented |
| Stock Management | Implemented |
| Supplier Management | Implemented |
| Purchase Orders | Implemented |
| Stock Movements | Implemented |
| Prescriptions Management | Implemented |
| Prescription Items | Implemented |
| E-Prescription with Templates | Implemented |
| Expiry Tracking | Implemented |
| Low Stock Alerts | Implemented |

### 5.10 Laboratory Module

| Feature | Status |
|---------|--------|
| Lab Test Catalog | Implemented |
| Lab Categories | Implemented |
| Lab Requests | Implemented |
| Lab Request Items | Implemented |
| Lab Technicians | Implemented |
| Lab Equipment Management | Implemented |
| Lab Results Entry | Implemented |
| Lab Reports | Implemented |
| Equipment Results | Implemented |

### 5.11 Radiology Module

| Feature | Status |
|---------|--------|
| Radiology Test Catalog | Implemented |
| Radiology Categories | Implemented |
| Radiology Requests | Implemented |
| Radiology Results | Implemented |
| Radiology Reports | Implemented |

### 5.12 Billing & Finance Module

| Feature | Status |
|---------|--------|
| Invoice Generation | Implemented |
| Invoice Items | Implemented |
| Payment Processing | Implemented |
| Payment Status Tracking | Implemented |
| Receipt Generation (PDF) | Implemented |
| Thermal Receipt Printing | Implemented |
| Package Management | Implemented |
| Advance Payments | Implemented |
| Outstanding Balances | Implemented |
| Expense Tracking | Implemented |
| Expense Categories | Implemented |
| Income Tracking | Implemented |
| Multi-Currency Support | Implemented |
| M-Pesa Integration | Implemented |

### 5.13 Insurance / SHA Module

| Feature | Status |
|---------|--------|
| Insurance Provider Management | Implemented |
| Patient Insurance Policies | Implemented |
| SHA Provider Configuration | Implemented |
| SHA Member Registry | Implemented |
| SHA Member Verification | Implemented (via ShaService with local DB fallback) |
| SHA Eligibility Checking | Implemented (via ShaService) |
| SHA Pre-Authorization | Implemented (via ShaService) |
| SHA Claims Submission | Implemented (via ShaService) |
| SHA Service Codes | Implemented |
| ICD-10 Codes | Implemented (75 codes seeded) |
| Insurance Claims Lifecycle | Implemented |
| Insurance API Logs | Implemented |
| Generic Insurance Verification | SIMULATED (InsuranceApiController) |
| Generic Insurance Claims | SIMULATED (InsuranceApiController) |
| SHA Live API Integration | NOT VERIFIED (EHA credentials not configured in production) |

### 5.14 Human Resources Module

| Feature | Status |
|---------|--------|
| Employee Management (CRUD) | Implemented |
| Employee Departments | Implemented |
| Designations | Implemented |
| Attendance Tracking | Implemented |
| Work Schedules | Implemented |
| Shifts Management | Implemented |
| Employee Shifts | Implemented |
| Payroll Management | Implemented |
| Leave Requests | Implemented |
| Leave Types | Implemented |
| Public Holidays | Implemented |
| Performance Appraisals | Implemented |
| Training Programs | Implemented |
| Training Enrollments | Implemented |
| HR Announcements | Implemented |
| Employee Documents | Implemented |
| ID Card Generation | Implemented |

### 5.15 Blood Bank Module

| Feature | Status |
|---------|--------|
| Blood Donors | Implemented |
| Blood Groups | Implemented |
| Blood Inventory | Implemented |
| Blood Requests | Implemented |

### 5.16 Ambulance & Emergency Module

| Feature | Status |
|---------|--------|
| Ambulance Management | Implemented |
| Ambulance Calls | Implemented |
| Emergency Admissions | Implemented |

### 5.17 Reports & Analytics Module

| Feature | Status |
|---------|--------|
| Patient Reports | Implemented |
| Revenue Reports | Implemented |
| Appointment Reports | Implemented |
| Financial Reports | Implemented |
| Custom Report Builder | Implemented |
| BI Dashboard | Implemented |
| Daily Summary Reports | Implemented |

### 5.18 Patient Portal

| Feature | Status |
|---------|--------|
| Patient Login | Implemented |
| Patient Dashboard | Implemented |
| Appointment Booking | Implemented |
| Prescription View | Implemented |
| Lab Results View | Implemented |
| Medical History View | Implemented |
| Billing View | Implemented |
| Profile Management | Implemented |
| Two-Factor Authentication | Implemented |

### 5.19 Telemedicine Module

| Feature | Status |
|---------|--------|
| Telemedicine Sessions (CRUD) | Implemented |
| Session Join/Start/End | Implemented |
| Zoom Integration | Implemented |
| Telemedicine Doctor Role | Implemented |

### 5.20 CMS Module

| Feature | Status |
|---------|--------|
| Blog Management | Implemented |
| Gallery Management | Implemented |
| Careers/Job Postings | Implemented |
| Testimonials | Implemented |
| Inquiries | Implemented |
| SEO Management | Implemented |
| Header/Footer Customization | Implemented |

### 5.21 Marketing Suite

| Feature | Status |
|---------|--------|
| Marketing Dashboard | Implemented |
| AI Content Generation | Implemented |
| Post Management | Implemented |
| Campaign Management | Implemented |
| Social Account Management | Implemented |
| Post Scheduling | Implemented |
| Comment Management | Implemented |
| Graphic Assets | Implemented |
| SEO Analytics | Implemented |

### 5.22 AI Features

| Feature | Status |
|---------|--------|
| Elliana-D Virtual Nurse | Partially Implemented (OpenRouter API integration) |
| Appointment Suggestions | Partially Implemented |
| Diagnosis Suggestions | Partially Implemented |
| Predictive Analytics | Partially Implemented |

### 5.23 EHR Integration

| Feature | Status |
|---------|--------|
| HL7 Configuration | Partially Implemented |
| FHIR Configuration | Partially Implemented |
| HL7 Message Sending | Partially Implemented |
| FHIR Resource Sending | Partially Implemented |

### 5.24 RFID Management

| Feature | Status |
|---------|--------|
| RFID Tag Management | Implemented |
| Tag Scanning | Implemented |
| Location Tracking | Implemented |
| Status Monitoring | Implemented |
| Bulk Updates | Implemented |

### 5.25 IoT Bed Monitoring

| Feature | Status |
|---------|--------|
| Bed Sensors | Implemented |
| Sensor Data Collection | Implemented |
| Occupancy Map | Implemented |
| Alert Management | Implemented |

### 5.26 Biometric & Security

| Feature | Status |
|---------|--------|
| Biometric Registration | Implemented |
| Biometric Verification | Implemented |
| Card Scanner Registration | Implemented |
| Card Scanner Verification | Implemented |

### 5.27 Visitor Management

| Feature | Status |
|---------|--------|
| Visitor Logging | Implemented |
| Badge Printing | Implemented |
| Check-out | Implemented |
| Analytics | Implemented |

### 5.28 Staff Management

| Feature | Status |
|---------|--------|
| Receptionist Management | Implemented |
| Pharmacist Management | Implemented |
| Lab Technician Management | Implemented |
| Accountant Management | Implemented |
| Case Handler Management | Implemented |
| Social Worker Management | Implemented |

### 5.29 Birth & Death Reports

| Feature | Status |
|---------|--------|
| Birth Reports | Implemented |
| Death Reports | Implemented |
| Operation Reports | Implemented |

### 5.30 Packages

| Feature | Status |
|---------|--------|
| Service Packages (CRUD) | Implemented |
| Package Items | Implemented |

---

## 6. User Roles

### Defined Roles (21)

| Role | Description | Access Level |
|------|-------------|--------------|
| Super Admin | Full system access | All modules, all permissions |
| Hospital Admin | Hospital operations management | Most modules, limited system config |
| Doctor | Patient care and medical operations | Clinical, patient, prescription, lab |
| Nurse | Patient care and assistance | Nursing, patients, vitals |
| Receptionist | Front desk operations | Registration, appointments, queue |
| Pharmacist | Medicine management | Pharmacy, prescriptions, inventory |
| Lab Technician | Laboratory operations | Lab tests, requests, results |
| Radiologist | Radiology operations | Radiology tests, requests |
| Accountant | Financial operations | Billing, payments, finance |
| Case Handler | Insurance and case management | Insurance, cases |
| Ambulance Operator | Ambulance services | Ambulance, emergency |
| HR Officer | Human resources | Employees, payroll, attendance |
| Patient | Limited access to own data | Portal, own records |
| System Auditor | Read-only access | View-only across modules |
| Support Staff | Minimal access | Limited operations |
| Telemedicine Doctor | Online consultations only | Telemedicine sessions |
| Inventory Manager | Stock management | Pharmacy, inventory, purchases |
| Procurement Officer | Purchasing | Suppliers, purchase orders |
| IT Support | System maintenance | System settings, users |
| Marketing Manager | CMS and communication | CMS, marketing suite |
| System AI Bot | Automated operations | AI features |

### Role-Permission Matrix

| Role | Patients | Clinical | Lab | Pharmacy | Billing | SHA | Reports | HR | Admin |
|------|----------|----------|-----|----------|---------|-----|---------|-----|-------|
| Super Admin | Full | Full | Full | Full | Full | Full | Full | Full | Full |
| Hospital Admin | Full | Full | Full | Full | Full | Full | Full | Full | Full |
| Doctor | View/Edit | Full | Request | View | View | View | View | No | No |
| Nurse | View/Edit | View/Edit | View | View | View | View | No | No | No |
| Receptionist | Full | View | View | No | View | View | View | No | No |
| Pharmacist | View | No | No | Full | View | No | View | No | No |
| Lab Technician | View | No | Full | No | No | No | View | No | No |
| Radiologist | View | View | Full | No | No | No | View | No | No |
| Accountant | View | No | No | View | Full | View | Full | View | No |
| Patient | Own | Own | Own | Own | Own | Own | Own | No | No |

---

## 7. Patient Workflow

### Registration Flow

1. **Patient arrives** at reception
2. **Registration form** completed with personal details, contacts, emergency info
3. **Auto-generated patient number** assigned
4. **Insurance information** recorded (if applicable)
5. **Patient profile** created in the system
6. **Appointment or walk-in** visit initiated

### Patient Journey

```
Registration → Appointment/Queue → Consultation → Diagnosis → Prescription/Lab Orders → Billing → Payment → Discharge/Follow-up
```

### Inpatient Journey

```
Registration → Admission → Ward Assignment → Bed Allocation → Treatment → Ward Rounds → Discharge Summary → Checkout
```

---

## 8. Clinical Workflow

### Outpatient Consultation

1. Patient arrives/is called from queue
2. Doctor opens patient record
3. **Vital signs** recorded (temperature, blood pressure, pulse, weight, etc.)
4. **Medical history** reviewed
5. **Clinical examination** performed
6. **Diagnosis** recorded (with ICD-10 codes)
7. **Prescriptions** issued
8. **Lab tests** ordered (if needed)
9. **Referral** made (if needed)
10. **Follow-up** scheduled

### Inpatient Management

1. Admission decision made
2. Bed allocation to appropriate ward
3. Admission notes created
4. Treatment plan established
5. Daily ward rounds
6. Medication administration tracked
7. Vital signs monitored
8. Progress notes maintained
9. Discharge planning
10. Discharge summary generated

---

## 9. Laboratory Workflow

1. **Lab request** created (by doctor or system)
2. **Test items** selected from catalog
3. **Sample collection** scheduled
4. **Tests performed** by lab technician
5. **Results recorded** in the system
6. **Results reviewed** and approved
7. **Report generated** (PDF/printable)
8. **Results delivered** to patient/doctor

---

## 10. Pharmacy Workflow

1. **Prescription received** from doctor
2. **Medicines identified** from prescription items
3. **Stock checked** for availability
4. **Dispensing** recorded
5. **Stock decremented** automatically
6. **Payment processed** (if applicable)
7. **Expiry tracking** monitored
8. **Reorder alerts** triggered for low stock

---

## 11. Billing Workflow

1. **Services rendered** recorded
2. **Invoice generated** automatically or manually
3. **Line items** added (consultations, procedures, medicines, lab tests)
4. **Discounts** applied (if applicable)
5. **Insurance coverage** calculated
6. **Patient portion** determined
7. **Payment received** (cash, M-Pesa, card)
8. **Receipt generated** (PDF or thermal)
9. **Outstanding balance** tracked
10. **Payment reminders** sent (automated)

---

## 12. Insurance / SHA Workflow

### SHA Integration Flow

1. **Member verification** — Patient's SHA number verified against registry
2. **Eligibility check** — Coverage status and benefits confirmed
3. **Pre-authorization** — Required services pre-approved
4. **Service delivery** — Patient receives treatment
5. **Claim preparation** — Services mapped to SHA service codes
6. **Claim submission** — Electronic claim sent to SHA
7. **Claim tracking** — Status monitored (submitted → under_review → approved/rejected)
8. **Payment reconciliation** — Remittance processed

### Current SHA Integration Status

- **ShaService** — Production-ready OAuth2 integration with DHA/EHA
- **EHA credentials** — Currently NOT configured in production (fallback to local database)
- **InsuranceApiController** — SIMULATED responses (not real API calls)
- **Local workflow** — Fully functional for manual SHA claim management
- **SHA member registry** — Database tables created and functional
- **ICD-10 codes** — 75 common codes seeded

---

## 13. Reporting

### Available Reports

| Report | Purpose | Export Formats |
|--------|---------|----------------|
| Patient Report | Patient statistics and demographics | PDF, Excel |
| Revenue Report | Financial revenue analysis | PDF, Excel |
| Appointment Report | Appointment statistics | PDF, Excel |
| Financial Report | Comprehensive financial overview | PDF, Excel |
| Daily Summary | Daily operations summary | PDF |
| Lab Report | Laboratory test statistics | PDF, Excel |
| Pharmacy Report | Medicine dispensing statistics | PDF, Excel |
| BI Dashboard | Business intelligence analytics | On-screen |
| Custom Reports | User-defined reports | PDF, Excel, CSV |

---

## 14. Security

### Implemented Security Controls

| Control | Implementation |
|---------|---------------|
| Authentication | Laravel Sanctum + Session-based |
| Password Hashing | Bcrypt (Laravel default) |
| Authorization | Spatie Permission (21 roles, 93 permissions) |
| CSRF Protection | Laravel CSRF tokens on all forms |
| XSS Protection | Blade template auto-escaping |
| SQL Injection | Laravel Eloquent ORM (parameterized queries) |
| Session Security | Database-backed sessions, HTTPS-only cookies |
| API Rate Limiting | 60 requests/minute per user/IP |
| Audit Logging | Spatie ActivityLog for all operations |
| HTTPS | SSL/TLS encryption via Let's Encrypt |
| File Upload Validation | Server-side validation on uploads |
| Two-Factor Authentication | Available for patient portal |
| API Authentication | Sanctum token-based |
| Role-based Access Control | Middleware-enforced per route |

### Security Recommendations

| Area | Recommendation |
|------|---------------|
| .env File | Restrict file permissions (currently world-readable) |
| MFA for Admin | Enable two-factor for admin accounts |
| Password Policy | Enforce minimum complexity requirements |
| API Rate Limiting | Review and adjust limits for production traffic |
| Backup Testing | Regularly test backup restoration |
| Security Headers | Add CSP, X-Frame-Options, HSTS headers |
| Input Validation | Review all forms for comprehensive validation |
| Dependency Updates | Regularly update composer packages |

---

## 15. System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────┐
│                    USERS                         │
├──────────┬──────────┬──────────┬────────────────┤
│  Admin   │  Doctor  │ Patient  │   Other Staff  │
└────┬─────┴────┬─────┴────┬─────┴───────┬────────┘
     │          │          │             │
     ▼          ▼          ▼             ▼
┌─────────────────────────────────────────────────┐
│              WEB APPLICATION                     │
│         (Blade + Alpine.js + Tailwind)           │
└────────────────────┬────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────┐
│              LARAVEL APPLICATION                  │
│                  (PHP 8.2)                        │
├──────────────────────────────────────────────────┤
│  Controllers │ Services │ Models │ Middleware     │
├──────────────────────────────────────────────────┤
│  Routes: Web (584+) │ API (Sanctum)              │
├──────────────────────────────────────────────────┤
│  Queue Workers │ Scheduler │ Jobs                 │
└──────┬───────────────┬───────────────┬───────────┘
       │               │               │
       ▼               ▼               ▼
┌──────────────┐ ┌────────────┐ ┌──────────────────┐
│   MySQL DB   │ │  Storage   │ │ External Services │
│ (133 tables) │ │ (uploads)  │ ├──────────────────┤
│              │ │            │ │ SHA/DHA (EHA)    │
│              │ │            │ │ M-Pesa           │
│              │ │            │ │ Twilio SMS       │
│              │ │            │ │ Pusher (WS)      │
│              │ │            │ │ OpenRouter AI    │
│              │ │            │ │ Zoom             │
└──────────────┘ └────────────┘ └──────────────────┘
```

### Data Flow

1. **User Request** → Apache → Laravel Router → Middleware (Auth, Role, Permission) → Controller → Service → Model → Database
2. **API Request** → Sanctum Auth → API Controller → Service → Model → JSON Response
3. **Background Jobs** → Queue Worker → Job Class → Service → Database/External API
4. **Real-time Updates** → Pusher WebSocket → Client-side Alpine.js

---

## 16. Technical Requirements

### Server Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP Version | 8.2+ | 8.3+ |
| Extensions | ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl, pcre, pdo, session, tokenizer, xml | All above + bcmath, gd, zip |
| Database | MySQL 8.0+ | MySQL 8.0+ |
| Web Server | Apache 2.4+ with mod_rewrite | Apache 2.4+ |
| Memory | 512MB | 2GB+ |
| Disk Space | 1GB | 10GB+ |
| Node.js | 18+ (for asset compilation) | 20+ |
| NPM | 9+ | 10+ |
| Composer | 2.x | 2.x |

### Required PHP Extensions

- ctype
- curl
- dom
- fileinfo
- filter
- hash
- mbstring
- openssl
- pcre
- pdo
- session
- tokenizer
- xml
- bcmath
- gd (for image processing)
- zip

---

## 17. Installation

### Step-by-Step Installation

```bash
# 1. Clone the repository
git clone https://github.com/dunte1/dunco-hms.git
cd dunco-hms

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Create environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# Set DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 7. Run migrations
php artisan migrate

# 8. Seed the database
php artisan db:seed

# 9. Build frontend assets
npm run build

# 10. Create storage symlink
php artisan storage:link

# 11. Set permissions
chmod -R 775 storage bootstrap/cache

# 12. Configure web server document root to /public

# 13. Start queue worker (background)
php artisan queue:work

# 14. Start scheduler (cron)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Default Login Credentials

After seeding:
- **Email:** admin@example.com
- **Password:** password

**Note:** Change these credentials immediately after first login.

---

## 18. Configuration

### Environment Variables

| Variable | Purpose | Example |
|----------|---------|---------|
| APP_NAME | Application name | DuncoHMS |
| APP_URL | Application URL | https://hmse.duncowebsolutions.co.ke |
| APP_ENV | Environment | production |
| APP_DEBUG | Debug mode | false |
| DB_CONNECTION | Database driver | mysql |
| DB_HOST | Database host | 127.0.0.1 |
| DB_PORT | Database port | 3306 |
| DB_DATABASE | Database name | [REDACTED] |
| DB_USERNAME | Database user | [REDACTED] |
| DB_PASSWORD | Database password | [REDACTED] |
| SESSION_DRIVER | Session storage | database |
| QUEUE_CONNECTION | Queue driver | database |
| MAIL_MAILER | Email driver | smtp |
| SANCTUM_STATEFUL_DOMAINS | Sanctum domains | hmse.duncowebsolutions.co.ke |
| EHA_ENV | SHA environment | uat |
| EHA_CLIENT_ID | SHA client ID | [EMPTY] |
| EHA_CLIENT_SECRET | SHA client secret | [EMPTY] |
| EHA_FACILITY_ID | Facility identifier | [EMPTY] |
| MPESA_BASE_URL | M-Pesa API URL | https://sandbox.safaricom.co.ke |
| MPESA_ENVIRONMENT | M-Pesa env | production |
| OPENROUTER_API_KEY | AI API key | [REDACTED] |
| TWILIO_SID | Twilio account | [REDACTED] |
| PUSHER_APP_ID | Pusher app | [REDACTED] |

---

## 19. Administration

### System Administration Features

- **User Management** — Create, edit, deactivate users
- **Role Management** — Create custom roles with specific permissions
- **Permission Management** — Fine-grained access control
- **System Settings** — Hospital name, logo, contact info, timezone
- **Branch Management** — Multi-branch hospital configuration
- **Audit Logs** — Track all system activities
- **Backup Management** — Database backup and restore
- **Theme Customization** — Colors, logos, dark mode
- **Localization** — Multi-language support
- **API Key Management** — External service API keys
- **Module Management** — Enable/disable system modules

---

## 20. User Management

### User Lifecycle

1. **Registration** — User registers or is created by admin
2. **Email Verification** — User verifies email address
3. **Admin Approval** — Admin approves user account
4. **Role Assignment** — Role assigned with specific permissions
5. **Active Status** — User can access the system
6. **Deactivation** — User access can be revoked

### User Statuses

- `pending` — Awaiting admin approval
- `active` — Approved and operational
- `inactive` — Deactivated by administrator

---

## 21. Backup and Recovery

### Backup System

- **Package:** spatie/laravel-backup v9.0
- **Driver:** Database-backed
- **Storage:** Local filesystem
- **Schedule:** Configurable via artisan commands

### Recovery Procedures

1. Access backup management in Settings
2. Select backup point
3. Initiate restore process
4. Verify data integrity
5. Resume operations

---

## 22. Maintenance

### Regular Maintenance Tasks

| Task | Frequency | Command |
|------|-----------|---------|
| Clear cache | Daily | `php artisan cache:clear` |
| Run queue workers | Continuous | `php artisan queue:work` |
| Run scheduler | Every minute | `php artisan schedule:run` |
| Database backup | Daily | Via spatie/laravel-backup |
| Log rotation | Weekly | Clear old logs |
| Update dependencies | Monthly | `composer update` |
| Security patches | As needed | Apply updates promptly |

---

## 23. Support

### Support Channels

- **Documentation:** This document and associated guides
- **GitHub Issues:** https://github.com/dunte1/dunco-hms/issues
- **Email:** dunthecan02@gmail.com
- **Website:** https://duncowebsolutions.co.ke/

---

## 24. Current Implementation Status

### Production Status

| Component | Status |
|-----------|--------|
| Core HMS Modules | Fully Implemented |
| SHA Integration | Code ready, EHA credentials not configured |
| M-Pesa Integration | Configured (sandbox URL in use) |
| SMS (Twilio) | Configured (needs production credentials) |
| AI Features | Fully Implemented (OpenRouter integration) |
| EHR (HL7/FHIR) | Fully Implemented (real message processing) |
| Telemedicine | Implemented |
| IoT Bed Monitoring | Implemented |
| RFID Management | Implemented |
| Biometric | Implemented |
| Marketing Suite | Implemented |
| CMS | Implemented |
| Patient Portal | Implemented |

### Database Status

- **Total Tables:** 133
- **Migrations Status:** All 110+ migrations completed
- **Production Data:** Minimal (1 user, 1 patient, 1 invoice, 1 employee)

---

## 25. Limitations / Outstanding Items

| Item | Priority | Description |
|------|----------|-------------|
| SHA EHA Credentials | High | Production EHA credentials not configured; system uses local DB fallback |
| M-Pesa Production | High | Using sandbox URL; needs production credentials |
| Twilio SMS | Medium | Production credentials placeholder |
| Mail Configuration | Medium | SMTP placeholder values |
| .env Security | High | File permissions too permissive (644) |
| Real Data | Medium | Production DB has minimal test data |
| .env Security | High | File permissions too permissive (644) |
| Frontend SPA | N/A | Uses Blade templates (by design, not a limitation) |

---

## 26. Future Enhancements

| Enhancement | Priority |
|-------------|----------|
| Complete SHA production integration | High |
| M-Pesa production deployment | High |
| Mobile app (React Native/Flutter) | Medium |
| SMS notifications via Twilio | Medium |
| Email notifications | Medium |
| Patient mobile app | Low |
| Telehealth video (WebRTC) | Medium |
| AI-powered diagnostics | Low |
| Advanced analytics dashboard | Low |
| Multi-hospital federation | Low |

---

## 27. Demo Access

### Demo URL

**https://hmse.duncowebsolutions.co.ke/**

### Demo Credentials

Demo credentials should be provisioned separately for prospective clients.

**Do not use production administrator credentials for demonstrations.**

---

## 28. Contact Information

**Dunco Web Solutions**  
Email: dunthecan02@gmail.com  
Website: https://duncowebsolutions.co.ke/  
GitHub: https://github.com/dunte1/dunco-hms

---

*Document generated from complete source code audit — September 2026*
