<?php

namespace App\Console\Commands\Core\Account;

use App\Services\Core\Account\AccountService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

final class CreateAccount extends Command
{
    protected $signature = 'create:account {--company_id= : ID компании} {--name= : Название аккаунта}';

    public function __construct(
        private AccountService $service
    )
    {
        parent::__construct();
    }

    public function handle():int
    {
        $companyId = $this->option('company_id');
        $name = $this->option('name');

        $validator = Validator::make(
            ['company_id' => $companyId, 'name' => $name],
            [
                'company_id' => 'required|int|exists:companies,id',
                'name' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $account = $this->service->create($companyId,$name);
        $this->info("Аккаунт успешно создан с id: {$account->id}");
        return self::SUCCESS;
    }
}
