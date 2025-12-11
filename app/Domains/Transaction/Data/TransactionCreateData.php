<?php

namespace App\Domains\Transaction\Data;

use App\Domains\Transaction\Enums\TransactionTypeEnum;
use Spatie\LaravelData\Data;

class TransactionCreateData extends Data
{
    public function __construct(
        public TransactionTypeEnum $type,
        public int $account_id,
        public float $amount,
        public ?int $related_account_id,
        public ?string $currency,
        public ?array $metadata,
    ) {}
}
