<?php

namespace App\Domains\Account\Rules;

use App\Domains\Account\Models\Account;
use Illuminate\Validation\ValidationException;

class EnsureNotSelfParentRule implements AccountRule
{
    public function validate(Account $account, ?Account $relatedAccount = null): void
    {
        if (! $relatedAccount) {
            return;
        }

        if ($account->id === $relatedAccount->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'Account cannot be its own parent.',
            ]);
        }
    }
}
