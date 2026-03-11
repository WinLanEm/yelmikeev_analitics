<?php

namespace App\Console\Commands\Core\ApiService;

use App\Services\Core\ApiService\ApiServiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

final class CreateApiService extends Command
{
    protected $signature = 'create:api-service
    {--name= : Название сервиса}
    {--types= : ID поддерживаемых типов токенов через запятую (например: 1,2)}';

    public function __construct(
        private ApiServiceService $service
    )
    {
        parent::__construct();
    }

    public function handle():int
    {
        $name = $this->option('name');
        $typesInput = $this->option('types');

        $typeIds = $typesInput ? explode(',', $typesInput) : [];

        $validator = Validator::make(
            ['name' => $name, 'type_ids' => $typeIds],
            [
                'name' => 'required|string|unique:api_services,name',
                'type_ids' => 'required|array|min:1',
                'type_ids.*' => 'integer|exists:token_types,id'
            ],
            [
                'type_ids.required' => 'Необходимо указать хотя бы один ID типа токена через запятую.',
                'type_ids.*.exists' => 'Один или несколько указанных типов токенов не существуют в БД.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $service = $this->service->create($name, $typeIds);
        $this->info("Сервис успешно создан с id: {$service->id}");
        return self::SUCCESS;
    }
}
