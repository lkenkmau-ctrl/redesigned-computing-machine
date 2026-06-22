FROM php:8.2-apache
RUN apt-get update && apt-get install -y libcurl4-openssl-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install curl
RUN a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod 755 /var/www/html
EXPOSE 80
