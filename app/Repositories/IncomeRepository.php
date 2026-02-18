<?php

namespace App\Repositories;

use App\Contracts\Repositories\ImportRepository;
use App\DTOs\IncomeDTO;
use App\Models\Income;

final class IncomeRepository implements ImportRepository
{
    public function upsert(array $dtos): void
    {
        if (empty($dtos)) return;

        $data = array_map(fn(IncomeDTO $dto) => $dto->toArray(), $dtos);

        Income::upsert(
            $data,
            ['income_id', 'barcode','tech_size','supplier_article'],
            null
        );
    }
}
