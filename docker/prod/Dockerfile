# Build stage: Install dependencies and prepare Laravel
FROM php:8.3-fpm

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies (as root)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    supervisor \
    cron \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install \
    pdo_pgsql \
    mbstring \
    xml \
    zip \
    bcmath \
    opcache \
    pcntl \
    && docker-php-ext-enable opcache

# Install Composer (run as root)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project files to the container
# Copy code to /var/www
COPY --chown=www-data:www-data . /var/www/html

RUN chmod -R ug+w /var/www/html/storage

# Fix permissions before running Laravel commands
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Switch to the www-data user for Laravel-specific commands
USER www-data

# Optimize Laravel for production
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

USER root

# Copy Nginx configuration
COPY docker/prod/default.conf /etc/nginx/sites-enabled/default

# Copy Supervisor configuration
COPY docker/prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions for runtime directories (as root)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for Nginx
EXPOSE 80

# Start Supervisor, which will manage Nginx and PHP-FPM
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
