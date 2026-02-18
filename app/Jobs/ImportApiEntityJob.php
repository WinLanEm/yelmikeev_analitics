<?php

namespace App\Jobs;

use App\Enums\ApiEntity;
use App\Factories\ImportRepositoryFactory;
use App\Services\ApiEntityFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

final class ImportApiEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    public function __construct(
        public readonly ApiEntity $entity,
        private readonly Carbon $dateFrom,
        private readonly ?Carbon $dateTo = null
    ) {}

    public function middleware(): array
    {
        return [new RateLimited('wb_api')];
    }

    public function handle(ApiEntityFetcher $entityFetcher): void
    {
        $page = 1;
        $limit = 500;
        $repository = ImportRepositoryFactory::make($this->entity);

        try {
            do {
                $items = $entityFetcher->fetch($this->entity, $this->dateFrom, $this->dateTo, $page, $limit);

                if (empty($items)) break;

                $repository->upsert($items);
                $page++;
                $itemsCount = count($items);

                unset($items);

                sleep(1);

            } while ($itemsCount === $limit);
        } catch (RequestException $e) {
            if ($e->getCode() === 429) {
                $this->release(60);
                return;
            }
            throw $e;
        }
    }
}
