<?php

namespace App\Repositories\Import;

use App\Contracts\Repositories\Import\ImportRepository;
use App\DTOs\Import\OrderDTO;
use App\Models\Income;
use App\Models\Order;
use Illuminate\Support\Carbon;

final class EloquentOrderRepository implements ImportRepository
{
    public function getLastImportDate(int $accountId): ?Carbon
    {
        $dateString = Order::where('account_id', $accountId)->max('date');
        return $dateString ? Carbon::parse($dateString) : null;
    }
    /**
     * @param array<int, OrderDTO> $dtos
     */
    public function upsert(array $dtos, int $accountId): void
    {
        if (empty($dtos)) return;

        $data = array_map(function(OrderDTO $dto) use ($accountId) {
            $row = $dto->toArray() + ['account_id' => $accountId];
            $row['row_hash'] = $this->generateRowHash($row);
            return $row;
        }, $dtos);

        Order::upsert(
            $data,
            ['row_hash'],
            [
                'last_change_date',
                'is_cancel',
                'cancel_dt',
                'discount_percent',
                'total_price',
                'warehouse_name',
                'oblast',
                'income_id'
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
        ];

        return md5(serialize($keyFields));
    }
}
