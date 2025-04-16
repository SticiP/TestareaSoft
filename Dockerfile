# Folosește PHP 8.2 cu FPM
FROM php:8.2-fpm

# Instalăm dependențele de sistem și extensiile PHP necesare
RUN apt-get update \
    && apt-get install -y \
       git \
       unzip \
       libpng-dev \
       libonig-dev \
       libxml2-dev \
    && docker-php-ext-install \
       pdo_mysql \
       mbstring \
       exif \
       pcntl \
       bcmath \
       gd

# Instalăm Composer global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setăm directorul de lucru
WORKDIR /var/www/html

# Copiem fișierele aplicației
COPY . .

RUN chmod -R 777 storage bootstrap/cache

# Instalăm dependențele PHP ale Laravel
RUN composer install --optimize-autoloader --no-dev

# Creăm directoarele necesare și setăm permisiuni
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expunem portul FPM (folosit intern de Nginx)
EXPOSE 9000

# Comanda implicită
CMD ["php-fpm"]
