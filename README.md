# Подготовка окружения

cp .env.example .env - там же доступы к бд

# Устанавливаем зависимости (PHP)
docker-compose --build

docker-compose run app composer install

docker-compose run app php artisan key:generate

docker-compose run app npm install && npm run build

docker-compose up -d

# Запуск воркера

docker-compose exec app php artisan queue:work

# Работа
При работе бд будут задержки, из-за удаленности сервера

Импорт данных происходит через консольную команду команду

php artisan api:import --entity= : sales, orders, stocks, incomes
