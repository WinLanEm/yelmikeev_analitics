<?php

namespace App\Services\Integration;

use App\Contracts\Services\DataServiceProvider;
use App\Enums\ApiEntity;
use Illuminate\Support\Carbon;

final class ApiEntityFetcher
{
    public function __construct(
        private DataServiceProvider $service,
    ){}

    public function fetch(ApiEntity $entity, Carbon $dateFrom, ?Carbon $dateTo, int $page, int $limit): array
    {
        return match ($entity) {
            ApiEntity::SALES => $this->service->getSales($dateFrom, $dateTo, $page, $limit),
            ApiEntity::INCOMES => $this->service->getIncomes($dateFrom, $dateTo, $page, $limit),
            ApiEntity::ORDERS => $this->service->getOrders($dateFrom, $dateTo, $page, $limit),
            ApiEntity::STOCKS => $this->service->getStocks($dateFrom, $page, $limit),
        };
    }
}
