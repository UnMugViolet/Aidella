FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    freetype-dev \
    gd \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    make \
    mysql-client \
    nginx \
    nodejs \
    npm \
    supervisor \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
    gd \
    pdo_mysql \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG NODE_ENV

# Use them during build
ENV NODE_ENV=$NODE_ENV

WORKDIR /var/www/html

COPY composer*.json ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

# Create storage directories and set proper permissions
RUN npm install --include=dev \
    && mkdir -p /var/www/html/storage/app/public/uploads/dog-races \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /tmp/nginx-client-body \
    && mkdir -p /tmp/nginx-proxy \
    && mkdir -p /tmp/nginx-fastcgi \
    && mkdir -p /tmp/nginx-uwsgi \
    && mkdir -p /tmp/nginx-scgi \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /tmp/nginx-* \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 755 /tmp/nginx-*

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/wait-for-db.sh /usr/local/bin/wait-for-db.sh

RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/log/nginx \
    && chmod +x /usr/local/bin/wait-for-db.sh

EXPOSE 35001

ENTRYPOINT ["/usr/local/bin/wait-for-db.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
