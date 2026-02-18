<?php

namespace App\Contracts\Repositories;

interface ImportRepository
{
    public function upsert(array $dtos):void;
}
