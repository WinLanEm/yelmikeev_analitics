<?php

namespace App\Contracts\Services;

use App\DTOs\Import\IncomeDTO;
use App\DTOs\Import\OrderDTO;
use App\DTOs\Import\SaleDTO;
use App\DTOs\Import\StockDTO;
use Illuminate\Support\Carbon;

interface DataServiceProvider
{
    /**
     * @return array<int, SaleDTO>
     */
    public function getSales(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array;

    /**
     * @return array<int, OrderDTO>
     */
    public function getOrders(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array;

    /**
     * @return array<int, IncomeDTO>
     */
    public function getIncomes(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array;

    /**
     * @return array<int, StockDTO>
     */
    public function getStocks(Carbon $dateFrom, int $page = 1, int $limit = 500): array;
}
