<?php

namespace App\Strategies\ImportDataStrategies;

use App\Contracts\Repositories\Import\ImportRepository;
use App\Contracts\Strategies\ImportData\ImportEntityStrategy;
use App\Enums\ApiEntity;
use App\Jobs\Import\ImportApiEntityJob;
use App\Models\ApiToken;
use Illuminate\Support\Carbon;

final class ImportIncomesStrategy implements ImportEntityStrategy
{
    public function __construct(
        private ImportRepository $repository,
    ){}

    public function execute(ApiToken $token):void
    {
        $lastImportDate = $this->repository->getLastImportDate($token->account_id);
        //если дата есть отступаем на 3 дня назад для перестраховки от задержек API, иначе используем дату по умолчанию
        $dateFrom = $lastImportDate
            ? $lastImportDate->copy()->subDays(3)
            : Carbon::create(2024, 1, 1);

        //добавляю один день чтобы точно получить все данные на сегодняшний день, endpoint /orders при дате на завтра
        //отдает больше записей, чем при дате на сегодня.
        $dateTo = Carbon::now()->addDay();

        $currentDate = $dateFrom->copy();

        while ($currentDate->lte($dateTo)) {
            $chunkFrom = $currentDate->copy();
            $chunkTo = $currentDate->copy()->endOfMonth();

            if ($chunkTo->gt($dateTo)) {
                $chunkTo = $dateTo->copy();
            }

            ImportApiEntityJob::dispatch(
                ApiEntity::INCOMES,
                $token,
                $chunkFrom,
                $chunkTo
            )->onQueue('imports');

            $currentDate->addMonth()->startOfMonth();
        }
    }
}
