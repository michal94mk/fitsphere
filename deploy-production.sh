#!/bin/bash

# FitSphere Production Deployment Script
# Run this script on your production server after uploading files

echo "🚀 Starting FitSphere production deployment..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found! Copy from production-env-example.txt and configure."
    exit 1
fi

# Install PHP dependencies (production optimized)
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install and build frontend assets
echo "🎨 Building frontend assets..."
npm ci --production
npm run build

# Generate application key if not set
echo "🔑 Configuring application..."
php artisan key:generate --no-interaction

# Clear and cache configuration
echo "🔧 Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database if needed (comment out if not needed)
# php artisan db:seed --no-interaction

# Set proper permissions
echo "🔐 Setting file permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage symlink
php artisan storage:link --no-interaction

# Clear application cache one more time
php artisan cache:clear

echo "✅ Deployment completed successfully!"
echo ""
echo "📋 Post-deployment checklist:"
echo "- [ ] Verify .env configuration"
echo "- [ ] Test database connection"
echo "- [ ] Test email sending"
echo "- [ ] Test API integrations"
echo "- [ ] Set up queue workers (php artisan queue:work --daemon)"
echo "- [ ] Configure web server SSL"
echo "- [ ] Set up monitoring and backups"
echo ""
echo "🚀 Your FitSphere application should now be running!" 