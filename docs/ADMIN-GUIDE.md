# Dunco HMS — Administrator Guide

## System Administration

### Accessing Admin Features

Admin features are accessible to users with **Super Admin** or **Hospital Admin** roles.

## User Management

### Creating Users

1. Navigate to **System → Users**
2. Click **Add User**
3. Fill in name, email, password
4. Select status (pending/active)
5. Assign role(s)
6. Save

### Managing Roles

1. Navigate to **Admin → Roles**
2. View existing roles (21 predefined)
3. Create custom roles
4. Assign permissions to roles

### Permission Categories

- Patient Management
- Appointments & Scheduling
- Prescriptions & Medicines
- Lab & Radiology
- Billing & Finance
- IPD/OPD
- Doctors & Staff
- Bed & Room Management
- Accounting & Payments
- Reports
- Settings
- CMS & Communication
- Multi-Hospital / Tenancy
- AI & Advanced Features
- System Administration
- Marketing Suite

## Hospital Configuration

### General Settings

- Hospital name and logo
- Contact information
- Address
- Timezone
- Default language

### Branch Management

- Add/manage hospital branches
- Branch-specific settings
- Multi-branch reporting

### System Settings

- Theme customization
- Dark mode toggle
- Localization (English, French, Swahili, Arabic)
- API key management
- Map configuration

## Audit & Compliance

### Audit Logs

- View all system activities
- Filter by user, action, date
- Export audit trail

### Activity Logs (Spatie)

- Track model changes
- Log user actions
- Custom event logging

## Backup & Recovery

### Creating Backups

1. Navigate to **Settings → Backup**
2. Click **Create Backup**
3. Wait for completion
4. Download backup file

### Restoring from Backup

1. Navigate to **Settings → Backup**
2. Select backup file
3. Click **Restore**
4. Verify data integrity

## Module Management

### Enable/Disable Modules

1. Navigate to **Admin → Modules**
2. Toggle module status
3. Changes take effect immediately

### Available Modules

- Patient Management
- Appointments
- Pharmacy
- Laboratory
- Radiology
- Billing
- Insurance/SHA
- HR Management
- Blood Bank
- Ambulance
- Reports
- CMS
- Marketing
- Telemedicine
- RFID
- IoT Bed Monitoring
- Biometric
- Patient Portal
- AI Features
- EHR Integration

## Security

### Password Policy

- Minimum 8 characters
- Recommended: mixed case, numbers, special characters

### Two-Factor Authentication

- Available for patient portal users
- Recommended for admin accounts

### API Security

- Sanctum token-based authentication
- Rate limiting: 60 requests/minute
- CORS configuration

## Maintenance

### Regular Tasks

| Task | Frequency | Method |
|------|-----------|--------|
| Clear cache | Daily | `php artisan cache:clear` |
| Review audit logs | Weekly | Admin panel |
| Database backup | Daily | Automated |
| Update dependencies | Monthly | `composer update` |
| Security patches | As needed | Apply updates |
| Review user accounts | Monthly | Admin panel |
| Check queue workers | Daily | `php artisan queue:work` |

### Queue Management

```bash
# Start queue worker
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry {id}
```

### Scheduler

```bash
# View scheduled tasks
php artisan schedule:list

# Test scheduler
php artisan schedule:run
```

## Multi-Currency

1. Navigate to **Admin → Currency**
2. Add currencies with exchange rates
3. Set default currency
4. Currency conversion applied automatically

## Localization

### Supported Languages

- English (default)
- French
- Swahili
- Arabic

### Switching Languages

Users can switch language via the language selector in the navigation bar.
