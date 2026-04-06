FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Copy nginx config to Alpine's correct include path
COPY nginx.conf /etc/nginx/http.d/default.conf

# Copy site files (nginx.conf excluded via .dockerignore)
COPY . /app/

WORKDIR /app
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"