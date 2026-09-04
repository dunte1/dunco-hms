# Dunco Hospital Management System (Dunco HMS)

A comprehensive, web-based hospital management system built with Laravel 12, PHP 8.2+, Blade templates, Alpine.js, and Tailwind CSS.

**Version:** 2.4  
**Production URL:** https://hmse.duncowebsolutions.co.ke/  
**License:** Proprietary — Dunco Web Solutions

---

## Overview

Dunco HMS is a full-featured healthcare management platform designed for hospitals, clinics, and medical facilities in Kenya. It provides end-to-end management of hospital operations including patient registration, clinical workflows, billing, pharmacy, laboratory, radiology, human resources, and SHA (Social Health Authority) insurance integration.

## Key Features

- **Patient Management** — Registration, profiles, medical history, diagnosis (ICD-10)
- **Clinical Workflow** — OPD/IPD consultations, vital signs, prescriptions
- **Pharmacy** — Medicine catalog, prescriptions, stock management, e-prescriptions
- **Laboratory** — Test catalog, requests, results, equipment management
- **Radiology** — Imaging tests, requests, reports
- **Billing & Finance** — Invoices, payments, receipts, M-Pesa integration
- **Insurance/SHA** — SHA member verification, eligibility, pre-authorization, claims
- **Human Resources** — Employees, payroll, attendance, leave, training
- **Queue Management** — Token generation, display board, kiosk
- **Telemedicine** — Video consultation sessions with Zoom integration
- **Patient Portal** — Self-service portal with 2FA
- **Reports & Analytics** — Financial, patient, revenue reports with export
- **CMS & Marketing** — Blog, gallery, careers, social media management
- **RFID & IoT** — Tag tracking, bed sensor monitoring
- **Biometric Security** — Fingerprint and card scanner integration
- **AI Features** — Elliana-D virtual nurse assistant
- **Multi-Branch** — Support for multiple hospital locations
- **Multi-Language** — English, French, Swahili, Arabic

## Technology Stack

| Component | Technology |
|-----------|------------|
| Backend | Laravel 12.x / PHP 8.2+ |
| Frontend | Blade + Alpine.js + Tailwind CSS |
| Database | MySQL 8.0+ |
| Build Tool | Vite 7.0+ |
| Authentication | Laravel Sanctum |
| Authorization | Spatie Permission (21 roles, 93 permissions) |
| PDF | barryvdh/laravel-dompdf |
| Excel | maatwebsite/excel |
| SMS | Twilio SDK |
| Logging | spatie/laravel-activitylog |
| Backups | spatie/laravel-backup |

## Installation

```bash
# Clone repository
git clone https://github.com/dunte1/dunco-hms.git
cd dunco-hms

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Create storage link
php artisan storage:link

# Start queue worker
php artisan queue:work
```

See [docs/INSTALLATION-GUIDE.md](docs/INSTALLATION-GUIDE.md) for complete instructions.

## Documentation

| Document | Description |
|----------|-------------|
| [Complete Documentation](docs/DUNCO-HMS-COMPLETE-DOCUMENTATION.md) | Full system documentation |
| [Installation Guide](docs/INSTALLATION-GUIDE.md) | Step-by-step installation |
| [User Guide](docs/USER-GUIDE.md) | End-user documentation |
| [Admin Guide](docs/ADMIN-GUIDE.md) | Administrator documentation |
| [SHA Integration](docs/SHA-INTEGRATION.md) | SHA/EHA integration guide |
| [API Documentation](docs/API-DOCUMENTATION.md) | REST API reference |
| [Security](docs/SECURITY.md) | Security controls and recommendations |
| [Modules & Features](docs/MODULES-AND-FEATURES.md) | Complete feature matrix |
| [Role-Permission Matrix](docs/ROLE-PERMISSION-MATRIX.md) | Access control matrix |
| [Demo Guide](docs/DEMO-GUIDE.md) | Demonstration guide |

## SHA Integration Status

The system includes SHA (Social Health Authority) integration through Kenya's DHA Health Interoperability Engine (EHA). The `ShaService` provides OAuth2 authentication, member verification, eligibility checks, pre-authorization, and claims submission.

**Current Status:** The SHA service code is production-ready, but EHA credentials have not been configured in the production environment. The system falls back to local database operations when EHA is unavailable.

See [docs/SHA-INTEGRATION.md](docs/SHA-INTEGRATION.md) for details.

## Demo

**URL:** https://hmse.duncowebsolutions.co.ke/

Demo credentials should be provisioned separately for prospective clients. Do not use production credentials for demonstrations.

## Database

- **133 tables** covering all hospital operations
- **110+ migrations** (all completed)
- **110+ Eloquent models**

## Security

- Role-based access control (21 roles, 93 permissions)
- Sanctum API authentication
- CSRF, XSS, SQL injection protection
- Activity logging and audit trails
- HTTPS enforcement

See [docs/SECURITY.md](docs/SECURITY.md) for complete security documentation.

## Support

- **Email:** dunthecan02@gmail.com
- **GitHub Issues:** https://github.com/dunte1/dunco-hms/issues
- **Website:** https://duncowebsolutions.co.ke/

## License

Proprietary — Dunco Web Solutions. All rights reserved.
