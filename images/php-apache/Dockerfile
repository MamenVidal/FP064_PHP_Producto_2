# Establecer la imagen base
FROM php:8.1-apache

# Define tu variable de entorno para el nuevo DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Modifica la configuración de Apache para usar el nuevo DocumentRoot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar dependencias y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    mariadb-client \
    git \ 
    unzip \ 
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mysqli zip exif pcntl bcmath opcache intl

# Activar mod_rewrite para Apache
RUN a2enmod rewrite headers

# asegurar que Apache esta configurado para permitir sobrescribir configuraciones mediante archivos .htaccess
RUN echo '<Directory "/var/www/html">' >> /etc/apache2/conf-available/allowoverride.conf \
    && echo '    AllowOverride All' >> /etc/apache2/conf-available/allowoverride.conf \
    && echo '</Directory>' >> /etc/apache2/conf-available/allowoverride.conf \
    && a2enconf allowoverride

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
