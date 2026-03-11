<?php

namespace App\Services\Core\TokenType;

use App\Contracts\Repositories\Core\TokenType\TokenTypeRepository;
use App\Models\TokenType;

final class TokenTypeService
{
    public function __construct(
        private TokenTypeRepository $repository
    ){}

    public function create(string $name):TokenType
    {
        return $this->repository->create($name);
    }
}
