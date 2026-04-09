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

# Set temp directory
RUN echo "sys_temp_dir = /tmp" >> /usr/local/etc/php/conf.d/temp.ini

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-exif --ignore-platform-req=ext-gd



# Generate application key
RUN php artisan key:generate --force

# Install NPM dependencies
RUN npm install || true

# Create all necessary directories
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/cache
RUN mkdir -p storage/framework/testing
RUN mkdir -p storage/logs
RUN mkdir -p bootstrap/cache
RUN mkdir -p public/build

# Set permissions for storage and bootstrap
RUN chown -R www-data:www-data storage bootstrap/cache public
RUN chmod -R 775 storage bootstrap/cache public
RUN chmod -R 777 storage/framework/views
RUN chmod -R 777 storage/framework/sessions
RUN chmod -R 777 storage/framework/cache
RUN chmod -R 777 storage/logs
RUN chmod -R 777 bootstrap/cache

# Create .htaccess for public directory if not exists
RUN touch public/.htaccess

# Clear and optimize caches
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan cache:clear || true

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
