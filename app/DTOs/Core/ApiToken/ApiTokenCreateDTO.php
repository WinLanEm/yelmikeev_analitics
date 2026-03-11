<?php

namespace App\DTOs\Core\ApiToken;

final class ApiTokenCreateDTO
{
    public function __construct(
        public readonly int $accountId,
        public readonly int $apiServiceId,
        public readonly int $tokenTypeId,
        public readonly string $token,
    ){}

    public static function fromArray(array $data):self
    {
        return new self(
            accountId: $data['account_id'],
            apiServiceId: $data['api_service_id'],
            tokenTypeId: $data['token_type_id'],
            token: $data['token_value'],
        );
    }

    public function toArray(): array
    {
        return [
            'account_id' => $this->accountId,
            'api_service_id' => $this->apiServiceId,
            'token_type_id' => $this->tokenTypeId,
            'token_value' => $this->token,
        ];
    }
}
