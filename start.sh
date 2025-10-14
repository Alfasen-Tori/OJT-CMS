#!/bin/bash

# Generate application key if not set
php artisan key:generate --force

# Clear and cache config
php artisan config:cache

# Clear and cache routes
php artisan route:cache

# Clear and cache views
php artisan view:cache

# Start PHP-FPM
php-fpm &

# Start nginx
nginx -g "daemon off;"