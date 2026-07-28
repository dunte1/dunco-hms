#!/bin/bash

# M-Pesa Setup Verification Script
# Run this on your server to verify all M-Pesa components are set up correctly

echo "🧪 M-PESA SETUP VERIFICATION"
echo "================================"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# 1. Check if test command exists
echo -e "${CYAN}1. Checking Test Command...${NC}"
if [ -f "app/Console/Commands/TestMpesaStk.php" ]; then
    echo -e "${GREEN}✅ Test command file exists${NC}"
else
    echo -e "${RED}❌ Test command file NOT found${NC}"
    echo "   Upload: app/Console/Commands/TestMpesaStk.php"
fi
echo ""

# 2. Check M-Pesa Controller
echo -e "${CYAN}2. Checking M-Pesa Callback Controller...${NC}"
if [ -f "app/Http/Controllers/Hms/MpesaCallbackController.php" ]; then
    echo -e "${GREEN}✅ MpesaCallbackController exists${NC}"
    
    # Check if all methods exist
    if grep -q "handleCallback" app/Http/Controllers/Hms/MpesaCallbackController.php && \
       grep -q "handleResult" app/Http/Controllers/Hms/MpesaCallbackController.php && \
       grep -q "handleConfirmation" app/Http/Controllers/Hms/MpesaCallbackController.php && \
       grep -q "handleValidation" app/Http/Controllers/Hms/MpesaCallbackController.php; then
        echo -e "${GREEN}✅ All callback methods present${NC}"
    else
        echo -e "${YELLOW}⚠️  Some callback methods missing${NC}"
    fi
else
    echo -e "${RED}❌ MpesaCallbackController NOT found${NC}"
    echo "   Upload: app/Http/Controllers/Hms/MpesaCallbackController.php"
fi
echo ""

# 3. Check Routes
echo -e "${CYAN}3. Checking Routes...${NC}"
php artisan route:list 2>/dev/null | grep -i mpesa > /tmp/mpesa_routes.txt
if [ -s /tmp/mpesa_routes.txt ]; then
    echo -e "${GREEN}✅ M-Pesa routes found:${NC}"
    cat /tmp/mpesa_routes.txt | sed 's/^/   /'
    
    # Check specific routes
    if grep -q "mpesa/callback" /tmp/mpesa_routes.txt; then
        echo -e "${GREEN}   ✓ /api/mpesa/callback${NC}"
    else
        echo -e "${RED}   ✗ /api/mpesa/callback missing${NC}"
    fi
    
    if grep -q "mpesa/result" /tmp/mpesa_routes.txt; then
        echo -e "${GREEN}   ✓ /api/mpesa/result${NC}"
    else
        echo -e "${RED}   ✗ /api/mpesa/result missing${NC}"
    fi
    
    if grep -q "mpesa/confirmation" /tmp/mpesa_routes.txt; then
        echo -e "${GREEN}   ✓ /api/mpesa/confirmation${NC}"
    else
        echo -e "${RED}   ✗ /api/mpesa/confirmation missing${NC}"
    fi
    
    if grep -q "mpesa/validation" /tmp/mpesa_routes.txt; then
        echo -e "${GREEN}   ✓ /api/mpesa/validation${NC}"
    else
        echo -e "${RED}   ✗ /api/mpesa/validation missing${NC}"
    fi
else
    echo -e "${RED}❌ No M-Pesa routes found${NC}"
    echo "   Check routes/api.php"
fi
rm -f /tmp/mpesa_routes.txt
echo ""

# 4. Check Environment Configuration
echo -e "${CYAN}4. Checking Environment Configuration...${NC}"
if [ -f ".env" ]; then
    if grep -q "MPESA_CONSUMER_KEY=" .env && grep -q "MPESA_CONSUMER_SECRET=" .env; then
        MPESA_KEY=$(grep "^MPESA_CONSUMER_KEY=" .env | cut -d '=' -f2)
        if [ -n "$MPESA_KEY" ] && [ "$MPESA_KEY" != "" ]; then
            echo -e "${GREEN}✅ MPESA_CONSUMER_KEY configured${NC}"
        else
            echo -e "${RED}❌ MPESA_CONSUMER_KEY is empty${NC}"
        fi
        
        MPESA_SECRET=$(grep "^MPESA_CONSUMER_SECRET=" .env | cut -d '=' -f2)
        if [ -n "$MPESA_SECRET" ] && [ "$MPESA_SECRET" != "" ]; then
            echo -e "${GREEN}✅ MPESA_CONSUMER_SECRET configured${NC}"
        else
            echo -e "${RED}❌ MPESA_CONSUMER_SECRET is empty${NC}"
        fi
        
        if grep -q "MPESA_SHORTCODE=" .env; then
            echo -e "${GREEN}✅ MPESA_SHORTCODE configured${NC}"
        else
            echo -e "${RED}❌ MPESA_SHORTCODE missing${NC}"
        fi
        
        if grep -q "MPESA_PASSKEY=" .env; then
            echo -e "${GREEN}✅ MPESA_PASSKEY configured${NC}"
        else
            echo -e "${RED}❌ MPESA_PASSKEY missing${NC}"
        fi
    else
        echo -e "${RED}❌ M-Pesa configuration missing in .env${NC}"
    fi
else
    echo -e "${RED}❌ .env file not found${NC}"
fi
echo ""

# 5. Check PaymentGatewayService
echo -e "${CYAN}5. Checking Payment Gateway Service...${NC}"
if [ -f "app/Services/PaymentGatewayService.php" ]; then
    echo -e "${GREEN}✅ PaymentGatewayService exists${NC}"
    
    if grep -q "processMpesaPayment" app/Services/PaymentGatewayService.php; then
        echo -e "${GREEN}✅ processMpesaPayment method exists${NC}"
    else
        echo -e "${RED}❌ processMpesaPayment method missing${NC}"
    fi
    
    if grep -q "getMpesaAccessToken" app/Services/PaymentGatewayService.php; then
        echo -e "${GREEN}✅ getMpesaAccessToken method exists${NC}"
    else
        echo -e "${RED}❌ getMpesaAccessToken method missing${NC}"
    fi
else
    echo -e "${RED}❌ PaymentGatewayService NOT found${NC}"
fi
echo ""

# 6. Test OAuth Token (if test script exists)
echo -e "${CYAN}6. Testing M-Pesa OAuth...${NC}"
if [ -f "test-mpesa-oauth.php" ]; then
    php test-mpesa-oauth.php
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ OAuth test passed${NC}"
    else
        echo -e "${RED}❌ OAuth test failed${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  test-mpesa-oauth.php not found (optional)${NC}"
fi
echo ""

# 7. Check Database Tables
echo -e "${CYAN}7. Checking Database Tables...${NC}"
php artisan tinker --execute="
try {
    \$payments = DB::table('payments')->count();
    echo '✅ Payments table exists (' . \$payments . ' records)' . PHP_EOL;
    
    \$invoices = DB::table('invoices')->count();
    echo '✅ Invoices table exists (' . \$invoices . ' records)' . PHP_EOL;
    
    // Check if status and transaction_data columns exist
    \$columns = DB::select('SHOW COLUMNS FROM payments');
    \$hasStatus = false;
    \$hasTransactionData = false;
    foreach (\$columns as \$col) {
        if (\$col->Field === 'status') \$hasStatus = true;
        if (\$col->Field === 'transaction_data') \$hasTransactionData = true;
    }
    
    if (\$hasStatus) {
        echo '✅ payments.status column exists' . PHP_EOL;
    } else {
        echo '❌ payments.status column missing - run migration' . PHP_EOL;
    }
    
    if (\$hasTransactionData) {
        echo '✅ payments.transaction_data column exists' . PHP_EOL;
    } else {
        echo '❌ payments.transaction_data column missing - run migration' . PHP_EOL;
    }
} catch (\Exception \$e) {
    echo '❌ Database error: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null
echo ""

# 8. Check File Permissions
echo -e "${CYAN}8. Checking File Permissions...${NC}"
if [ -w "storage/logs" ]; then
    echo -e "${GREEN}✅ storage/logs is writable${NC}"
else
    echo -e "${RED}❌ storage/logs is NOT writable${NC}"
    echo "   Run: chmod -R 775 storage"
fi

if [ -w "storage/app/public" ]; then
    echo -e "${GREEN}✅ storage/app/public is writable${NC}"
else
    echo -e "${RED}❌ storage/app/public is NOT writable${NC}"
    echo "   Run: chmod -R 775 storage"
fi
echo ""

# 9. Check Storage Link
echo -e "${CYAN}9. Checking Storage Symlink...${NC}"
if [ -L "public/storage" ] || [ -d "public/storage" ]; then
    echo -e "${GREEN}✅ public/storage exists${NC}"
    if [ -d "storage/app/public/settings" ]; then
        echo -e "${GREEN}✅ storage/app/public/settings directory exists${NC}"
    else
        echo -e "${YELLOW}⚠️  storage/app/public/settings directory missing (will be created on first upload)${NC}"
    fi
else
    echo -e "${RED}❌ public/storage symlink missing${NC}"
    echo "   Run: php artisan storage:link"
fi
echo ""

# 10. Check Logs for Errors
echo -e "${CYAN}10. Recent M-Pesa Log Entries...${NC}"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 5 M-Pesa related log entries:"
    tail -100 storage/logs/laravel.log | grep -i mpesa | tail -5 | sed 's/^/   /'
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}   (No M-Pesa logs found)${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Log file not found${NC}"
fi
echo ""

# 11. Verify Cache
echo -e "${CYAN}11. Verifying Configuration Cache...${NC}"
php artisan config:clear > /dev/null 2>&1
echo -e "${GREEN}✅ Configuration cache cleared${NC}"
php artisan route:clear > /dev/null 2>&1
echo -e "${GREEN}✅ Route cache cleared${NC}"
echo ""

# Summary
echo ""
echo "================================"
echo -e "${CYAN}VERIFICATION COMPLETE${NC}"
echo "================================"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. If any files are missing, upload them from your local machine"
echo "2. Run: php artisan config:cache (if in production)"
echo "3. Run: php artisan route:cache (if in production)"
echo "4. Test STK Push: php artisan mpesa:test-stk 254746979588 10"
echo "5. Ensure APP_URL in .env points to your public domain"
echo ""

