<?php

namespace App\Domains\Account\Rules;

use App\Domains\Account\Models\Account;

interface AccountRule
{
    public function validate(Account $account, ?Account $relatedAccount = null): void;
}
