FROM centos:8-apache

# Install necessary dependencies
RUN dnf update \
  && dnf install -y \
    freetype-devel \
    libjpeg-turbo-devel \
    libpng-devel \
    libzip-devel \
    mysql-devel

# Enable required PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli

# Start Apache in the foreground
CMD ["apache2-foreground"]

#FROM php:7.4-apache
#
## Install necessary dependencies
#RUN dnf -y install \
#    freetype \
#    freetype-devel \
#    libjpeg-turbo \
#    libjpeg-turbo-devel \
#    libpng \
#    libpng-devel \
#    libzip \
#    libzip-devel \
#    mariadb \
#    mariadb-devel
#
## Enable required PHP extensions
#RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli
#
## Start Apache in the foreground
#CMD ["apache2-foreground"]

