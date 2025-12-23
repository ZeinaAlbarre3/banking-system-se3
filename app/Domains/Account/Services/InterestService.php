<?php

namespace App\Domains\Account\Services;

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Data\InterestResultData;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Strategies\InterestStrategyFactory;

class InterestService
{
    public function __construct(
        private readonly InterestStrategyFactory $factory
    )
    {
    }

    public function calculate(Account $account, InterestData $data): InterestResultData
    {
        $type = $account->type;
        $strategy = $this->factory->forType($type);

        $interest = round($strategy->calculate($account, $data), 2);

        return new InterestResultData(
            account_reference: $account->reference_number,
            type: $account->type,
            market_rate: $data->market_rate,
            days: $data->days,
            interest: $interest,
        );
    }
}
