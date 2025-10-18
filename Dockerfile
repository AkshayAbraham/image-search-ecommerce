# Use official PHP Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Create uploads directory and set permissions
RUN mkdir -p /var/www/html/public/assets/uploads && \
    chmod -R 755 /var/www/html/public/assets/uploads && \
    chown -R www-data:www-data /var/www/html/public/assets/uploads

# Set Apache document root to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80