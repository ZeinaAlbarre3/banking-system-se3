<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountTypeEnum;
use Spatie\LaravelData\Data;

class InterestResultData extends Data
{
    public function __construct(
        public string $account_reference,
        public AccountTypeEnum  $type,
        public float $market_rate,
        public int $days,
        public float $interest,
    ) {}
}
