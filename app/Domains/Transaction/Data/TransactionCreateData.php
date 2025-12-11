<?php

namespace App\Domains\Transaction\Data;

use App\Domains\Transaction\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Data;

class TransactionCreateData extends Data
{
    public function __construct(
        public TransactionTypeEnum $type,
        public string $account_reference,
        public float $amount,
        public ?string $related_account_reference,
        public ?string $currency,
        public ?array $metadata,
    ) {}
}
