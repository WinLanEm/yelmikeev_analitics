<?php

namespace App\Factories;

use App\Contracts\Repositories\ImportRepository;
use App\Enums\ApiEntity;
use App\Repositories\IncomeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SaleRepository;
use App\Repositories\StockRepository;
use InvalidArgumentException;

final class ImportRepositoryFactory
{
    public static function make(ApiEntity $entity): ImportRepository
    {
        return match ($entity) {
            ApiEntity::SALES  => app(SaleRepository::class),
            ApiEntity::ORDERS => app(OrderRepository::class),
            ApiEntity::INCOMES => app(IncomeRepository::class),
            ApiEntity::STOCKS => app(StockRepository::class),
            default => throw new InvalidArgumentException("No repository for {$entity->value}"),
        };
    }
}
