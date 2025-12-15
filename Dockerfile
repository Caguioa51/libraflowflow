# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsodium-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd sodium \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node --version \
    && npm --version

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy and make build script executable
COPY build.sh /usr/local/bin/build.sh
RUN chmod +x /usr/local/bin/build.sh

# Run the build script
RUN /usr/local/bin/build.sh

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create storage directories
RUN mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views

# Create Apache configuration file
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php index.html
    </Directory>
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Create startup script
COPY <<EOF /startup.sh
#!/bin/bash
set -e
echo "Starting Libraflow deployment..."
php artisan config:cache
php artisan migrate --force
php artisan storage:link

# Seed admin user if requested
if [ "\${SEED_ADMIN_USER:-false}" = "true" ]; then
    echo "Seeding admin user..."
    php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
fi

# Seed real books if requested
if [ "\${SEED_REAL_BOOKS:-false}" = "true" ]; then
    echo "Seeding real books..."
    php artisan db:seed --class=Database\\Seeders\\RealBooksSeeder --force
fi

# Seed system settings if requested
if [ "\${SEED_SYSTEM_SETTINGS:-false}" = "true" ]; then
    echo "Seeding system settings..."
    php artisan db:seed --class=Database\\Seeders\\SystemSettingsSeeder --force
fi

echo "Starting Apache..."
exec apache2-foreground
EOF

RUN chmod +x /startup.sh

# Expose port 80
EXPOSE 80

# Start with our custom script
CMD ["/startup.sh"]
