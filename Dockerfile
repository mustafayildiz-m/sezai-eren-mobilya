FROM php:8.3-apache
RUN apt-get update && apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev libsqlite3-dev curl \
 && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
 && docker-php-ext-install -j$(nproc) gd pdo_sqlite \
 && a2enmod rewrite headers expires \
 && rm -rf /var/lib/apt/lists/*
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's#<Directory /var/www/>#<Directory /var/www/html/public/>#' /etc/apache2/apache2.conf \
 && printf '<Directory /var/www/html/public/>\n  AllowOverride All\n  Require all granted\n</Directory>\n' > /etc/apache2/conf-available/public.conf && a2enconf public
RUN printf 'upload_max_filesize=20M\npost_max_size=64M\nmemory_limit=256M\n' > /usr/local/etc/php/conf.d/uploads.ini
RUN curl -sL https://phar.phpunit.de/phpunit-11.phar -o /usr/local/bin/phpunit && chmod +x /usr/local/bin/phpunit
WORKDIR /var/www/html
COPY . .
RUN mkdir -p storage/ratelimit public/uploads && chown -R www-data:www-data storage public/uploads
EXPOSE 80
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
