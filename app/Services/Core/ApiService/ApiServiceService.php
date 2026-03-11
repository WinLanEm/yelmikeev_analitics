<?php

namespace App\Services\Core\ApiService;

use App\Contracts\Repositories\Core\ApiService\ApiServiceRepository;
use App\Models\ApiService;

final class ApiServiceService
{
    public function __construct(
        private ApiServiceRepository $repository
    ){}

    public function create(string $name, array $typeIds):ApiService
    {
        return $this->repository->createWithTokenTypes($name, $typeIds);
    }
}
