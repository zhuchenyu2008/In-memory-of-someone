FROM php:8.2-apache

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod headers \
    && sed -i 's/Listen 80/Listen 5176/' /etc/apache2/ports.conf \
    && sed -i 's/:80/:5176/' /etc/apache2/sites-available/000-default.conf

EXPOSE 5176
