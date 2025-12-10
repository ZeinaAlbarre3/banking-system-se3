<?php

namespace App\Domains\Account\Rules;

use App\Domains\Account\Models\Account;
use Illuminate\Validation\ValidationException;

class EnsureSameOwnerRule implements AccountRule
{
    public function validate(Account $account, ?Account $relatedAccount = null): void
    {
        if (! $relatedAccount) {
            return;
        }

        if ($account->user_id !== $relatedAccount->user_id) {
            throw ValidationException::withMessages([
                'parent_id' => 'Parent account must belong to the same user.',
            ]);
        }
    }
}
