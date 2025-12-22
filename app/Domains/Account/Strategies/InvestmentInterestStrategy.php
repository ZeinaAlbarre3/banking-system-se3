<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

class InvestmentInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float
    {
        $principal = (float) $account->balance;
        $rate = max(0.0, $data->market_rate + 0.01);

        return $principal * $rate * ($data->days / 365);
    }
}
