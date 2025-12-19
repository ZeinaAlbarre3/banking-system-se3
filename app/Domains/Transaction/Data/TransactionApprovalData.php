<?php

namespace App\Domains\Transaction\Data;


use App\Domains\Transaction\Models\Transaction;
use Spatie\LaravelData\Data;

class TransactionApprovalData extends Data
{
    public function __construct(
        public Transaction $transaction,
        public TransactionCreateData $data,
    ) {}
}
