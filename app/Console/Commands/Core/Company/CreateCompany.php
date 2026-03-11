<?php

namespace App\Console\Commands\Core\Company;

use App\Services\Core\Company\CompanyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

final class CreateCompany extends Command
{
    protected $signature = 'create:company {--name= : Название компании}';

    public function __construct(
        private CompanyService $service
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
                'name' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $company = $this->service->create($name);
        $this->info("Компания успешно создана с id: {$company->id}");
        return self::SUCCESS;
    }
}
