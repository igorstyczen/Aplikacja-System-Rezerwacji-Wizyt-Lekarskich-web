#!/bin/bash
set -e

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    echo "[entrypoint] Instalacja zależności Composer (wolumen Linux, nie Windows)..."
    composer install --optimize-autoloader --prefer-dist --no-interaction
fi

mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs storage/app/public bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

if [ ! -e public/storage ]; then
    echo "[entrypoint] Tworzenie symlinku public/storage..."
    php artisan storage:link --no-ansi 2>/dev/null || ln -sfn ../storage/app/public public/storage
fi

if [ -f .env ]; then
    echo "[entrypoint] Cache konfiguracji Laravel..."
    php artisan config:cache --no-ansi 2>/dev/null || true
    php artisan route:cache --no-ansi 2>/dev/null || true
    php artisan view:clear --no-ansi 2>/dev/null || true
fi

exec apache2-foreground
