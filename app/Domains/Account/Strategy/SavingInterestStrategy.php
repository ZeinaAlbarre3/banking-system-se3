<?php

namespace App\Domains\Account\Strategy;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

class SavingInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float
    {
        $principal = (float) $account->balance;
        $rate = max(0.0, $data->market_rate);

        return $principal * $rate * ($data->days / 365);
    }
}
