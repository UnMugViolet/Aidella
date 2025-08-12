FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
	gd \
    libzip-dev \
    make \
    mysql-client \
    nginx \
    supervisor \
    unzip \
    zip \
    && docker-php-ext-install \
    pdo_mysql \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer*.json ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

# Clear any cached service provider discovery and rebuild for production
RUN rm -rf bootstrap/cache/packages.php bootstrap/cache/services.php \
    && composer dump-autoload --optimize --no-dev --no-scripts \
    && mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod +rw /var/www/html/storage/logs/laravel.log \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor /var/log/nginx /run/nginx \
    && chown -R www-data:www-data /var/log/nginx /run/nginx \
    && chown -R www-data:www-data /var/log/supervisor

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

EXPOSE 35001

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
