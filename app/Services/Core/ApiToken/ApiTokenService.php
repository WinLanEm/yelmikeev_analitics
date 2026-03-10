<?php

namespace App\Services\Core\ApiToken;

use App\Contracts\Repositories\Core\ApiToken\ApiTokenRepository;
use App\DTOs\Core\ApiToken\ApiTokenCreateDTO;
use App\Models\ApiToken;

final class ApiTokenService
{
    public function __construct(
        private ApiTokenRepository $apiTokenRepository,
    ){}

    public function create(ApiTokenCreateDTO $dto):ApiToken
    {
        return $this->apiTokenRepository->create($dto);
    }
}
