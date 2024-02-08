FROM php:7.4-apache

# Install necessary dependencies
RUN dnf -y install \
    freetype \
    freetype-devel \
    libjpeg-turbo \
    libjpeg-turbo-devel \
    libpng \
    libpng-devel \
    libzip \
    libzip-devel \
    mariadb \
    mariadb-devel

# Enable required PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli

# Start Apache in the foreground
CMD ["apache2-foreground"]

#FROM php:7.4-apache
#
## Install necessary dependencies
#RUN apt-get update \
#    && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libpng-dev \
#        libzip-dev \
#        default-libmysqlclient-dev \
#    && rm -rf /var/lib/apt/lists/*
#
## Enable required PHP extensions
#RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli
#
## Start Apache in the foreground
#CMD ["apache2-foreground"]

