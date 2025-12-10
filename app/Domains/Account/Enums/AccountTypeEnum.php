<?php

namespace App\Domains\Account\Enums;

enum AccountTypeEnum: string
{
    case SAVING     = 'saving';
    case CURRENT    = 'current';
    case LOAN       = 'loan';
    case INVESTMENT = 'investment';
}
