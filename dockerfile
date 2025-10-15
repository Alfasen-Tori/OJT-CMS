# ----------------------------
# Stage 1: Build stage
# ----------------------------
FROM php:8.2-cli AS builder

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    libxslt1-dev \
    zlib1g-dev \
    libicu-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    pkg-config \
    && docker-php-ext-install zip pdo_mysql bcmath gd intl xml xsl pcntl sockets

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js 18 and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the app
COPY . .

# Build frontend assets if needed
RUN npm install
RUN npm run build

# Laravel optimizations
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan storage:link || true

# ----------------------------
# Stage 2: Production stage
# ----------------------------
FROM php:8.2-cli

WORKDIR /var/www/html

# Copy PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

# Copy app from builder
COPY --from=builder /var/www/html /var/www/html

# Copy Composer from builder
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Default command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
