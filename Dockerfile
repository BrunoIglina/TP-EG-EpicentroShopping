FROM php:8.2-apache

# Variable de entorno con la nueva ruta
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Reemplazamos las rutas en los archivos de configuración de Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# --------------------

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Directorio de trabajo
WORKDIR /var/www/html

# Esto ayuda a que Docker cachee las librerías y no las reinstale si solo cambias un .php
COPY private/lib/composer.json ./private/lib/

# Instalamos librerías especificando la carpeta
RUN composer install --working-dir=private/lib --no-dev --optimize-autoloader

# Copiamos el resto del proyecto al contenedor
COPY . .

# Permisos para Apache
RUN chown -R www-data:www-data /var/www/html