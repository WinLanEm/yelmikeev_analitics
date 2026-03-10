<?php

namespace App\Contracts\Repositories\Core\ApiService;

use App\Models\ApiService;

interface ApiServiceRepository
{
    public function create(string $name):ApiService;

    public function createWithTokenTypes(string $name, array $typeIds): ApiService;
}
