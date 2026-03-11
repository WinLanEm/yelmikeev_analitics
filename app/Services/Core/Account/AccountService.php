<?php

namespace App\Services\Core\Account;

use App\Contracts\Repositories\Core\Account\AccountRepository;
use App\Models\Account;

final class AccountService
{
    public function __construct(
        private AccountRepository $repository,
    ){}

    public function create(int $companyId, string $name):Account
    {
        return $this->repository->create($companyId, $name);
    }
}
