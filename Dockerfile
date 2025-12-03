FROM php:8.2-apache

# Bật rewrite nếu bạn có .htaccess / route MVC
RUN a2enmod rewrite

# Copy source vào container
COPY . /var/www/html

# Quyền thư mục (nhất là uploads)
RUN chown -R www-data:www-data /var/www/html
