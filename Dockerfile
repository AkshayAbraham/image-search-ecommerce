# Use official PHP + Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy application files and set ownership for www-data (faster than chown after copy)
# Add a .dockerignore file to avoid copying unnecessary files into the image
COPY --chown=www-data:www-data . .

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Ensure uploads directory exists and has correct permissions
RUN mkdir -p /var/www/html/public/assets/uploads \
    && chmod -R 755 /var/www/html/public/assets/uploads \
    && chown -R www-data:www-data /var/www/html/public/assets/uploads

# Make public/ the Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Replace Apache's document root references with the new value (double quotes so shell expands the env var)
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
    && sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80