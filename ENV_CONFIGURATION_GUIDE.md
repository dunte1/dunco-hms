# Comprehensive .env Configuration Guide for DuncoHMS

This document contains all environment variables needed for the complete Hospital Management System including the Marketing Module.

## Quick Setup

1. Copy the template below to create your `.env` file
2. Run `php artisan key:generate` to generate APP_KEY
3. Configure variables based on your needs
4. Run `php artisan config:clear` to clear cached config

## Complete .env Template

```env
# ============================================
# DUNCOHMS - Hospital Management System
# Comprehensive Environment Configuration
# ============================================

# ============================================
# APPLICATION SETTINGS
# ============================================
APP_NAME="DuncoHMS"
APP_ENV=local
APP_KEY=base64:jsnV7ExjSDxxw+LD5K95x3CyjfNvHqnkafzXLtJYfG8=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8001
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

# ============================================
# DATABASE CONFIGURATION
# ============================================
# SQLite (Default - for development)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# MySQL Configuration (Uncomment for production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=duncohms
# DB_USERNAME=root
# DB_PASSWORD=
# DB_SOCKET=

# Database Options
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_FOREIGN_KEYS=true

# ============================================
# MAIL CONFIGURATION
# ============================================
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@duncohms.com"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_LOG_CHANNEL=

# Mail Providers (Optional)
POSTMARK_TOKEN=
RESEND_KEY=

# AWS SES (Alternative)
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=us-east-1

# ============================================
# SMS/COMMUNICATION SERVICES
# ============================================
# Twilio SMS Configuration
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# ============================================
# QUEUE CONFIGURATION
# ============================================
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids
DB_QUEUE_CONNECTION=
DB_QUEUE_TABLE=jobs
DB_QUEUE=default
DB_QUEUE_RETRY_AFTER=90

# ============================================
# REDIS CONFIGURATION (Optional)
# ============================================
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=phpredis
REDIS_PREFIX=duncohms-database-
REDIS_DB=0
REDIS_CACHE_DB=1

# Redis Queue (Optional - for better performance)
REDIS_QUEUE_CONNECTION=default
REDIS_QUEUE=default
REDIS_QUEUE_RETRY_AFTER=90

# ============================================
# CACHE CONFIGURATION
# ============================================
CACHE_STORE=database
CACHE_PREFIX=duncohms-cache-
DB_CACHE_CONNECTION=
DB_CACHE_TABLE=cache
DB_CACHE_LOCK_CONNECTION=
DB_CACHE_LOCK_TABLE=

# ============================================
# SESSION CONFIGURATION
# ============================================
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
SESSION_STORE=database
SESSION_CONNECTION=
SESSION_TABLE=sessions
SESSION_COOKIE=laravel_session
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=false

# ============================================
# FILESYSTEM CONFIGURATION
# ============================================
FILESYSTEM_DISK=local

# AWS S3 (Optional - for cloud storage)
# AWS_BUCKET=
# AWS_URL=
# AWS_ENDPOINT=
# AWS_USE_PATH_STYLE_ENDPOINT=false

# ============================================
# AI & MARKETING MODULE CONFIGURATION
# ============================================
# OpenRouter AI (Recommended - Free tier available)
OPENROUTER_API_KEY=
OPENROUTER_URL=https://openrouter.ai/api/v1/chat/completions
OPENROUTER_MODEL=meta-llama/llama-3.1-8b-instruct:free

# Alternative: HuggingFace AI
HUGGINGFACE_API_KEY=

# Social Media OAuth (For Marketing Module)
# Facebook/Instagram
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/facebook

# Twitter/X API
TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
TWITTER_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/twitter

# LinkedIn API
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/linkedin

# ============================================
# PAYMENT GATEWAYS
# ============================================
# Stripe (International)
STRIPE_KEY=
STRIPE_SECRET=

# PayPal
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=sandbox

# M-Pesa (Kenya - East Africa)
MPESA_CONSUMER_KEY=
MPESA_CONSUMER_SECRET=
MPESA_SHORTCODE=
MPESA_PASSKEY=
MPESA_ENVIRONMENT=sandbox

# ============================================
# LOGGING & MONITORING
# ============================================
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug
LOG_DEPRECATIONS_CHANNEL=null

# Slack Notifications (Optional)
SLACK_BOT_USER_OAUTH_TOKEN=
SLACK_BOT_USER_DEFAULT_CHANNEL=

# ============================================
# BROADCASTING & REAL-TIME
# ============================================
BROADCAST_DRIVER=log
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# ============================================
# SECURITY SETTINGS
# ============================================
SANCTUM_STATEFUL_DOMAINS=127.0.0.1,localhost

# ============================================
# HORIZON (Queue Monitoring - Linux/Mac only)
# ============================================
# HORIZON_ENABLED=false
# HORIZON_BALANCE=max
# HORIZON_MAX_PROCESSES=10

# ============================================
# SYSTEM SETTINGS
# ============================================
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12
APP_MAINTENANCE_DRIVER=file
```

## Required Variables (Minimum Setup)

For basic functionality, you only need:

```env
APP_NAME="DuncoHMS"
APP_ENV=local
APP_KEY=base64:your-generated-key-here
APP_DEBUG=true
APP_URL=http://127.0.0.1:8001
DB_CONNECTION=sqlite
MAIL_MAILER=log
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

## Marketing Module Variables (Optional but Recommended)

```env
# AI Content Generation
OPENROUTER_API_KEY=your_openrouter_api_key_here

# Social Media OAuth (one or more as needed)
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=${APP_URL}/marketing/social-accounts/callback/facebook
```

## Setup Instructions

1. **Create .env file:**
   ```bash
   cp ENV_CONFIGURATION_GUIDE.md .env
   # Then edit .env and remove markdown formatting
   ```

2. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

3. **Configure Database:**
   - For SQLite (default): No action needed
   - For MySQL: Uncomment MySQL section and update credentials

4. **Configure Mail:**
   - Update MAIL_* variables for email functionality
   - Or use MAIL_MAILER=log for development

5. **Configure Marketing AI:**
   - Get free API key from https://openrouter.ai
   - Add OPENROUTER_API_KEY to .env

6. **Configure Social Media (Optional):**
   - Get OAuth credentials from each platform
   - Add to respective *_CLIENT_ID and *_CLIENT_SECRET

7. **Clear Config Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Variables by Module

### Core System
- APP_* - Application settings
- DB_* - Database configuration
- SESSION_* - Session management

### Marketing Module
- OPENROUTER_API_KEY - AI content generation
- FACEBOOK_* - Facebook/Instagram OAuth
- TWITTER_* - Twitter/X OAuth
- LINKEDIN_* - LinkedIn OAuth

### Queue & Jobs
- QUEUE_CONNECTION - Queue driver
- REDIS_* - Redis configuration (optional)
- DB_QUEUE_* - Database queue settings

### Communication
- MAIL_* - Email configuration
- TWILIO_* - SMS configuration

### Payment Gateways
- STRIPE_* - Stripe payments
- PAYPAL_* - PayPal payments
- MPESA_* - M-Pesa payments

