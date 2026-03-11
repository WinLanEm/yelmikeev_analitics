<?php

namespace App\Services\Integration;

use App\Contracts\Services\DataServiceProvider;
use App\DTOs\Import\IncomeDTO;
use App\DTOs\Import\OrderDTO;
use App\DTOs\Import\SaleDTO;
use App\DTOs\Import\StockDTO;
use App\Enums\ApiEntity;
use App\Models\ApiToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

final class WbDataServiceProvider implements DataServiceProvider
{
    private ApiToken $token;
    private string $baseUrl;
    private int $limit = 500;
    public function __construct(ApiToken $token)
    {
        $this->token = $token;
        $this->baseUrl = config('api.base_url');
    }

    public function getSupportedEntities(): array
    {
        return [
            ApiEntity::SALES,
            ApiEntity::ORDERS,
            ApiEntity::INCOMES,
            ApiEntity::STOCKS,
        ];
    }

    public function fetch(ApiEntity $entity, Carbon $dateFrom, ?Carbon $dateTo, int $page = 1): array
    {
        return match ($entity) {
            ApiEntity::SALES => $this->getSales($dateFrom, $dateTo, $page),
            ApiEntity::INCOMES => $this->getIncomes($dateFrom, $dateTo, $page),
            ApiEntity::ORDERS => $this->getOrders($dateFrom, $dateTo, $page),
            ApiEntity::STOCKS => $this->getStocks($dateFrom, $page),
            default => throw new InvalidArgumentException("Сущность {$entity->value} не поддерживается провайдером WB")
        };
    }

    private function getSales(Carbon $dateFrom, Carbon $dateTo, int $page = 1): array
    {
        $data = $this->makeRequest('sales', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $this->limit,
        ]);
        return array_map(fn($item) => SaleDTO::fromArray($item), $data);
    }

    private function getOrders(Carbon $dateFrom, Carbon $dateTo, int $page = 1): array
    {
        $data = $this->makeRequest('orders', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $this->limit,
        ]);
        return array_map(fn($item) => OrderDTO::fromArray($item), $data);
    }

    private function getIncomes(Carbon $dateFrom, Carbon $dateTo, int $page = 1): array
    {
        $data = $this->makeRequest('incomes', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'page' => $page,
            'limit' => $this->limit,
        ]);
        return array_map(fn($item) => IncomeDTO::fromArray($item), $data);
    }

    private function getStocks(Carbon $dateFrom, int $page = 1): array
    {
        $data = $this->makeRequest('stocks', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'page' => $page,
            'limit' => $this->limit,
        ]);

        return array_map(fn($item) => StockDTO::fromArray($item), $data);
    }

    private function makeRequest(string $endpoint, array $params): array
    {
        $tokenValue = $this->token->token_value;
        $typeName = strtolower($this->token->tokenType->name);

        $request = Http::retry(3, 1000);

        $request = match ($typeName) {
            'bearer' => $request->withToken($tokenValue),

            'api-key-header' => $request->withHeaders(['X-Api-Key' => $tokenValue]),

            'api-key-query' => (function() use (&$params, $tokenValue, $request) {
                $params['key'] = $tokenValue;
                return $request;
            })(),

            // логин и пароль (Basic Auth). Предполагаем, что в базе лежит "login:password"
            'basic-auth' => (function() use ($tokenValue, $request) {
                if (!str_contains($tokenValue, ':')) {
                    throw new InvalidArgumentException("Для типа 'basic-auth' токен должен быть в формате 'login:password'");
                }
                [$login, $password] = explode(':', $tokenValue, 2);
                return $request->withBasicAuth($login, $password);
            })(),

            default => throw new RuntimeException("Обработчик для типа токена '{$typeName}' не реализован в " . self::class)
        };

        $response = $request->get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->failed()) {
            Log::error("WB API Error", [
                'status' => $response->status(),
                'body' => $response->body(),
                'token_id' => $this->token->id
            ]);
            $response->throw();
        }

        return $response->json('data') ?? [];
    }
}
