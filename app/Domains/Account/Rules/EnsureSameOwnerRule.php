<?php

namespace App\Domains\Account\Rules;

use App\Domains\Account\Exceptions\AccountRuleException;
use App\Domains\Account\Models\Account;

class EnsureSameOwnerRule implements AccountRule
{
    public function validate(Account $account, ?Account $relatedAccount = null): void
    {
        if (! $relatedAccount) {
            return;
        }

        if ($account->user_id !== $relatedAccount->user_id) {
            throw new AccountRuleException('Parent account must belong to the same user.');
        }
    }
}
