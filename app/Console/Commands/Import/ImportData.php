<?php

namespace App\Console\Commands\Import;

use App\Enums\ApiEntity;
use App\Services\Import\ImportManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

final class ImportData extends Command
{
    protected $signature = 'api:import
    {--api_token_id= : ID API токена}
    {--entity= : sales, orders, stocks, incomes (опционально)}';

    public function __construct(
        private ImportManager $importManager
    )
    {
        parent::__construct();
    }

    public function handle():int
    {
        $tokenId = $this->option('api_token_id');
        $entityInput = $this->option('entity');

        $entityNames = $entityInput
            ? array_map('trim', explode(',', $entityInput))
            : null;

        $validator = Validator::make(
            [
                'api_token_id' => $tokenId,
                'entities' => $entityNames
            ],
            [
                'api_token_id' => 'required|integer|exists:api_tokens,id',
                'entities' => 'nullable|array',
                'entities.*' => [new Enum(ApiEntity::class)]
            ],
            [
                'entities.*.enum' => 'Передана неизвестная сущность. Доступно: sales, orders, stocks, incomes.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $entities = $entityNames
            ? array_map(fn(string $name) => ApiEntity::from($name), $entityNames)
            : ApiEntity::cases();

        $this->importManager->run($tokenId, $entities);

        $this->info('Импорт успешно запущен!');
        return self::SUCCESS;
    }
}
