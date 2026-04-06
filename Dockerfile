FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install dependencies for PHPMailer
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install PHPMailer via Composer
RUN composer install --no-dev --optimize-autoloader

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80