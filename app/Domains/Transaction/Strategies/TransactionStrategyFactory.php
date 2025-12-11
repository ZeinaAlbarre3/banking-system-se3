<?php

namespace App\Domains\Transaction\Strategies;

use App\Domains\Transaction\Enums\TransactionTypeEnum;

class TransactionStrategyFactory
{
    public function __construct(
        private readonly DepositStrategy  $deposit,
        private readonly WithdrawStrategy $withdraw,
        private readonly TransferStrategy $transfer,
    )
    {
    }

    public function forType(TransactionTypeEnum $type): TransactionStrategy
    {
        return match ($type) {
            TransactionTypeEnum::DEPOSIT => $this->deposit,
            TransactionTypeEnum::WITHDRAW => $this->withdraw,
            TransactionTypeEnum::TRANSFER => $this->transfer,
        };
    }
}
