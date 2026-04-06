FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html/
COPY nginx.conf /etc/nginx/http.d/default.conf

WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"