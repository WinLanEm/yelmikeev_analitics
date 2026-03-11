#!/bin/bash
set -e

# Проверяем, что это запускается главный контейнер (Octane), а не воркер или планировщик
if [ "$1" = "php" ] && [ "$2" = "artisan" ] && [[ "$3" == *"octane:start"* ]]; then

    if [ ! -f .env ]; then
        cp .env.example .env
    fi

    if ! grep -q "^APP_KEY=base64:" .env; then
        php artisan key:generate --force
    fi

    sleep 5

    php artisan migrate --force

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Выполняем команду из docker-compose
exec "$@"
