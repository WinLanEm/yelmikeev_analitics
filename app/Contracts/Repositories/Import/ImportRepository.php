<?php

namespace App\Contracts\Repositories\Import;

use Illuminate\Support\Carbon;

interface ImportRepository
{
    public function getLastImportDate(int $accountId): ?Carbon;
    public function upsert(array $dtos, int $accountId):void;
}
