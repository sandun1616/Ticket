#!/bin/bash
# ITTICKET Deployment Script for GoDaddy
# Run this on your GoDaddy server via SSH

set -e

echo "=== ITTICKET Deployment Script for elastogroup.com ==="

# Navigate to public_html
cd ~/public_html

# Check if Laravel is installed
if [ ! -f "artisan" ]; then
    echo "❌ ERROR: Laravel installation not found"
    exit 1
fi

echo "📦 Setting up Laravel environment..."

# Create .env from template if not exists
if [ ! -f ".env" ]; then
    echo "⚠️  .env not found. Creating from .env.production..."
    cp .env.production .env
fi

# Install/update dependencies
echo "📥 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📥 Installing Node dependencies..."
npm ci --production

# Build frontend assets
echo "🔨 Building frontend assets..."
npm run build

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Seed database (optional - comment out if you have existing data)
# php artisan db:seed --force

# Clear caches and optimize
echo "✨ Optimizing application..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting folder permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads/ 2>/dev/null || true

# Create symbolic link for storage if needed
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

echo ""
echo "✅ Deployment Complete!"
echo "🌐 Access your app at: https://elastogroup.com"
echo ""
echo "Next steps:"
echo "1. Verify .env database credentials are correct"
echo "2. Test the application at https://elastogroup.com"
echo "3. Check logs: tail -f storage/logs/laravel.log"
