FROM php:8.2-apache

RUN a2enmod rewrite

# Cài mysqli + pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# (Tùy chọn) tắt warning ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
