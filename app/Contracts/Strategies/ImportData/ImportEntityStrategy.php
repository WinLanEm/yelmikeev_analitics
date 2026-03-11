<?php

namespace App\Contracts\Strategies\ImportData;

use App\Models\ApiToken;

interface ImportEntityStrategy
{
    public function execute(ApiToken $token):void;
}
