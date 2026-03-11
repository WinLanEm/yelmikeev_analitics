<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\StockDTO;
use App\Models\Income;
use App\Models\Stock;
use Illuminate\Support\Carbon;

final class EloquentStockRepository implements ImportRepository
{
    public function getLastImportDate(int $accountId): ?Carbon
    {
        $dateString = Stock::where('account_id', $accountId)->max('date');
        return $dateString ? Carbon::parse($dateString) : null;
    }
    /**
     * @param array<int, StockDTO> $dtos
     */
    public function upsert(array $dtos, int $accountId): void
    {
        if (empty($dtos)) return;

        $data = array_map(function(StockDTO $dto) use ($accountId) {
            $row = $dto->toArray()  + ['account_id' => $accountId];
            $row['row_hash'] = $this->generateRowHash($row);
            return $row;
        }, $dtos);

        Stock::upsert(
            $data,
            ['row_hash'],
            [
                'quantity',
                'quantity_full',
                'price',
                'discount',
                'last_change_date',
                'in_way_to_client',
                'in_way_from_client'
            ]
        );
    }

    private function generateRowHash(array $data): string
    {
        $keyFields = [
            $data['account_id'],
            $data['date'],
            $data['warehouse_name'],
            $data['nm_id'],
            $data['barcode'],
            $data['tech_size'],
        ];

        return md5(serialize($keyFields));
    }
}
