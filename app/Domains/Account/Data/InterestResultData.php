<?php

namespace App\Domains\Account\Data;

use Spatie\LaravelData\Data;

class InterestResultData extends Data
{
    public function __construct(
        public string $account_reference,
        public string $type,
        public float $market_rate,
        public int $days,
        public float $interest,
    ) {}
}
