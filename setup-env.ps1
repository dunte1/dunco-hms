# PowerShell script to update .env file with comprehensive configuration

Write-Host "Updating .env file with comprehensive configuration..." -ForegroundColor Green

# Read current .env
$currentEnv = Get-Content .env -ErrorAction SilentlyContinue

# Create comprehensive .env content
$newEnv = @"
# ============================================
# DUNCOHMS - Hospital Management System
# Comprehensive Environment Configuration
# ============================================

# ============================================
# APPLICATION SETTINGS
# ============================================
APP_NAME="DuncoHMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8001
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

# ============================================
# DATABASE CONFIGURATION
# ============================================
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# MySQL Configuration (Uncomment for production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=duncohms
# DB_USERNAME=root
# DB_PASSWORD=

DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_FOREIGN_KEYS=true
DB_QUEUE_CONNECTION=
DB_QUEUE_TABLE=jobs
DB_QUEUE=default
DB_QUEUE_RETRY_AFTER=90

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
MAIL_SCHEME=
MAIL_URL=
MAIL_LOG_CHANNEL=

POSTMARK_TOKEN=
RESEND_KEY=

# AWS SES
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=us-east-1

# ============================================
# SMS/COMMUNICATION SERVICES
# ============================================
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# ============================================
# QUEUE CONFIGURATION
# ============================================
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

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

# AWS S3 (Optional)
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

# Social Media OAuth - Facebook/Instagram
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/facebook

# Social Media OAuth - Twitter/X
TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
TWITTER_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/twitter

# Social Media OAuth - LinkedIn
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URI=http://127.0.0.1:8001/marketing/social-accounts/callback/linkedin

# ============================================
# PAYMENT GATEWAYS
# ============================================
STRIPE_KEY=
STRIPE_SECRET=

PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=sandbox

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

SLACK_BOT_USER_OAUTH_TOKEN=
SLACK_BOT_USER_DEFAULT_CHANNEL=

# ============================================
# BROADCASTING
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

"@

# Backup existing .env if it exists
if (Test-Path .env) {
    $backupName = ".env.backup.$(Get-Date -Format 'yyyyMMdd-HHmmss')"
    Copy-Item .env $backupName
    Write-Host "Backed up existing .env to $backupName" -ForegroundColor Yellow
    
    # Extract APP_KEY from existing .env
    $appKey = ($currentEnv | Select-String "APP_KEY=").ToString()
    if ($appKey) {
        $newEnv = $newEnv -replace "APP_KEY=", $appKey
        Write-Host "Preserved existing APP_KEY" -ForegroundColor Green
    }
}

# Write new .env
$newEnv | Out-File -FilePath .env -Encoding UTF8 -NoNewline
Write-Host "Created comprehensive .env file!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. If APP_KEY is empty, run: php artisan key:generate" -ForegroundColor White
Write-Host "2. Configure your API keys (OpenRouter, Social Media, etc.)" -ForegroundColor White
Write-Host "3. Run: php artisan config:clear" -ForegroundColor White

