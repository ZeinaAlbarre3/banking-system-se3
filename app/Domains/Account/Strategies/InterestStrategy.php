<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

interface InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float;
}
