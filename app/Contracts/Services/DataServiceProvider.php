<?php

namespace App\Contracts\Services;

use App\DTOs\Import\IncomeDTO;
use App\DTOs\Import\OrderDTO;
use App\DTOs\Import\SaleDTO;
use App\DTOs\Import\StockDTO;
use App\Enums\ApiEntity;
use Illuminate\Support\Carbon;

interface DataServiceProvider
{
    /**
     * @return array<int, ApiEntity>
     */
    public function getSupportedEntities(): array;


    public function fetch(ApiEntity $entity, Carbon $dateFrom, ?Carbon $dateTo, int $page = 1): array;
}
