FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy config directory first
COPY config/ ./config/

# Copy other essential files
COPY login.php ./
COPY register.php ./
COPY index.php ./
COPY test.php ./
COPY .htaccess ./

# Copy remaining files
COPY . .

# Debug: List files to verify they were copied
RUN echo "=== Root directory contents ===" && ls -la /var/www/html/
RUN echo "=== Config directory contents ===" && ls -la /var/www/html/config/
RUN echo "=== Testing file existence ===" && test -f /var/www/html/config/database.php && echo "config/database.php exists" || echo "config/database.php missing"

# Install dependencies (if composer.json exists)
RUN if [ -f "composer.json" ]; then composer install --no-dev --optimize-autoloader; fi

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Configure Apache
RUN a2enmod rewrite

# Create a simple Apache configuration
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
