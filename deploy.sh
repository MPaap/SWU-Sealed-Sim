#!/bin/bash

APP_PATH="/var/www/sealed"
echo "Deploying"

# Pull latest code
cd $APP_PATH
git pull origin main

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install and build frontend assets if package.json exists
if [ -f "package.json" ]; then
    echo "Installing Node.js dependencies..."
    npm ci --production=false

    echo "Building production assets..."
    npm run build
fi

# Clear and cache config
php artisan down
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Set permissions
laravel-permissions $APP_PATH

# Restart queue workers
# sudo supervisorctl restart laravel-${ENVIRONMENT}-worker:*

# Bring site back up
php artisan up
echo "Deployment completed!"
