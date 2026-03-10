<?php

namespace App\Providers;

use App\Contracts\Repositories\Core\Account\AccountRepository;
use App\Contracts\Repositories\Core\ApiService\ApiServiceRepository;
use App\Contracts\Repositories\Core\ApiToken\ApiTokenRepository;
use App\Contracts\Repositories\Core\Company\CompanyRepository;
use App\Contracts\Repositories\Core\TokenType\TokenTypeRepository;
use App\Contracts\Repositories\Import\ImportRepository;
use App\Contracts\Services\DataServiceProvider;
use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Repositories\Core\Account\EloquentAccountRepository;
use App\Repositories\Core\ApiService\EloquentApiServiceRepository;
use App\Repositories\Core\ApiToken\EloquentApiTokenRepository;
use App\Repositories\Core\Company\EloquentCompanyRepository;
use App\Repositories\Core\TokenType\EloquentTokenTypeRepository;
use App\Repositories\Import\IncomeRepository;
use App\Repositories\Import\OrderRepository;
use App\Repositories\Import\SaleRepository;
use App\Repositories\Import\StockRepository;
use App\Services\Integration\HttpDataServiceProvider;
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

        $this->app->bind(
            AccountRepository::class,
            EloquentAccountRepository::class,
        );

        $this->app->bind(
            CompanyRepository::class,
            EloquentCompanyRepository::class,
        );

        $this->app->bind(
            ApiServiceRepository::class,
            EloquentApiServiceRepository::class,
        );

        $this->app->bind(
            ApiTokenRepository::class,
            EloquentApiTokenRepository::class,
        );

        $this->app->bind(
            TokenTypeRepository::class,
            EloquentTokenTypeRepository::class,
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
