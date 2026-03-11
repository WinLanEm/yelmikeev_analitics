<?php

namespace App\Strategies\ImportDataStrategies;

use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Enums\ApiEntity;
use App\Jobs\Import\ImportApiEntityJob;
use App\Models\ApiToken;
use Illuminate\Support\Carbon;

final class ImportStocksStrategy implements ImportEntityStrategy
{
    public function execute(ApiToken $token): void
    {
        ImportApiEntityJob::dispatch(
            ApiEntity::STOCKS,
            $token,
            Carbon::now(),
            null
        )->onQueue('imports');
    }
}
