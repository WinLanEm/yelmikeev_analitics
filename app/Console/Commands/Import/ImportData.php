<?php

namespace App\Console\Commands\Import;

use App\Enums\ApiEntity;
use App\Strategies\ImportDataStrategy;
use Illuminate\Console\Command;

final class ImportData extends Command
{
    protected $signature = 'api:import {--entity= : sales, orders, stocks, incomes}';

    protected $description = 'Command description';

    public function __construct(
        private ImportDataStrategy $importDataStrategy
    )
    {
        parent::__construct();
    }

    public function handle():void
    {
        $entity = ApiEntity::from($this->option('entity'));

        $this->importDataStrategy->import($entity);

        $this->info('Data imported');
    }
}
