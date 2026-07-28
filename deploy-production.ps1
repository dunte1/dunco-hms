# ============================================
# DUNCOHMS - Production Deployment Script
# ============================================

Write-Host "🏥 DuncoHMS Production Deployment Script" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green

# Check if running as administrator
if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Write-Host "❌ This script requires administrator privileges. Please run as administrator." -ForegroundColor Red
    exit 1
}

# Check PHP version
Write-Host "🔍 Checking PHP version..." -ForegroundColor Yellow
$phpVersion = php --version
if ($phpVersion -match "PHP 8\.[2-9]") {
    Write-Host "✅ PHP version is compatible" -ForegroundColor Green
} else {
    Write-Host "❌ PHP 8.2+ is required. Current version: $phpVersion" -ForegroundColor Red
    exit 1
}

# Check Composer
Write-Host "🔍 Checking Composer..." -ForegroundColor Yellow
$composerVersion = composer --version
if ($composerVersion -match "Composer version") {
    Write-Host "✅ Composer is installed" -ForegroundColor Green
} else {
    Write-Host "❌ Composer is not installed or not in PATH" -ForegroundColor Red
    exit 1
}

# Check Node.js
Write-Host "🔍 Checking Node.js..." -ForegroundColor Yellow
$nodeVersion = node --version
if ($nodeVersion -match "v1[8-9]|v2[0-9]") {
    Write-Host "✅ Node.js version is compatible" -ForegroundColor Green
} else {
    Write-Host "❌ Node.js 18+ is required. Current version: $nodeVersion" -ForegroundColor Red
    exit 1
}

# Install/Update dependencies
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Failed to install PHP dependencies" -ForegroundColor Red
    exit 1
}

Write-Host "📦 Installing Node.js dependencies..." -ForegroundColor Yellow
npm install
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Failed to install Node.js dependencies" -ForegroundColor Red
    exit 1
}

# Build assets
Write-Host "🔨 Building production assets..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Failed to build assets" -ForegroundColor Red
    exit 1
}

# Generate application key if not exists
Write-Host "🔑 Generating application key..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item "env.production" ".env"
    Write-Host "✅ Created .env file from production template" -ForegroundColor Green
}

php artisan key:generate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Failed to generate application key" -ForegroundColor Red
    exit 1
}

# Clear and cache configurations
Write-Host "🧹 Clearing and caching configurations..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
Write-Host "⚡ Caching for production..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
Write-Host "🔒 Setting proper permissions..." -ForegroundColor Yellow
# Set storage and bootstrap/cache permissions
icacls "storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "bootstrap/cache" /grant "IIS_IUSRS:(OI)(CI)F" /T

# Create production zip
Write-Host "📦 Creating production zip..." -ForegroundColor Yellow
$zipName = "duncohms-production-$(Get-Date -Format 'yyyyMMdd-HHmmss').zip"

# Files and folders to exclude from production zip
$excludeItems = @(
    "node_modules",
    ".git",
    ".gitignore",
    ".env.backup.*",
    "tests",
    "testsprite_tests",
    "*.md",
    "*.txt",
    "*.json",
    "*.sh",
    "*.ps1",
    "env.production",
    ".env.example",
    "composer.json.temp",
    "workflow.json",
    "features.txt",
    "modules.txt",
    "roles.txt",
    "implementation-progress-report.json",
    "missing-features-analysis.json"
)

# Create zip using PowerShell
$excludePattern = ($excludeItems | ForEach-Object { "`"$_`"" }) -join ","
$zipCommand = "Compress-Archive -Path * -DestinationPath `"$zipName`" -Exclude $excludePattern -Force"

try {
    Invoke-Expression $zipCommand
    Write-Host "✅ Production zip created: $zipName" -ForegroundColor Green
} catch {
    Write-Host "❌ Failed to create production zip: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Display production readiness summary
Write-Host "`n🎉 PRODUCTION DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host "✅ All dependencies installed" -ForegroundColor Green
Write-Host "✅ Assets built for production" -ForegroundColor Green
Write-Host "✅ Configurations cached" -ForegroundColor Green
Write-Host "✅ Permissions set correctly" -ForegroundColor Green
Write-Host "✅ Production zip created: $zipName" -ForegroundColor Green

Write-Host "`n📋 NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Upload the zip file to your production server" -ForegroundColor White
Write-Host "2. Extract the zip file on your server" -ForegroundColor White
Write-Host "3. Update the .env file with your production settings" -ForegroundColor White
Write-Host "4. Run: php artisan migrate --force" -ForegroundColor White
Write-Host "5. Run: php artisan db:seed" -ForegroundColor White
Write-Host "6. Set up your web server (Apache/Nginx)" -ForegroundColor White
Write-Host "7. Configure SSL certificate" -ForegroundColor White
Write-Host "8. Set up database backups" -ForegroundColor White

Write-Host "`n🔧 PRODUCTION CHECKLIST:" -ForegroundColor Yellow
Write-Host "□ Update APP_URL in .env" -ForegroundColor White
Write-Host "□ Configure database credentials" -ForegroundColor White
Write-Host "□ Set up email configuration" -ForegroundColor White
Write-Host "□ Configure payment gateways" -ForegroundColor White
Write-Host "□ Set up SSL certificate" -ForegroundColor White
Write-Host "□ Configure file permissions" -ForegroundColor White
Write-Host "□ Set up monitoring and logging" -ForegroundColor White
Write-Host "□ Test all functionality" -ForegroundColor White

Write-Host "`n📞 SUPPORT:" -ForegroundColor Yellow
Write-Host "For deployment support, refer to the SYSTEM_SPECIFICATIONS_DOCUMENT.md" -ForegroundColor White
Write-Host "For configuration help, refer to ENV_CONFIGURATION_GUIDE.md" -ForegroundColor White

Write-Host "`n🎊 DuncoHMS is ready for production! 🎊" -ForegroundColor Green
