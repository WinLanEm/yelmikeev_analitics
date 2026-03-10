<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\SaleDTO;
use App\Models\Sale;

final class SaleRepository implements ImportRepository
{
    public function upsert(array $dtos): void
    {
        if (empty($dtos)) return;

        $data = array_map(fn(SaleDTO $dto) => $dto->toArray(), $dtos);

        Sale::upsert(
            $data,
            ['g_number', 'last_change_date', 'nm_id', 'is_storno'],
            null
        );
    }
}
