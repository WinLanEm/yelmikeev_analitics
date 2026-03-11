<?php

namespace App\Strategies\ImportDataStrategies;

use App\Contracts\Repositories\Import\ImportRepository;
use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Enums\ApiEntity;
use App\Jobs\Import\ImportApiEntityJob;
use App\Models\ApiToken;
use Illuminate\Support\Carbon;

final class ImportSalesStrategy implements ImportEntityStrategy
{
    public function __construct(
        private ImportRepository $repository
    ){}

    public function execute(ApiToken $token):void
    {
        $lastImportDate = $this->repository->getLastImportDate($token->account_id);
        $dateFrom = $lastImportDate
            ? $lastImportDate->copy()->subDays(3)
            : Carbon::create(2024,1,1);

        $dateTo = Carbon::now()->addDay();

        $currentDate = $dateFrom->copy();

        while ($currentDate->lte($dateTo)) {
            $chunkFrom = $currentDate->copy();
            $chunkTo = $currentDate->copy()->endOfMonth();

            if ($chunkTo->gt($dateTo)) {
                $chunkTo = $dateTo->copy();
            }

            ImportApiEntityJob::dispatch(
                ApiEntity::SALES,
                $token,
                $chunkFrom,
                $chunkTo
            )->onQueue('imports');

            $currentDate->addMonth()->startOfMonth();
        }
    }

}
