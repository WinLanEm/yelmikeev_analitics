<?php

namespace App\Strategies;

use App\Enums\ApiEntity;
use App\Models\ApiToken;
use App\Strategies\ImportDataStrategies\ImportIncomesStrategy;
use App\Strategies\ImportDataStrategies\ImportOrdersStrategy;
use App\Strategies\ImportDataStrategies\ImportSalesStrategy;
use App\Strategies\ImportDataStrategies\ImportStocksStrategy;
use InvalidArgumentException;


final class ImportDataStrategy
{
    public function import(ApiEntity $entity, ApiToken $token): void
    {
        match ($entity) {
            ApiEntity::INCOMES => app(ImportIncomesStrategy::class)->execute($token),
            ApiEntity::STOCKS => app(ImportStocksStrategy::class)->execute($token),
            ApiEntity::ORDERS => app(ImportOrdersStrategy::class)->execute($token),
            ApiEntity::SALES => app(ImportSalesStrategy::class)->execute($token),
            default => throw new InvalidArgumentException()
        };
    }
}
