<?php

namespace App\Services\Core\Company;

use App\Contracts\Repositories\Core\Company\CompanyRepository;
use App\Models\Company;

final class CompanyService
{
    public function __construct(
        private CompanyRepository $repository,
    ){}

    public function create(string $name):Company
    {
        return $this->repository->create($name);
    }
}
