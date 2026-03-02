# 1. Imagen base
FROM php:8.2-apache

# 2. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli

# 3. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Directorio de trabajo
WORKDIR /var/www/html

# 5. Copiamos SOLO el composer.json primero
# Esto ayuda a que Docker cachee las librerías y no las reinstale si solo cambias un .php
COPY lib/composer.json ./lib/

# 6. Instalamos librerías especificando la carpeta
RUN composer install --working-dir=lib --no-dev --optimize-autoloader

# 7. Copiamos el resto del proyecto al contenedor
COPY . .

# 8. Permisos para Apache
RUN chown -R www-data:www-data /var/www/html