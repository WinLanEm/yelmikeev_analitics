<?php

namespace App\Repositories;

use App\Contracts\Repositories\ImportRepository;
use App\DTOs\OrderDTO;
use App\Models\Order;

final class OrderRepository implements ImportRepository
{
    public function upsert(array $dtos): void
    {
        if (empty($dtos)) return;

        $data = array_map(fn(OrderDTO $dto) => $dto->toArray(), $dtos);

        Order::upsert(
            $data,
            ['g_number', 'nm_id','barcode', 'last_change_date', 'is_cancel','warehouse_name','date','oblast','total_price'],
            null
        );
    }
}
