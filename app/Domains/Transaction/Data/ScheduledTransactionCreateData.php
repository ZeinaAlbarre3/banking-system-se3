<?php

namespace App\Domains\Transaction\Data;

use App\Domains\Transaction\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Data;

class ScheduledTransactionCreateData extends Data
{
    public function __construct(
        public string $account_reference,
        public ?string $related_account_reference,
        public TransactionTypeEnum $type,
        public float $amount,
        public string $frequency,
        public ?int $day_of_week,
        public ?int $day_of_month,
        public string $time_of_day,
        public string $timezone,
        public ?array $metadata,
    ) {}
}
