<?php

namespace App\Contracts\Repositories\Core\Account;

use App\Models\Account;

interface AccountRepository
{
    public function create(int $companyId, string $name):Account;
}
