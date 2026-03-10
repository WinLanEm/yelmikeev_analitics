<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\StockDTO;
use App\Models\Stock;

final class StockRepository implements ImportRepository
{
    public function upsert(array $dtos): void
    {
        if (empty($dtos)) return;

        $data = array_map(fn(StockDTO $dto) => $dto->toArray(), $dtos);

        Stock::upsert(
            $data,
            ['date', 'last_change_date', 'supplier_article', 'warehouse_name', 'nm_id', 'barcode', 'sc_code', 'tech_size'],
            null
        );
    }
}
