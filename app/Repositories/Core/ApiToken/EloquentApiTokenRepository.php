<?php

namespace App\Repositories\Core\ApiToken;

use App\Contracts\Repositories\Core\ApiToken\ApiTokenRepository;
use App\DTOs\Core\ApiToken\ApiTokenCreateDTO;
use App\Models\ApiToken;

final class EloquentApiTokenRepository implements ApiTokenRepository
{
    public function findByIdWithRelations(int $id): ApiToken
    {
        /** @var ApiToken */
        return ApiToken::with(['account','apiService','tokenType'])->findOrFail($id);
    }

    public function create(ApiTokenCreateDTO $dto):ApiToken
    {
        return ApiToken::create($dto->toArray());
    }
}
