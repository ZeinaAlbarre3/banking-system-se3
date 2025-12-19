<?php

namespace App\Domains\Account\Strategy;

use App\Domains\Account\Enums\AccountTypeEnum;

class InterestStrategyFactory
{
    public function __construct(
        private readonly SavingInterestStrategy     $saving,
        private readonly CurrentInterestStrategy    $current,
        private readonly InvestmentInterestStrategy $investment,
        private readonly LoanInterestStrategy       $loan,
    ) {}

    public function forType(AccountTypeEnum $type): InterestStrategy
    {
        return match ($type) {
            AccountTypeEnum::SAVING => $this->saving,
            AccountTypeEnum::CURRENT => $this->current,
            AccountTypeEnum::INVESTMENT => $this->investment,
            AccountTypeEnum::LOAN => $this->loan,
        };
    }
}
