FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Habilitar mod_rewrite de Apache (útil para proyectos PHP)
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html/

# Ajustar permisos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html