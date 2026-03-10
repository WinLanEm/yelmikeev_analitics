<?php

namespace App\Contracts\Repositories\Core\Company;

use App\Models\Company;

interface CompanyRepository
{
    public function create(string $name):Company;
}
