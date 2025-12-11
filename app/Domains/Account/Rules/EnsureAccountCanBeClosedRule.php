<?php

namespace App\Domains\Account\Rules;

use App\Domains\Account\Exceptions\AccountRuleException;
use App\Domains\Account\Models\Account;
use Illuminate\Validation\ValidationException;

class EnsureAccountCanBeClosedRule implements AccountRule
{
    public function validate(Account $account, ?Account $relatedAccount = null): void
    {
        if (! $account->canBeClosed()) {
            throw new AccountRuleException('Cannot close account with non-zero balance.');
        }
    }
}
