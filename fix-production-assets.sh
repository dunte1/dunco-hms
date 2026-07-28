#!/bin/bash

# Fix Production Vite Assets
# Run this on your production server

echo "🔧 Fixing production assets..."

# Navigate to project directory
cd /home/duncoweb/hmse.duncowebsolutions.co.ke

echo "📦 Installing dependencies..."
npm install

echo "🏗️  Building production assets..."
npm run build

echo "🔐 Setting file permissions..."
chmod -R 755 public/build
chown -R duncoweb:duncoweb public/build

echo "🧹 Clearing Laravel caches..."
php artisan optimize:clear

echo "⚡ Optimizing Laravel..."
php artisan optimize

echo "✅ Production assets fixed!"
echo "🎉 Please refresh your browser"

