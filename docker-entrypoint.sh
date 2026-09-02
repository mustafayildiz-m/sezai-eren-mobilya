#!/usr/bin/env bash
set -e
mkdir -p /var/www/html/storage/ratelimit /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads
exec apache2-foreground
