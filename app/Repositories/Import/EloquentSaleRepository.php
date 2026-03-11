<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\SaleDTO;
use App\Models\Sale;
use Illuminate\Support\Carbon;

final class EloquentSaleRepository implements ImportRepository
{
    public function getLastImportDate(int $accountId): ?Carbon
    {
        $dateString = Sale::where('account_id', $accountId)->max('date');
        return $dateString ? Carbon::parse($dateString) : null;
    }
    /**
     * @param array<int, SaleDTO> $dtos
     */
    public function upsert(array $dtos, int $accountId): void
    {
        if (empty($dtos)) return;

        $data = array_map(function(SaleDTO $dto) use ($accountId) {
            $row = $dto->toArray() + ['account_id' => $accountId];
            $row['row_hash'] = $this->generateRowHash($row);
            return $row;
        }, $dtos);

        Sale::upsert(
            $data,
            ['row_hash'],
            [
                'last_change_date',
                'discount_percent',
                'promo_code_discount',
                'spp',
                'for_pay',
                'finished_price',
                'price_with_disc',
                'is_storno',
                'sale_id',
            ]
        );
    }

    private function generateRowHash(array $data): string
    {
        $keyFields = [
            $data['account_id'],
            $data['g_number'],
            $data['odid'],
            $data['nm_id'],
            $data['is_storno'] ? 1 : 0,
        ];

        return md5(serialize($keyFields));
    }
}
