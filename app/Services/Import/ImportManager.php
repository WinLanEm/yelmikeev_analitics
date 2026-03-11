<?php

namespace App\Services\Import;

use App\Contracts\Repositories\Core\ApiToken\ApiTokenRepository;
use App\Enums\ApiEntity;
use App\Factories\Integration\IntegrationFactory;
use App\Strategies\ImportDataStrategy;
use Illuminate\Support\Facades\Log;

final class ImportManager
{
    public function __construct(
        private ApiTokenRepository $apiTokenRepository,
        private ImportDataStrategy $strategy,
    ){}

    /**
     * @param array<int, ApiEntity> $requestedEntities
     */
    public function run(int $tokenId, array $requestedEntities): void
    {
        $token = $this->apiTokenRepository->findByIdWithRelations($tokenId);

        $apiClient = IntegrationFactory::make($token);

        $supportedEntities = $apiClient->getSupportedEntities();

        $validEntities = array_filter(
            $requestedEntities,
            fn(ApiEntity $entity) => in_array($entity, $supportedEntities, true)
        );

        $unsupportedEntities = array_filter(
            $requestedEntities,
            fn(ApiEntity $entity) => !in_array($entity, $supportedEntities, true)
        );

        $serviceName = $token->apiService->name;

        if (!empty($unsupportedEntities)) {
            $unsupportedNames = implode(', ', array_map(fn(ApiEntity $e) => $e->value, $unsupportedEntities));

            if (empty($validEntities)) {
                Log::warning("API сервис '{$serviceName}' не поддерживает запрошенные сущности: {$unsupportedNames}. Импорт отменен.");
                return;
            }

            Log::info("API сервис '{$serviceName}' пропустит неподдерживаемые сущности: {$unsupportedNames}.");
        }

        foreach ($validEntities as $entity) {
            $this->strategy->import($entity, $token);
        }
    }
}
