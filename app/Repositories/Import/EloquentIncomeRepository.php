<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\IncomeDTO;
use App\Models\Income;
use Illuminate\Support\Carbon;

final class EloquentIncomeRepository implements ImportRepository
{
    public function getLastImportDate(int $accountId): ?Carbon
    {
        $dateString = Income::where('account_id', $accountId)->max('date');
        return $dateString ? Carbon::parse($dateString) : null;
    }

    /**
     * @param array<int, IncomeDTO> $dtos
     */
    public function upsert(array $dtos,int $accountId): void
    {
        if (empty($dtos)) return;

        $data = array_map(function(IncomeDTO $dto) use ($accountId) {
            $row = $dto->toArray() + ['account_id' => $accountId];
            $row['row_hash'] = $this->generateRowHash($row);
            return $row;
        }, $dtos);

        Income::upsert(
            $data,
            ['row_hash'],
            [
                'number',
                'last_change_date',
                'date_close',
                'quantity',
                'total_price',
                'warehouse_name'
            ]
        );
    }

    private function generateRowHash(array $data): string
    {
        $keyFields = [
            $data['account_id'],
            $data['income_id'],
            $data['barcode'],
            $data['nm_id'],
            $data['tech_size'],
            $data['supplier_article'],
        ];

        return md5(serialize($keyFields));
    }
}
