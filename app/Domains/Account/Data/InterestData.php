<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountTypeEnum;
use Spatie\LaravelData\Data;

class InterestData extends Data
{
    public function __construct(
        public int $days,
        public float $market_rate,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            days: (int) $validated['days'],
            market_rate: (float) config('finance.market_rate', 0.03),
        );
    }
}
