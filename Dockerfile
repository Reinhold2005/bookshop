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
WORKDIR /var/www

# Copy entire application
COPY . .

# Set the document root to the public directory
ENV APACHE_DOCUMENT_ROOT /var/www/public

# Update Apache configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable error logging
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-exif --ignore-platform-req=ext-gd

# Create .env file
RUN echo "APP_ENV=production" > .env
RUN echo "APP_DEBUG=true" >> .env
RUN echo "APP_URL=https://bookshop22.onrender.com" >> .env
RUN echo "APP_KEY=" >> .env
RUN echo "DB_CONNECTION=pgsql" >> .env
RUN echo "SESSION_DRIVER=file" >> .env
RUN echo "CACHE_DRIVER=file" >> .env

# Generate application key
RUN php artisan key:generate --force

# Install NPM dependencies
RUN npm install || true

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache public
RUN chmod -R 775 storage bootstrap/cache public

# Clear all caches
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan cache:clear

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
