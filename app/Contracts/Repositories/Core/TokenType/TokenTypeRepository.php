<?php

namespace App\Contracts\Repositories\Core\TokenType;

use App\Models\TokenType;

interface TokenTypeRepository
{
    public function create(string $name):TokenType;
}
