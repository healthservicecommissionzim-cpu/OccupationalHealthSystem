#!/bin/bash

echo "🚀 Optimizing Laravel Application for Production..."

# Clear all caches first
echo "Clearing old caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Cache configurations
echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache

# Optimize Composer autoloader
echo "Optimizing Composer autoloader..."
composer install --optimize-autoloader --no-dev

# Set proper permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Optimization complete!"
echo ""
echo "Additional recommendations:"
echo "1. Enable OPcache in php.ini"
echo "2. Use Redis/Memcached for caching (update .env CACHE_DRIVER)"
echo "3. Enable gzip compression in web server"
echo "4. Use CDN for static assets"
