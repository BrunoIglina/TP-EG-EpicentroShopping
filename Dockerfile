FROM php:8.2-apache

# 1. Habilitamos mod_rewrite (Fundamental para .htaccess y MVC)
RUN a2enmod rewrite

# 2. Instalamos dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install zip mysqli intl

# 3. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Directorio de trabajo
WORKDIR /var/www/html

# 5. Instalación de librerías (Caché de Docker)
COPY private/lib/composer.json ./private/lib/
RUN composer install --working-dir=private/lib --no-dev --optimize-autoloader

# 6. Copiamos el resto del proyecto
COPY . .

# 7. Permisos para que Apache pueda leer todo
RUN chown -R www-data:www-data /var/www/html