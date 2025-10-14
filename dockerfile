FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip unzip libzip-dev \
    default-mysql-client nginx

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd zip xml dom fileinfo opcache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . /var/www
WORKDIR /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www

# Copy nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["/start.sh"]