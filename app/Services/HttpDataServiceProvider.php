<?php

namespace App\Services;

use App\DTOs\IncomeDTO;
use App\DTOs\OrderDTO;
use App\DTOs\SaleDTO;
use App\DTOs\StockDTO;
use Illuminate\Support\Facades\Http;

final class ApiService
{
    private string $apiKey;
    private string $baseUrl;
    public function __construct()
    {
        $this->apiKey = config('api.key');
        $this->baseUrl = config('api.base_url');
    }

    public function getSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('sales', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => SaleDTO::fromArray($item), $data);
    }

    public function getOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => OrderDTO::fromArray($item), $data);
    }

    public function getIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'limit' => $limit,
        ]);
        return array_map(fn($item) => IncomeDTO::fromArray($item), $data);
    }

    public function getStocks(string $dateFrom, int $page = 1, int $limit = 500): array
    {
        $data = $this->makeRequest('stocks', [
            'dateFrom' => $dateFrom,
            'page' => $page,
            'limit' => $limit,
        ]);

        return array_map(fn($item) => StockDTO::fromArray($item), $data);
    }

    private function makeRequest(string $endpoint, array $params): array
    {
        $params['key'] = $this->apiKey;

        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        $response->throw();

        return $response->json('data') ?? [];
    }
}
