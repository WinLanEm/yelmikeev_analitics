<?php

namespace App\Strategies;

use App\Enums\ApiEntity;
use App\Strategies\ImportDataStrategies\ImportIncomesStrategy;
use App\Strategies\ImportDataStrategies\ImportOrdersStrategy;
use App\Strategies\ImportDataStrategies\ImportSalesStrategy;
use App\Strategies\ImportDataStrategies\ImportStocksStrategy;
use InvalidArgumentException;


final class ImportDataStrategy
{
    public function import(ApiEntity $entity): void
    {
        match ($entity) {
            ApiEntity::INCOMES => app(ImportIncomesStrategy::class)->execute(),
            ApiEntity::STOCKS => app(ImportStocksStrategy::class)->execute(),
            ApiEntity::ORDERS => app(ImportOrdersStrategy::class)->execute(),
            ApiEntity::SALES => app(ImportSalesStrategy::class)->execute(),
            default => throw new InvalidArgumentException()
        };
    }
}
