# Подготовка окружения

cp .env.example .env - там же доступы к бд

# Устанавливаем зависимости (PHP)
docker-compose up -d --build

docker-compose exec app composer install

docker-compose exec app npm install && npm run build

# Запуск воркера

docker-compose exec app php artisan queue:work

# Работа
При работе бд будут задержки, из-за удаленности сервера

Импорт данных происходит через консольную команду команду 

php artisan api:import --entity= : sales, orders, stocks, incomes
