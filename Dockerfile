FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl unzip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY . /app/

RUN cat > /etc/nginx/nginx.conf <<'NGINXEOF'
worker_processes auto;
error_log /var/log/nginx/error.log;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    server {
        listen 80;
        root /app;
        index index.html index.php;

        location / {
            try_files $uri $uri/ =404;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }
    }
}
NGINXEOF

WORKDIR /app
RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 755 /app
RUN chown -R nobody:nobody /app

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"