<?php

namespace App\Repositories\Core\Company;

use App\Contracts\Repositories\Core\Company\CompanyRepository;
use App\Models\Company;

final class EloquentCompanyRepository implements CompanyRepository
{
    public function create(string $name): Company
    {
        return Company::create([
            'name' => $name,
        ]);
    }

}
