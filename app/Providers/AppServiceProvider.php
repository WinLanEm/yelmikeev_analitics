<?php

namespace App\Providers;

use App\Contracts\Repositories\ImportRepository;
use App\Contracts\Services\DataServiceProvider;
use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Repositories\IncomeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SaleRepository;
use App\Repositories\StockRepository;
use App\Services\HttpDataServiceProvider;
use App\Strategies\ImportDataStrategies\ImportIncomesStrategy;
use App\Strategies\ImportDataStrategies\ImportOrdersStrategy;
use App\Strategies\ImportDataStrategies\ImportSalesStrategy;
use App\Strategies\ImportDataStrategies\ImportStocksStrategy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ImportRepository::class,
            IncomeRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            OrderRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            SaleRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            StockRepository::class
        );

        $this->app->bind(
            ImportEntityStrategy::class,
            ImportIncomesStrategy::class
        );
        $this->app->bind(
            ImportEntityStrategy::class,
            ImportOrdersStrategy::class
        );
        $this->app->bind(
            ImportEntityStrategy::class,
            ImportSalesStrategy::class
        );
        $this->app->bind(
            ImportEntityStrategy::class,
            ImportStocksStrategy::class
        );

        $this->app->bind(
            DataServiceProvider::class,
            HttpDataServiceProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('wb_api', function ($job) {
            return Limit::perMinute(12)->by($job->entity->value ?? 'global');
        });
    }
}
