FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY . /app/

WORKDIR /app
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"