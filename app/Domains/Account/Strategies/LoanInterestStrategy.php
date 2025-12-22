<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

class LoanInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float
    {
        $loanAmount = (float) $account->balance;
        $rate = max(0.0, $data->market_rate);

        return $loanAmount * $rate * ($data->days / 365);
    }
}
