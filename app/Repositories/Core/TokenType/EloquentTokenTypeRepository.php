<?php

namespace App\Repositories\Core\TokenType;

use App\Contracts\Repositories\Core\TokenType\TokenTypeRepository;
use App\Models\TokenType;

final class EloquentTokenTypeRepository implements TokenTypeRepository
{
    public function create(string $name): TokenType
    {
        return TokenType::create([
            'name' => $name,
        ]);
    }

}
