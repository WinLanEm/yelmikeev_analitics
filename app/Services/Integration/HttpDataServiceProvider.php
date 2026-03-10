<?php

namespace App\Services\Integration;

use App\Contracts\Services\DataServiceProvider;
use App\DTOs\Import\IncomeDTO;
use App\DTOs\Import\OrderDTO;
use App\DTOs\Import\SaleDTO;
use App\DTOs\Import\StockDTO;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

final class HttpDataServiceProvider implements DataServiceProvider
{
    private string $apiKey;
    private string $baseUrl;
    public function __construct()
    {
        $this->apiKey = config('api.key');
        $this->baseUrl = config('api.base_url');
    }

    public function getSales(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('sales', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => SaleDTO::fromArray($item), $data);
    }

    public function getOrders(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('orders', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => OrderDTO::fromArray($item), $data);
    }

    public function getIncomes(Carbon $dateFrom, Carbon $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('incomes', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => IncomeDTO::fromArray($item), $data);
    }

    public function getStocks(Carbon $dateFrom, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('stocks', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'page' => $page,
            'limit' => $limit,
        ]);

        return array_map(fn($item) => StockDTO::fromArray($item), $data);
    }

    private function makeRequest(string $endpoint, array $params): array
    {
        $params['key'] = $this->apiKey;

        return Http::retry(3, 1000)
            ->get("{$this->baseUrl}/{$endpoint}", $params)
            ->throw()
            ->json('data') ?? [];
    }
}
