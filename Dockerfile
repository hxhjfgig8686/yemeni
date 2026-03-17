FROM php:8.2-apache

# PostgreSQL PDO
RUN apt-get update \
 && apt-get install -y libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# تشغيل rewrite module
RUN a2enmod rewrite

# نسخ الملفات
COPY . /var/www/html/

# تعديل الصلاحيات
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
