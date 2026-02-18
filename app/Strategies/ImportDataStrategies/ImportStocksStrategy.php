<?php

namespace App\Strategies\ImportDataStrategies;

use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Enums\ApiEntity;
use App\Jobs\ImportApiEntityJob;
use Illuminate\Support\Carbon;

final class ImportStocksStrategy implements ImportEntityStrategy
{
    public function execute(): void
    {
        ImportApiEntityJob::dispatch(
            ApiEntity::STOCKS,
            Carbon::now(),
            null
        );
    }
}
