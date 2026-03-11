<?php

namespace App\Factories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\Enums\ApiEntity;
use App\Repositories\Import\EloquentIncomeRepository;
use App\Repositories\Import\EloquentOrderRepository;
use App\Repositories\Import\EloquentSaleRepository;
use App\Repositories\Import\EloquentStockRepository;
use InvalidArgumentException;

final class ImportRepositoryFactory
{
    public static function make(ApiEntity $entity): ImportRepository
    {
        return match ($entity) {
            ApiEntity::SALES  => app(EloquentSaleRepository::class),
            ApiEntity::ORDERS => app(EloquentOrderRepository::class),
            ApiEntity::INCOMES => app(EloquentIncomeRepository::class),
            ApiEntity::STOCKS => app(EloquentStockRepository::class),
            default => throw new InvalidArgumentException("No repository for {$entity->value}"),
        };
    }
}
