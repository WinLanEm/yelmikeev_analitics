<?php

namespace App\Contracts\Repositories\Import;

interface ImportRepository
{
    public function upsert(array $dtos):void;
}
