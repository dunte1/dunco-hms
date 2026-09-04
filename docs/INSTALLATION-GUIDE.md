# Dunco HMS — Installation Guide

## Requirements

### Server Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP Version | 8.2+ | 8.3+ |
| MySQL | 8.0+ | 8.0+ |
| Apache | 2.4+ | 2.4+ |
| Memory | 512MB | 2GB+ |
| Disk Space | 1GB | 10GB+ |
| Node.js | 18+ | 20+ |
| Composer | 2.x | 2.x |

### Required PHP Extensions

- ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl
- pcre, pdo, session, tokenizer, xml, bcmath, gd, zip

## Installation Steps

```bash
# 1. Clone repository
git clone https://github.com/dunte1/dunco-hms.git
cd dunco-hms

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Create .env file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_user
# DB_PASSWORD=your_password

# 7. Run migrations
php artisan migrate

# 8. Seed the database (creates admin user + roles)
php artisan db:seed

# 9. Build frontend assets
npm run build

# 10. Create storage symlink
php artisan storage:link

# 11. Set permissions
chmod -R 775 storage bootstrap/cache

# 12. Configure Apache document root to /public

# 13. Start queue worker
php artisan queue:work

# 14. Add cron job for scheduler
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Installation

1. Access the application at your configured URL
2. Login with default credentials (admin@example.com / password)
3. Change the admin password immediately
4. Configure hospital settings (name, logo, contact info)
5. Create user accounts and assign roles
6. Configure SHA/EHA credentials (if using SHA integration)
7. Configure M-Pesa credentials (if using mobile payments)
8. Configure Twilio credentials (if using SMS)

## Environment Variables

See the Configuration section of the main documentation for all environment variables.

## Verification

```bash
# Check application status
php artisan about

# Verify database connection
php artisan migrate:status

# Test queue worker
php artisan queue:work --once

# Check scheduled tasks
php artisan schedule:list
```
