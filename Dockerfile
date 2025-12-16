FROM php:8.2-apache

RUN a2enmod rewrite \
 && docker-php-ext-install mysqli pdo pdo_mysql \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html

CMD ["sh", "-c", "cat > /var/www/html/config.php <<'EOF'\n<?php\n\ndefine('BASE_URL', getenv('BASE_URL') ?: 'http://localhost:8081');\n\ndefine('DB_HOST', getenv('DB_HOST') ?: 'db');\ndefine('DB_NAME', getenv('DB_NAME') ?: 'quanlytuyendungabc');\ndefine('DB_USER', getenv('DB_USER') ?: 'root');\ndefine('DB_PASS', getenv('DB_PASS') ?: '');\n\ndefine('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');\ndefine('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));\ndefine('SMTP_USER', getenv('SMTP_USER') ?: '');\ndefine('SMTP_PASS', getenv('SMTP_PASS') ?: '');\ndefine('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: '');\ndefine('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: '');\nEOF\nexec apache2-foreground"]
