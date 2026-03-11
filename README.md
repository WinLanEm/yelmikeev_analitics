# Подготовка и запуск prod/dev
cp .env.example .env

# Dev
В режиме разработки локальная папка с кодом монтируется в контейнер. Чтобы сервер не уходил в бесконечный перезапуск из-за отсутствия файлов, зависимости нужно установить ДО старта основного процесса.

docker-compose run --rm app composer install

docker-compose run --rm app npm install

docker-compose up -d --build

# Prod
docker-compose -f docker-compose.prod.yml up -d --build

# Работа

Все консольные команды необходимо выполнять внутри контейнера app. Например, добавив префикс docker-compose exec app.

# Доступные консольные команды

Создание компании:
php artisan create:company --name="Название компании"

Создание аккаунта:
php artisan create:account --company_id=1 --name="Название аккаунта"

Создание типа токена (например, bearer, api-key):
php artisan create:token-type --name="api-key"

Создание API сервиса (через запятую указываются ID поддерживаемых типов токенов):
php artisan create:api-service --name="Wildberries" --types="1,2"

Создание API токена для аккаунта:
php artisan create:api-token --account_id=1 --api_service_id=1 --token_type_id=1 --token_value="ваш_токен"

Запуск импорта данных:
php artisan api:import --api_token_id=1

Запуск импорта с указанием конкретных сущностей (через запятую, опционально):
php artisan api:import --api_token_id=1 --entity="sales, orders, stocks, incomes"
