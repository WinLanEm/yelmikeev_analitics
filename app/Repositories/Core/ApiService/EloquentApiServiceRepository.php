<?php

namespace App\Repositories\Core\ApiService;

use App\Contracts\Repositories\Core\ApiService\ApiServiceRepository;
use App\Models\ApiService;
use Illuminate\Support\Facades\DB;

final class EloquentApiServiceRepository implements ApiServiceRepository
{
    public function create(string $name): ApiService
    {
        return ApiService::create([
            'name' => $name
        ]);
    }

    public function createWithTokenTypes(string $name, array $typeIds): ApiService
    {
        return DB::transaction(function () use ($name, $typeIds) {

            $service = ApiService::create(['name' => $name]);

            $service->tokenTypes()->sync($typeIds);

            return $service;
        });
    }
}
