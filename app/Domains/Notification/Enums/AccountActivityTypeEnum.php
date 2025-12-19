<?php

namespace App\Domains\Notification\Enums;

enum AccountActivityTypeEnum: string
{
    case BALANCE_CHANGE = 'balance_change';
    case LARGE_TRANSACTION = 'large_transaction';
}
