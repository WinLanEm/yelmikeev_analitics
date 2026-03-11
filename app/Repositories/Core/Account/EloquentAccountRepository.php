<?php

namespace App\Repositories\Core\Account;

use App\Contracts\Repositories\Core\Account\AccountRepository;
use App\Models\Account;

final class EloquentAccountRepository implements AccountRepository
{
    public function create(int $companyId, string $name): Account
    {
        return Account::create([
            'company_id' => $companyId,
            'name' => $name,
        ]);
    }
}
