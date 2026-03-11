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
use App\Jobs\Import\ImportApiEntityJob;
use App\Repositories\Core\Account\EloquentAccountRepository;
use App\Repositories\Core\ApiService\EloquentApiServiceRepository;
use App\Repositories\Core\ApiToken\EloquentApiTokenRepository;
use App\Repositories\Core\Company\EloquentCompanyRepository;
use App\Repositories\Core\TokenType\EloquentTokenTypeRepository;
use App\Repositories\Import\EloquentIncomeRepository;
use App\Repositories\Import\EloquentOrderRepository;
use App\Repositories\Import\EloquentSaleRepository;
use App\Repositories\Import\EloquentStockRepository;
use App\Services\Integration\WbDataServiceProvider;
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
            EloquentIncomeRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            EloquentOrderRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            EloquentSaleRepository::class
        );
        $this->app->bind(
            ImportRepository::class,
            EloquentStockRepository::class
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
            WbDataServiceProvider::class
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

        $this->app->when(ImportIncomesStrategy::class,)
            ->needs(ImportRepository::class)
            ->give(EloquentIncomeRepository::class);

        $this->app->when(ImportStocksStrategy::class,)
            ->needs(ImportRepository::class)
            ->give(EloquentStockRepository::class);

        $this->app->when(ImportSalesStrategy::class,)
            ->needs(ImportRepository::class)
            ->give(EloquentSaleRepository::class);

        $this->app->when(ImportOrdersStrategy::class,)
            ->needs(ImportRepository::class)
            ->give(EloquentOrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('api_integration', function ($job) {
            if ($job instanceof ImportApiEntityJob) {
                $token = $job->getToken();
                $serviceName = strtolower($token->apiService->name);

                $limit = match ($serviceName) {
                    'wb', 'wildberries' => Limit::perMinute(24),
                    default => Limit::perMinute(30),
                };

                //задаем лимиты только конкретному токену для конкретного сервиса (у разных сервисов разные лимиты)
                return $limit->by($token->id);
            }
            return Limit::none();
        });
    }
}
