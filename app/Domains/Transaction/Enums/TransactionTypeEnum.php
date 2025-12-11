<?php

namespace App\Domains\Transaction\Enums;

enum TransactionTypeEnum: string
{
    case DEPOSIT  = 'deposit';
    case WITHDRAW = 'withdraw';
    case TRANSFER = 'transfer';
}
