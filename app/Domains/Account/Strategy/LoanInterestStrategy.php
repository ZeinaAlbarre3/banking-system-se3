<?php

namespace App\Domains\Account\Strategy;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;

class LoanInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account, InterestData $data): float
    {
        $loanAmount = (float) ($account->metadata['loan_amount'] ?? 0);
        $loanRate   = (float) ($account->metadata['loan_rate'] ?? $data->market_rate);

        return $loanAmount * $loanRate * ($data->days / 365);
    }
}
