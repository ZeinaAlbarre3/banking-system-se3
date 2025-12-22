<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

class CurrentInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float
    {
        return 0.0;
    }
}
