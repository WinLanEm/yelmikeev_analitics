<?php

namespace App\Strategies\ImportDataStrategies;

use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Enums\ApiEntity;
use App\Jobs\ImportApiEntityJob;
use Illuminate\Support\Carbon;

final class ImportOrdersStrategy implements ImportEntityStrategy
{
    public function execute(): void
    {
        $dateFrom = Carbon::create(2024, 1, 1);
        $dateTo = Carbon::now()->endOfYear();

        $currentDate = $dateFrom->copy();

        while ($currentDate->lte($dateTo)) {
            $chunkFrom = $currentDate->copy();
            $chunkTo = $currentDate->copy()->endOfMonth();

            ImportApiEntityJob::dispatch(
                ApiEntity::ORDERS,
                $chunkFrom,
                $chunkTo
            );

            $currentDate->addMonth()->startOfMonth();
        }
    }
}
