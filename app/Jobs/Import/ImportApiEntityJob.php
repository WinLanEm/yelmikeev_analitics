<?php

namespace App\Jobs\Import;

use App\Enums\ApiEntity;
use App\Factories\Import\ImportRepositoryFactory;
use App\Factories\Integration\IntegrationFactory;
use App\Models\ApiToken;
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
    public int $timeout = 60;

    public function __construct(
        private readonly ApiEntity $entity,
        private readonly ApiToken $token,
        private readonly Carbon $dateFrom,
        private readonly ?Carbon $dateTo = null,
        private readonly int $page = 1,
    ) {}

    public function middleware(): array
    {
        return [new RateLimited('api_integration')];
    }

    public function getToken(): ApiToken
    {
        return $this->token;
    }

    public function handle(): void
    {
        $repository = ImportRepositoryFactory::make($this->entity);
        $apiClient = IntegrationFactory::make($this->token);

        try {
            $items = $apiClient->fetch($this->entity, $this->dateFrom, $this->dateTo, $this->page);

            // Если пришел пустой массив — значит мы дошли до конца. Просто завершаем Джобу.
            if (empty($items)) {
                return;
            }

            $repository->upsert($items, $this->token->account_id);

            self::dispatch(
                $this->entity,
                $this->token,
                $this->dateFrom,
                $this->dateTo,
                $this->page + 1 //получаем следующую страницу
            )->onQueue('imports');
        } catch (RequestException $e) {
            if ($e->getCode() === 429) {
                $this->release(60);
                return;
            }
            throw $e;
        }
    }
}
