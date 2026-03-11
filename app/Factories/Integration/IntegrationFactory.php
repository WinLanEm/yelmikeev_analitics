<?php

namespace App\Factories\Integration;

use App\Contracts\Services\DataServiceProvider;
use App\Models\ApiToken;
use App\Services\Integration\WbDataServiceProvider;
use InvalidArgumentException;

final class IntegrationFactory
{
    public static function make(ApiToken $token): DataServiceProvider
    {
        return match ($token->apiService->name) {
            'wb', 'wildberries' => new WbDataServiceProvider($token),
            default => throw new InvalidArgumentException("Service provider for {$token->apiService->name} is not supported"),
        };
    }
}
