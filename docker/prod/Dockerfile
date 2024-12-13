# Build stage: Install dependencies and prepare Laravel
FROM php:8.3-fpm

ARG NODE_VERSION=22

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
    && docker-php-ext-enable opcache \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
        && apt-get install -y nodejs \
        && npm install -g npm \
        && apt-get -y autoremove \
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project files to the container
COPY --chown=www-data:www-data . /var/www/html

RUN chmod -R ug+w /var/www/html/storage

# Fix permissions before running Laravel commands
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Switch to the www-data user for Laravel-specific commands
USER www-data

# Optimize Laravel for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

USER root

# Copy Nginx configuration
COPY docker/prod/default.conf /etc/nginx/sites-enabled/default

# Copy Supervisor configuration
COPY docker/prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy start.sh script
COPY docker/prod/start-container.sh /usr/local/bin/start-container.sh
RUN chmod +x /usr/local/bin/start-container.sh

# Set permissions for runtime directories (as root)
RUN chown -R www-data:www-data /var/www/html

# Build the frontend assets
RUN npm install
RUN npm run build

# Expose port 80 for Nginx
EXPOSE 80

# Set the entry point to the start.sh script
CMD ["/usr/local/bin/start-container.sh"]
