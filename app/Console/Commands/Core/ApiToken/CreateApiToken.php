<?php

namespace App\Console\Commands\Core\ApiToken;

use App\DTOs\Core\ApiToken\ApiTokenCreateDTO;
use App\Services\Core\ApiToken\ApiTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class CreateApiToken extends Command
{
    protected $signature = 'create:api-token
        {--account_id= : ID аккаунта}
        {--api_service_id= : ID сервиса}
        {--token_type_id= : ID типа токена}
        {--token_value= : Токен}';

    public function __construct(
        private ApiTokenService $service,
    )
    {
        parent::__construct();
    }

    public function handle():int
    {
        $accountId = $this->option('account_id');
        $apiServiceId = $this->option('api_service_id');
        $tokenTypeId = $this->option('token_type_id');
        $tokenValue = $this->option('token_value');

        $validator = Validator::make(
            [
                'account_id' => $accountId,
                'api_service_id' => $apiServiceId,
                'token_type_id' => $tokenTypeId,
                'token_value' => $tokenValue,
            ],
            [
                'account_id' => 'required|integer|exists:accounts,id',
                'api_service_id' => 'required|integer|exists:api_services,id',
                'token_type_id' => [
                    'required',
                    'integer',
                    'exists:token_types,id',
                    Rule::exists('api_service_token_type', 'token_type_id')
                        ->where('api_service_id', $apiServiceId) // проверяем связь типа токена с сервисом
                ],
                'token_value' => 'required|string',
            ],
            [
                'token_type_id.exists' => 'Ошибка: Данный тип токена НЕ поддерживается выбранным API сервисом!'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $dto = ApiTokenCreateDto::fromArray($validator->validated());

        $apiToken = $this->service->create($dto);
        $this->info("API токен успешно создан с id: {$apiToken->id}");
        return self::SUCCESS;
    }
}
