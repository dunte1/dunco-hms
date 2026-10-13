# HMS Test Suite

## What's included

### PHPUnit tests (`tests/`)
Run locally or on server (no Node/npm required):

```bash
php artisan test
# or
vendor/bin/phpunit
```

| Suite | Coverage |
|-------|----------|
| `tests/Feature/Auth/LoginTest.php` | Login/logout, invalid credentials, guest redirect |
| `tests/Feature/Patient/PatientFlowTest.php` | Full journey: registration → triage → consult → lab → Rx → dispense → finalize; illegal transitions; queue sync |
| `tests/Feature/Clinical/PrescriptionLabTest.php` | HTTP flows: prescribe, lab order, lab results, dispense, start consultation, finalize |
| `tests/Feature/Document/DocumentGenerationTest.php` | PDF generation (invoice, lab report), DocumentService branding/types, email template existence |
| `tests/Feature/Mail/EmailTest.php` | Email templates render, notification channels (mail+database), invoice email, lab results email |
| `tests/Feature/Roles/RoleAccessTest.php` | All roles, permission matrix, middleware 403, sidebar filtering |
| `tests/Feature/Sidebar/SidebarTest.php` | Sidebar routes exist, no parameterized routes, key HMS routes |
| `tests/Unit/PatientFlowStateTest.php` | OPD state machine map, terminal states, queue map |

### Production E2E suite (`storage/framework/testing/production_e2e_test.php`)
Runs on the server against live data as **every user role**. Tests:
- Login as Super Admin, Hospital Admin, Doctor, Nurse, Receptionist, Patient, Lab Tech, Pharmacist, Accountant
- Crawl role-appropriate pages (200 HTML, not JSON)
- PDF document generation
- Email template rendering + Mailer config
- Full patient flow smoke test
- No Node/npm/build required

```bash
php storage/framework/testing/production_e2e_test.php
php storage/framework/testing/production_e2e_test.php --user=doctor
php storage/framework/testing/production_e2e_test.php --mail-only
```

## Test users (from TestUserSeeder)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | dunthecan02@gmail.com | password |
| Hospital Admin | admin@duncohms.com | password |
| Doctor | doctor@duncohms.com | password |
| Nurse | nurse@duncohms.com | password |
| Receptionist | receptionist@duncohms.com | password |
| Patient | patient@duncohms.com | password |
| Lab Tech | james.lab@hospital.com | password |
| Pharmacist | david.pharmacy@hospital.com | password |
| Accountant | charles.accounting@hospital.com | password |

## Database for PHPUnit
`phpunit.xml` uses **SQLite in-memory** (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`) so tests run without MySQL.

To run against MySQL instead:
```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="duncohms_test"/>
```

## Email testing
- PHPUnit: `MAIL_MAILER=array` (asserts notifications are queued/sent)
- Production E2E: renders all email templates + attempts `Mail::raw`; live SMTP delivery depends on production `.env` (`MAIL_MAILER=smtp`)
