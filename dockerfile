# ============================
# 1. Base PHP Image
# ============================
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ============================
# 2. Node.js + NPM for Vite build
# ============================
FROM node:18 AS nodebuilder

WORKDIR /app

# Copy package files and install dependencies
COPY package*.json ./
RUN npm install

# Copy all app files and build
COPY . .
RUN npm run build

# ============================
# 3. Final Build Stage (PHP + built assets)
# ============================
FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies again (same as before)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel app (from builder)
COPY . .

# Copy built frontend assets
COPY --from=nodebuilder /app/public/build ./public/build

# Install PHP dependencies (no-dev)
RUN composer install --no-dev --optimize-autoloader

# Laravel setup & optimization
RUN php artisan key:generate --force \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache \
 && php artisan storage:link || true \
 && php artisan migrate --force \
 && php artisan db:seed --force

# Expose port
EXPOSE 8000

# Start the app (Render uses $PORT automatically)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
