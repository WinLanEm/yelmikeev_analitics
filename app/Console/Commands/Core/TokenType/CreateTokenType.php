<?php

namespace App\Console\Commands\Core\TokenType;

use App\Services\Core\TokenType\TokenTypeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

final class CreateTokenType extends Command
{
    protected $signature = 'create:token-type {--name= : Название типа токена}';

    public function __construct(
        private TokenTypeService $service
    )
    {
        parent::__construct();
    }

    public function handle():int
    {
        $name = $this->option('name');

        $validator = Validator::make(
            ['name' => $name],
            [
                'name' => 'required|string|unique:token_types,name'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $tokenType = $this->service->create($name);
        $this->info("Тип токена успешно создан с id: {$tokenType->id}");
        return self::SUCCESS;
    }
}
