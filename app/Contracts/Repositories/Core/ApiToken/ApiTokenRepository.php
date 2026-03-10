<?php

namespace App\Contracts\Repositories\Core\ApiToken;

use App\DTOs\Core\ApiToken\ApiTokenCreateDTO;
use App\Models\ApiToken;

interface ApiTokenRepository
{
    public function create(ApiTokenCreateDTO $dto):ApiToken;
}
