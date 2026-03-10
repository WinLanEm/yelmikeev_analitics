<?php

namespace App\Factories;

use App\Contracts\Repositories\Import\ImportRepository;
use App\Enums\ApiEntity;
use App\Repositories\Import\IncomeRepository;
use App\Repositories\Import\OrderRepository;
use App\Repositories\Import\SaleRepository;
use App\Repositories\Import\StockRepository;
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
