FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock ./

# Install Composer dependencies WITHOUT running scripts
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-exif --ignore-platform-req=ext-gd

# Copy application files
COPY . .

# Create .env file from example if not exists
RUN if [ ! -f .env ] && [ -f .env.example ]; then cp .env.example .env; fi

# Run post-install scripts manually
RUN composer run-script post-autoload-dump --no-interaction || true

# Install NPM dependencies and build
RUN npm install || true
RUN npm run build || true

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Generate key (this will work after .env is created)
RUN php artisan key:generate --force || true

# Optimize (skip if fails)
RUN php artisan config:cache --no-interaction || true
RUN php artisan route:cache --no-interaction || true
RUN php artisan view:cache --no-interaction || true

EXPOSE 80
CMD ["apache2-foreground"]
